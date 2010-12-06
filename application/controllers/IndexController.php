<?php
// application/controllers/IndexController.php


class IndexController extends Zend_Controller_Action {
	
	
	function init() {
		Zend_Loader::loadClass('table_Users');
		$this->initView();
		$this->_helper->layout->setLayout('portal');
		
	}
	
	function indexAction() {
		$this->loginAction();
		$this->render();
	}
	
	function loginAction() {
		require_once  'login.php';
        $form = new forms_login();

        if(!$this->getRequest()->isPost()) {
            $this->view->loginForm = $form;
            return;
        }
        
        $values = array('username' => $_POST['username'], 'password' => $_POST['password']);
        
        // Setup DbTable adapter
        $adapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter()); // set earlier in Bootstrap
        $adapter->setTableName('sis_users');
        
        $adapter->setIdentityColumn('CAUS_TX_Usuario');
        $adapter->setCredentialColumn('CAUS_TX_Senha');
        $adapter->setIdentity($values['username']);
        $adapter->setCredential($values['password']);
        
        // authentication attempt
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        
        // authentication succeeded
        if ($result->isValid()) {
           
            $data = $adapter->getResultRowObject(null,'password');
            $auth->getStorage()->write($data);
            $_SESSION['userid'] = $data->CAUS_NM_ID;
            
            return $this->_redirect('system/system');
            
            
        } else { // or not! Back to the login page!
            $this->view->failedAuthentication = true;
            $this->view->form = $form;
        } 

	}

	function logoutAction() {
		$_POST['username'] = null;
		$_POST['username'] = null;
		$this->_forward('index', 'index');
	}
	
	function registerAction() {
		
	}
    
    function dashboardAction(){
        //$this->view->updatehistoric = "../library/Bovespa/readHistoric.php";
    }
    
    
    public function historicAction() {
        Zend_Loader::loadClass('Bovespa_readHistoric');
        $update = new Bovespa_readHistoric();
        $this->_forward('dashboard', 'index');
    }
    
	
	public function regforwardAction() {
		$accept = $this->_request->getPost('accept');
		
		if ($accept == "yes") {
			return $this->_redirect('index/registerform');
		} else {
			return $this->_redirect('');
		}
	}
	
	public function regupdateAction() {
		$user = new table_Users();
		$firstname = $this->_request->getPost('firstname');
		$login = $this->_request->getPost('login');
		$email = $this->_request->getPost('email');
		$lastname = $this->_request->getPost('lastname');
		$password = $this->_request->getPost('password');
		/*$day = $this->_request->getPost('day');
		$month = $this->_request->getPost('month');
		$year = $this->_request->getPost('year');*/
		$data = array(
						'CAUS_TX_Nome' => $firstname,
						'CAUS_TX_Sobrenome' => $lastname,
						'CAUS_TX_Senha' => $password,
						/*'CAUS_NM_Dia_Nascimento' => $day,
						'CAUS_NM_Mes_Nascimento' => $month,
						'CAUS_NM_Ano_Nascimento' => $year,*/
						'CAUS_TX_EMAIL' => $email,
						'CAUS_TX_Usuario' => $login
						);
		$user->insert($data);
		$this->_redirect('index/index');
	}
	
	public function registerformAction() {
		require_once 'registration.php';
		$this->view->form = new forms_registration();
	}
	
	public function requireAction() {
		
	}
	
	public function aboutAction() {
		
	}
	
	
	function setView() {	
		$view = Zend_Registry::get('view');
		$view->assign('header','system/system_header.phtml');
		$view->assign('navigation','system/system_navigation.phtml');
		$view->assign('body','system/system_main.phtml');
		$view->assign('footer','system/system_footer.phtml');
		$this->_response->setBody($view->render('system/system.phtml'));
		}

}