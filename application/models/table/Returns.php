<?php

Class table_Returns extends Zend_Db_Table {
	
	protected $_name = 'sis_rentabilidades';
	protected $_primary = 'RENT_NM_ID';
	protected $db;
	public $returns;
	public $covariances;
	public $correlations;
	public $variances;
	public $stdDeviations;
	public $ArrayRet;
	private $_stocks;
	
	
	function __construct($stocks) {
		Zend_Loader::loadClass('Finance_Matrix');
		Zend_Loader::loadClass('table_Portfolio');
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$this->_stocks = $stocks;
		$this->ArrayRet = $this->ReturnsArray();
	}
	
	function init() {
		
	}
	
	function stockExpReturn() {//retorno esperado para mais de uma ação
		$i=0;
		foreach ($this->_stocks as $stock) {	
			$matrix = new Finance_Matrix($this->ArrayRet[$stock]);
			$this->returns[$i] = round($matrix->MeanMatrix($this->ArrayRet[$stock]),4);
			$i++;
		}
		return $this->returns;
	}
	
	function stockVariance() {
		$i=0;
		foreach ($this->_stocks as $stock) {	
			$matrix = new Finance_Matrix($this->ArrayRet[$stock],$this->ArrayRet[$stock]);
			$this->variances[$i] = round($matrix->Covariance($this->ArrayRet[$stock],$this->ArrayRet[$stock]),4);
			$i++;
		}
		return $this->variances;
	}
	
	function stdDeviation() {
		$i=0;
		foreach ($this->_stocks as $stock) {
			$this->stdDeviations[$i] = round(sqrt($this->variances[$i]),4);
			$i++;
		}
		return $this->stdDeviations;
	}
	
	public function stockVaR($conf = 1.65) {
		$i=0;
		foreach ($this->_stocks as $stock) {
			$VaR[$i] = $this->returns[$i] + $this->stdDeviations[$i]*$conf;
			$i++;
		}
		return $VaR;
	}
	
	public function portfolioCorrelations() {
		$i=0;
		foreach ($this->_stocks as $stock) {
			$rets[$i] = $this->ArrayRet[$stock];
			$i++;
		}
		$matrix = new Finance_Matrix($rets);
		$correlations = $matrix->correlationMatrix($rets);
		
		return $correlations;
	}
	
	public function portfolioVaR() {
		$corr = $this->portfolioCorrelations();
		$VaR = $this->stockVaR();
		$matrix = new Finance_Matrix($VaR, $corr);
		$mult1 = $matrix->multiplicationMatrix($VaR, $corr);
		
		$matrix = new Finance_Matrix($VaR);
		$VaRt = $matrix->transpose($VaR);
		
		$matrix = new Finance_Matrix($mult1, $VaRt);
		$mult2 = $matrix->multiplicationMatrix($mult1, $VaRt);
		
		$portCorr = sqrt($mult2[0]);
		
		return $portCorr;
	}
	
	
	function ReturnsArray() {
		foreach ($this->_stocks as $stock) {	
			$sql = sprintf("select RENT_NM_Rentabilidade from sis_rentabilidades where COAT_TX_ID='%s'", $stock);
			$arrayRet[$stock] = $this->db->fetchCol($sql);
		}
		return $arrayRet;
	}
	
	function portfolioCovariances() {
		$i=0;
		foreach ($this->_stocks as $stock) {
			$rets[$i] = $this->ArrayRet[$stock];
			$i++;
		}		
		$matrix = new Finance_Matrix($rets);
		$this->covariances = $matrix->covarianceMatrix($rets);
		
		return $this->covariances;
	}
	
	public function portfolioReturn() {
		$portfolio = new table_Portfolio();
		$w = $portfolio->weights();
		$n=0;
		foreach ($w as $i) {
			$weights[$n] = $i;
			$n++;
		}
		
		$returns = $this->stockExpReturn();
		$count = count($weights);
		$portfolioReturn = 0;
		for ($i=0;$i<$count;$i++) {
			$portfolioReturn = $portfolioReturn + $weights[$i]*$returns[$i];
		}
		
		return $portfolioReturn;
	}
	
	public function portfolioStdDeviation() {
		$portfolio = new table_Portfolio();
		$w = $portfolio->weights();
		$n=0;
		foreach ($w as $i) {
			$weights[$n] = $i;
			$n++;
		}
		
		$rets = $this->ReturnsArray();
		$j = 0;
		foreach ($rets as $r) {
			$returns[$j] = $r;
			$j++;
		}
		
		$matrix = new Finance_Matrix($weights, $rets);
		$variance = $matrix->variancePortfolio($weights, $returns);
		$portfolioStdDeviation = sqrt($variance);
		return $portfolioStdDeviation;
	}
	
}