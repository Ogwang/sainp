<?php

Class MyActionHelpers_Setnavigation extends Zend_Controller_Action_Helper_Abstract {
	
	public function direct() {
		$controllername = $this->getRequest()->getControllerName();
		$nav = sprintf("%s/navigation.phtml", $controllername);
		
		$path = "application/views/scripts/".$nav;
		
		if (file_exists($path)) {
			return $nav;
		} else {
			return null;
		}
	}
	
}