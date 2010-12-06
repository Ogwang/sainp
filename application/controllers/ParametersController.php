<?php

Class ParametersController extends Zend_Controller_Action {
	
	function init() {
		Zend_Loader::loadClass('table_Users');
		Zend_Loader::loadClass('table_Parametrizacao');
		$this->initView();
		$this->_helper->layout->setLayout('system');
		$this->view->navigation = $this->_helper->setnavigation();
	}
	
	/*public function setNavigation() {
		$this->navigation = '../parameters/navigation.phtml';
	}*/
	
	
	
	function parametersAction() {
		$param = new table_Parametrizacao();
		$this->view->param = $param->fetchRow('CAUS_NM_ID='.$_SESSION['userid']);
		$body = 'parameters/parameters.phtml';
		$action='updateparam';
		//$this->setView($body,$action);
		//$render = $this->_helper->setview($body,$action,$this->_navigation);
		//$this->_response->setBody($render);
	}
	
	
	function paramuserAction() {
		$user = new table_Users();
		$this->view->user = $user->fetchRow('CAUS_NM_ID='.$_SESSION['userid']);
		//$body = 'parameters/paramuser.phtml';
		//$action = 'updateuser';
		//$render = $this->_helper->setview($body,$action,$this->_navigation);
		//$this->_response->setBody($render);
	}
	
	
	
	function updateuserAction() {
		$user = new table_Users();
		$firstname = $this->_request->getPost('firstname');
		$lastname = $this->_request->getPost('lastname');
		$password = $this->_request->getPost('password');
		$day = $this->_request->getPost('day');
		$month = $this->_request->getPost('month');
		$year = $this->_request->getPost('year');
		$data = array(
						'CAUS_TX_Nome' => $firstname,
						'CAUS_TX_Sobrenome' => $lastname,
						'CAUS_TX_Senha' => $password,
						'CAUS_NM_Dia_Nascimento' => $day,
						'CAUS_NM_Mes_Nascimento' => $month,
						'CAUS_NM_Ano_Nascimento' => $year
						);
		$where = 'CAUS_NM_ID='.$_SESSION['userid'];
		$user->update($data, $where);
		$this->paramuserAction();
	}
	
	function updateparamAction() {
		$param = new table_Parametrizacao();
		$corretperc = $this->_request->getPost('tx_corret_perc');
		$corretreais = $this->_request->getPost('tx_corret_reais');
		$emolperc = $this->_request->getPost('tx_emol_perc');
		$emolreais = $this->_request->getPost('tx_emol_reais');
		$custreais = $this->_request->getPost('custodia');
		$ir = $this->_request->getPost('tx_ir');
		$rf = $this->_request->getPost('tx_rf');
		$data = array(
						'PARA_NM_Taxa_Corretagem_Perc' => $corretperc,
						'PARA_VL_Taxa_Corretagem_Reais' => $corretreais,
						'PARA_VL_Taxa_Emolumentos_Perc' => $emolperc,
						'PARA_VL_Taxa_Emolumentos_Reais' => $emolreais,
						'PARA_VL_Custodia_Reais' => $custreais,
						'PARA_NM_IR' => $ir,
						'PARA_NM_RF' => $rf,
						);
		$where = 'CAUS_NM_ID='.$_SESSION['userid'];
		$param->update($data, $where);
		$this->parametersAction();
	}	
	

	
	
}