<?php

Class ClassificationController extends Zend_Controller_Action {
	
	private $_selectedSector;
	private $_selectedSubsector;
	private $_SelectedSegment;
	
	
	function init() {
		$this->initView();
		$this->_helper->layout->setLayout('system');
		Zend_Loader::loadClass('table_Classification');
		Zend_Loader::loadClass('table_Companies');
		$this->_selectedSector = $this->_request->getParam('sector');
		$this->_selectedSubsector = $this->_request->getParam('subsector');
		$this->_selectedSegment = $this->_request->getParam('segment');
	}
	
	
	public function classificationAction() {
		
		$class = new table_Classification();
		$this->view->sectors = $class->getSector($this->_selectedSector);
		$this->view->subsectors = $class->getSubsector($this->_selectedSector, $this->_selectedSubsector);
		$this->view->segments = $class->getSegment($this->_selectedSector, $this->_selectedSubsector, $this->_selectedSegment);
		
		$companies = new table_Companies();
		$this->view->companies = $companies->getCompanies();
		
		if ($this->_selectedSubsector == null && $this->_selectedSegment == null) {
			$this->view->showCompanies = false;
		} else {
			$this->view->showCompanies = true;
		}
	}
	
	
}

