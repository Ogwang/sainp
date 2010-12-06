<?php

class SystemController extends Zend_Controller_Action {
	
	protected $_userid;
	protected $_username;


	function init() {
		$this->initView();
		$this->_helper->layout->setLayout('system');
		$this->view->navigation = $this->_helper->setnavigation();
	}
	
	function systemAction() {
		//$this->initView();
	}
	
	function setView() {
		$view = Zend_Registry::get('view');
		$view->assign('header','system/system_header.phtml');
		$view->assign('sys_navigation','system/system_navigation.phtml');
		//$view->assign('body_navigation', null);
		$view->assign('body','system/system_main.phtml');
		$view->assign('background','system/system_background.phtml');
		$view->assign('footer','system/system_footer.phtml');
	  	$this->_response->setBody($view->render('system/system.phtml'));
	}
	
	
	
	
}