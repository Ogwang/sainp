<?php

Class PortfolioController extends Zend_Controller_Action {
	
	public $portfolio;
	public $stocks;
	public $returns;
	public $variances;
	public $weights;
	public $stddev; //standard deviation
	
	
	
	function init() {
		Zend_Loader::loadClass('table_Portfolio');
		Zend_Loader::loadClass('table_Users');
		Zend_Loader::loadClass('table_Returns');
		Zend_Loader::loadClass('Optimization');
		Zend_Loader::loadClass('table_Buy');
		Zend_Loader::loadClass('table_Sell');
		Zend_Loader::loadClass('table_Stocks');
		$this->initView();
		$this->getStocks();
		if ($this->stocks != null) {
			$this->getReturns();
		}
		//$this->_setNavigation();
		$this->_helper->layout->setLayout('system');
		$this->view->navigation = $this->_helper->setnavigation();
	}
	
	/*public function _setNavigation() {
		$this->navigation = '../portfolio/navigation.phtml';
	}*/
	
	public function portfolioAction() {
		
		if ($this->stocks != null) {
			$this->view->portfolio = $this->portfolio->fetchAll('CAUS_NM_ID='.$_SESSION['userid']);
			$this->view->weights = $this->weights;
			
			
			$this->view->company = $this->portfolio->getCompany();
			
			$this->view->returns = $this->returns;
			//$this->view->variances = $this->variances;
			$this->view->stddev = $this->stddev;
			$this->view->VaR = $this->Returns->stockVaR();
			$this->view->portReturn = $this->Returns->portfolioReturn();
			$this->view->portStdDeviation = $this->Returns->portfolioStdDeviation();
			$this->view->portVaR = $this->Returns->portfolioVaR();
		}
	}
	
	public function optimizationAction() {
		if ($this->stocks != null) {
			$opt = new Optimization($this->stocks);
			
			$this->view->stocks = $this->stocks;
			$this->view->weightsEF = $opt->getWeightsEF();
			$this->view->expreturnsEF = $opt->getReturnsEF();
			$this->view->deviationsEF = $opt->getStDeviationsEF();
			//$this->view->VaREF = $opt->getVaREF();
		}

	}
	
	public function protectionAction() {
		$this->view->protArray = array(0, 5, 10, 15, 20, 25, 30);
		
	}
	
	public function buyAction() {
		require_once 'buy.php';
		$this->view->form = new forms_buy();
	}
	
	public function buyupdateAction() {
		$stock = $this->_request->getParam('stock');
		$day = $this->_request->getParam('day');
		$month = $this->_request->getParam('month');
		$year = $this->_request->getParam('year');
		$qty = $this->_request->getParam('quantity');
		$value = $this->_request->getParam('value');
		$data = array(
						'CAUS_NM_ID' => $_SESSION['userid'],
						'COAT_TX_ID' => $stock,
						'COMP_NM_Dia' => $day,
						'COMP_NM_Mes' => $month,
						'COMP_NM_Ano' => $year,
						'COMP_NM_Acoes' => $qty,
						'COMP_VL_Preco' => $value);
		$buy = new table_Buy();
		$buy->insert($data);
		table_Buy::buyStock($stock,$qty);
		$this->_redirect('portfolio/portfolio');
	}
	
	public function sellAction() {
		
		require_once 'sell.php';
		$this->view->form = new forms_sell();
	}
	
	public function updatesellAction() {
		$stock = $this->_request->getParam('stock');
		$day = $this->_request->getParam('day');
		$month = $this->_request->getParam('month');
		$year = $this->_request->getParam('year');
		$qty = $this->_request->getParam('quantity');
		$value = $this->_request->getParam('value');
		$data = array(
						'CAUS_NM_ID' => $_SESSION['userid'],
						'COAT_TX_ID' => $stock,
						'VEND_NM_Dia' => $day,
						'VEND_NM_Mes' => $month,
						'VEND_NM_Ano' => $year,
						'VEND_NM_Acoes' => $qty,
						'VEND_VL_Preco' => $value);
		$sell = new table_Sell();
		$sell->insert($data);
		table_Sell::sellStock($stock,$qty);
		$this->_redirect('portfolio/portfolio');
	}
	
	private function getStocks() {
		$this->portfolio = new table_Portfolio();
		$this->stocks = $this->portfolio->stocks();
		if ($this->stocks != null) {
			$this->weights = $this->portfolio->weights();
		}
		
	}
	
	private function getReturns() {
		$this->Returns = new table_Returns($this->stocks);
		$this->returns = $this->Returns->stockExpReturn();
		$this->variances = $this->Returns->stockVariance();
		$this->stddev = $this->Returns->stdDeviation();
	}

	
}
