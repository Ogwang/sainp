<?php

Class table_balance_Dre extends Zend_Db_Table {

	protected $_name = "bal_dre";
	protected $_primary = "DREE_NM_ID";
	
	
	public $dre;
	
	private $_year;
	private $_stock;
	
	function init() {
		Zend_Loader::loadClass('table_balance_Dre');
		
	}
	
	
	function DRE($stock, $year) {
		$this->_getStock($stock);
		$this->_getYear($year);
		
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$sql = sprintf("SELECT * FROM bal_dre where COEM_TX_ID='%s' AND DREE_NM_Ano='%s'", $this->_stock, $this->_year);
		$this->dre = $this->db->fetchRow($sql);
		
		return $this->dre;
	}
	
	
	
	private function _getStock($stock) {
		$this->_stock = $stock;
	}
	
	private function _getYear($year) {
		$this->_year = $year;
	}
	
}
	