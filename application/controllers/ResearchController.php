<?php

Class ResearchController extends Zend_Controller_Action {

	private $_company;
	private $_classification;
	
	private $_year;
	private $_stock;
	private $_actionName;
	
	
  	function init() {
		date_default_timezone_set('America/Los_Angeles');
		$this->initView();
		Zend_Loader::loadClass('table_Classification');
		Zend_Loader::loadClass('table_Companies');
		Zend_Loader::loadClass('table_balance_Doar');
		Zend_Loader::loadClass('table_balance_Dre');
		Zend_Loader::loadClass('table_balance_Cashflow');
		Zend_Loader::loadClass('table_balance_Balancesheet');
		$this->_helper->layout->setLayout('system');
		$this->view->navigation = $this->_helper->setnavigation();
		$this->_stock = $this->_request->getParam('code');
		$this->_year = $this->_request->getParam('year');
		$this->_actionName = $this->_request->getParam('actionName');
	}
	
	public function researchAction() {
		$this->view->navigation = null;
		
		$sector = $this->view->selectedSector = $this->_request->getPost('sector');
		$subsector = $this->view->selectedSubsector = $this->_request->getPost('subsector');
		$segment = $this->view->selectedSegment = $this->_request->getPost('segment');
		
		//$companies = new table_Companies();
		//$this->view->companies = $companies->fetchAll();
		
		$class = new table_Classification();
		//$this->view->class = $class->fetchAll();
		$this->view->sector = $class->getSector($sector);
		$this->view->subsector = $class->getSubsector($sector, $subsector);
		$this->view->segment = $class->getSegment($sector, $subsector, $segment);
		$this->view->companies = $class->getClassification();
	}
	
	public function summaryAction() {
		$this->_headerprep();
		$this->view->page = "Resumo";
	}
	
	public function cashflowAction() {
		if ($this->_year == null) {
			$this->_year = date("Y");
		}
		
		$this->_headerprep();
		$this->view->page = "Fluxo de Caixa";
		
		$CF = new table_balance_Cashflow();
		$this->view->cashflow = $CF->cashflow($this->_stock, $this->_year);
	}
	
	public function balanceAction() {
		if ($this->_year == null) {
			$this->_year = date("Y");
		}
		
		$this->_headerprep();
		$this->view->page = "Balanço Patrimonial";
		
		$BS = new table_balance_Balancesheet();
		$this->view->balancesheet = $BS->balancesheet($this->_stock, $this->_year);
	}
	
	public function indicatorsAction() {
		$this->_headerprep();
		$this->view->page = "Indicadores";
	}
	
	public function doarAction() {
		
		if ($this->_year == null) {
			$this->_year = date("Y");
		}
		
		$this->_headerprep();
		$this->view->page = "DOAR";
		
		$doar = new table_balance_Doar();
		$this->view->doar = $doar->DOAR($this->_stock, $this->_year);
	}
	
	public function dreAction() {
		if ($this->_year == null) {
			$this->_year = date("Y");
		}
		
		$this->_headerprep();
		$this->view->page = "DRE";
		
		$dre = new table_balance_Dre();
		$this->view->dre = $dre->DRE($this->_stock, $this->_year);
	}
	
	private function _headerprep() {
		$companies = new table_Companies();
		$this->view->company = $companies->fetchRow("COEM_TX_ID='".$this->_stock."'");
		
		$class = new table_Classification();
		$this->view->classification = $class->getClassification($this->_stock);
	}
	
	public function forwardyearAction() {
		$this->_headerprep();
		$this->_year = $this->_year + 1;
		
		$redirect = "research/" . $this->_actionName . "/code/" . "VALE" . "/year/" . $this->_year;
		
		$this->_redirect($redirect);
		
	}
	
	public function rewardyearAction() {
		$this->_headerprep();
		$this->_year = $this->_year - 1;
		
		$redirect = "research/" . $this->_actionName . "/code/" . "VALE" . "/year/" . $this->_year;
		
		$this->_redirect($redirect);
		
	}
	
}