<?php
  
Class table_balance_Balancesheet extends Zend_Db_Table {
	
	protected $_name = "bal_balancopatrimonial";
	protected $_primary = "BAPA_NM_ID";
	
	public $balancesheet;
	
	private $_year;
	private $_stock;
	
	function init() {
		Zend_Loader::loadClass('table_balance_Balancesheet');
		
	}
	
	
	function Balancesheet($stock, $year) {
		$this->_getStock($stock);
		$this->_getYear($year);
		
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$sql = sprintf("SELECT * FROM bal_balancopatrimonial where COEM_TX_ID='%s' AND BAPA_NM_Ano='%s'", $this->_stock, $this->_year);
		$this->balancesheet = $this->db->fetchRow($sql);
		
		return $this->balancesheet;
	}
	
	
	
	private function _getStock($stock) {
		$this->_stock = $stock;
	}
	
	private function _getYear($year) {
		$this->_year = $year;
	}
	
}