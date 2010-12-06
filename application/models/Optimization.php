<?php

Class Optimization {
	
	public $stocks;
	private $_numAssets;
	private $_returns;
	private $_expReturns;
	private $_covariances;
	private $_weightsEF;
	private $_returnsEF;
	private $_stdeviationsEF;
	
	function __construct($stocks) {
		$this->stocks = $stocks;
		$this->init();
	}
	
	
	function init() {
		Zend_Loader::loadClass('Finance_Matrix');
		Zend_Loader::loadClass('Weboctave_apiOctave');
		Zend_Loader::loadClass('table_Returns');
		
		$tblReturns = new table_Returns($this->stocks);
		$this->_covariances = $tblReturns->portfolioCovariances();
		$this->_expReturns = $tblReturns->stockExpReturn();
		
		$this->_eficientFrontier();
	}
	
	
	private function _eficientFrontier() {
		$ef = new Weboctave_apiOctave($_SESSION['userid'],$this->_covariances,$this->_expReturns);
		list($this->_weightsEF, $this->_stdeviationsEF, $this->_returnsEF) = $ef->efficientFrontier();
	}
	
	public function getVaREF() {
		$var = new table_Returns();
		$portVAR = $var->portfolioVaR();
		return $portVAR;
	}
	
	public function getWeightsEF() {
		return $this->_weightsEF;
	}
	
	public function getReturnsEF() {
		return $this->_returnsEF;
	}
	
	public function getStDeviationsEF() {
		return $this->_stdeviationsEF;
	}
	
	
	
	
}


