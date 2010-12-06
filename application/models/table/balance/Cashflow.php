<?php
  
Class table_balance_Cashflow extends Zend_Db_Table {
	
	protected $_name = "bal_fluxocaixa";
	protected $_primary = "FLCA_NM_ID";
	
	public $cashflow;
	
	private $_year;
	private $_stock;
	
	function init() {
		Zend_Loader::loadClass('table_balance_Cashflow');
		
	}
	
	
	function cashflow($stock, $year) {
		$this->_getStock($stock);
		$this->_getYear($year);
		
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$sql = sprintf("SELECT * FROM bal_fluxocaixa where COEM_TX_ID='%s' AND FLCA_NM_Ano='%s'", $this->_stock, $this->_year);
		$this->cashflow = $this->db->fetchRow($sql);
		
		return $this->cashflow;
	}
	
	
	
	private function _getStock($stock) {
		$this->_stock = $stock;
	}
	
	private function _getYear($year) {
		$this->_year = $year;
	}
	
}