<?php

Class table_Companies extends Zend_Db_Table {
	
	protected $_name = 'sis_codigo_empresa';
	protected $_primary = 'COEM_TX_ID';
	protected $_dependentTables = array('Classifiction');
	//protected $_dependentTables = array('Stocks');
	private $_companies;
	
	
	
	
	function init() {
		Zend_Loader::loadClass('table_Classification');
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
	}
	
	
	public function getCompanies() {
		$sql = "SELECT * FROM sis_codigo_empresa";
		$this->_companies = $this->db->fetchAll($sql);
		return $this->_companies;
	}
	
}