<?php
  
Class table_balance_Doar extends Zend_Db_Table {
	
	protected $_name = "bal_doar";
	protected $_primary = "DOAR_NM_ID";
	
	public $doar;
	
	private $_year;
	private $_stock;
	
	function init() {
		Zend_Loader::loadClass('table_balance_Doar');
		
	}
	
	
	function DOAR($stock, $year) {
		$this->_getStock($stock);
		$this->_getYear($year);
		
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$sql = sprintf("SELECT * FROM bal_doar where COEM_TX_ID='%s' AND DOAR_NM_Ano='%s'", $this->_stock, $this->_year);
		$this->doar = $this->db->fetchRow($sql);
		
		return $this->doar;
	}
	
	
	
	private function _getStock($stock) {
		$this->_stock = $stock;
	}
	
	private function _getYear($year) {
		$this->_year = $year;
	}
	
}