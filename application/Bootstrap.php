<?php 
require_once './library/Zend/Loader.php';  
require_once './public/setDirPath.php';
  
class Bootstrap  
{ 
	
	
    //Primary function will be called first from index.php, it will call rest of functions to boot  
    public static function run() {
    	self::setupPath();
    	self::definePaths();
    	self::defineTimezone();
    	self::classLoader();
    	self::sessionStart();
        self::errorHandling();
        self::setupRegistry();
        self::setupView();
        self::setupDatabase();
        self::setupController();
    }
    
      
    private static function errorHandling() {
		error_reporting(E_ALL|E_STRICT);
    }
    
    private static function setupPath() {
		set_include_path('./'
		. PATH_SEPARATOR . './library/'
		. PATH_SEPARATOR . './library/Zend/'
		. PATH_SEPARATOR . './application/'
		. PATH_SEPARATOR . './application/models/'
		. PATH_SEPARATOR . './application/forms/'
		. PATH_SEPARATOR . './application/controllers/'
		. PATH_SEPARATOR . './application/views/'
		. PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']
		. PATH_SEPARATOR . get_include_path());
    }
    
    private static function classLoader() {
		Zend_Loader::loadClass('Zend_Controller_Front');
		Zend_Loader::loadClass('Zend_Controller_Action');
		Zend_Loader::loadClass('Zend_Config_Ini');
		Zend_Loader::loadClass('Zend_Registry');
		Zend_Loader::loadClass('Zend_Db');
		Zend_Loader::loadClass('Zend_Db_Table');
		Zend_Loader::loadClass('Zend_Form');
		Zend_Loader::loadClass('Zend_Session');
		Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
		Zend_Loader::loadClass('Zend_Auth');
		Zend_Loader::loadClass('Zend_View');
		Zend_Loader::loadClass('Zend_Controller_Action_Helper_ViewRenderer');
		Zend_Loader::loadClass('Zend_View_Helper_Abstract');
		Zend_Loader::loadClass('Zend_Controller_Action_HelperBroker');
		Zend_Loader::loadClass('Zend_Layout');
    }
    
  
  	private static function setupRegistry() {
		// load configuration
		$host = $_SERVER['HTTP_HOST'];
		if ($host == "localhost") { $environment = 'production';}
		else { $environment = 'development'; }
		
		$config = new Zend_Config_Ini('./application/configs/application.ini', $environment);
		$registry = Zend_Registry::getInstance();
		$registry->set('config', $config);
	}
		
	private static function setupDatabase() {
		// setup database
		$host = $_SERVER['HTTP_HOST'];
		if ($host == "localhost") { $environment = 'development';}
		else { $environment = 'production'; }
		
		$config = new Zend_Config_Ini('./application/configs/application.ini', $environment);
		$db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
		Zend_Db_Table::setDefaultAdapter($db);
		Zend_Registry::set('db', $db); 
	}
		
	private static function setupController() {
		// setup controller
		$frontController = Zend_Controller_Front::getInstance();
		$frontController->throwExceptions(true);
		$frontController->setControllerDirectory('./application/controllers');
		$baseURL = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '/index.php'));
		$frontController->setbaseUrl($baseURL);
		Zend_Controller_Action_HelperBroker::addPath('helpers', 'MyActionHelpers');
		
		
		// run!
		$frontController->dispatch();
  	}
  	
  	private static function setupView() {
		$view = new Zend_View;  
        $view->setEncoding('ISO-8859-1');  
        $view->setEscape('htmlentities');
        $view->setBasePath('./application/views/');	/** Define o diretório onde estarão as visões */
        $view->addHelperPath('./application/views/helpers','View_Helper');
		Zend_Registry::set('view', $view); 
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);  
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);  
        
        Zend_Layout::startMvc(array('layoutPath' => './application/layouts'));
        
        
  	}
  	
  	private static function sessionStart() {
  		Zend_Session::start();
  	}
  	
  	private static function definePaths() {
		workPath();
  	}
  	
  	private static function defineTimezone() {
		date_default_timezone_set("America/Sao_Paulo");
  	}
   
}