<?php


Class MyActionHelpers_Setview extends Zend_Controller_Action_Helper_Abstract {
	
	public function direct($body, $action = null,$navigation = null) {
		//$this->initView();
		$view = Zend_Registry::get('view');
		//$view->assign('header','system/system_header.phtml');
		//$view->assign('sys_navigation','system/system_navigation.phtml');
		
		if ($navigation != null) {
			$view->assign('body_navigation', $navigation);
		}
		
		$view->assign('body',$body);
		//$view->assign('background','system/system_background.phtml');
		//$view->assign('footer','system/system_footer.phtml');
		$view->action = $action;
		$view->buttonText = 'Pesquisar';
	  	//$this->_response->setBody($view->render('system/system.phtml'));
	  	//return $view->render('system/system.phtml');
	}
	
}