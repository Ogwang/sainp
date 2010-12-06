<?php

Class table_system_Sectors extends Zend_Db_Table {
	
	protected $_name = 'sis_classificacao_setor';
	protected $_primary = 'CLSE_NM_ID';
	
	private $_sectors;
		
	
	function init() {
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
	}
	
	function getSector($sector=null) {
		if ($sector == null) {
			$sql = 'select * from sis_classificacao_setor';
		} else {
			$sql = sprintf('select * from sis_classificacao_setor where CLSE_NM_ID="%s"',$sector);
		}
		$this->_sectors = $this->db->fetchAll($sql);
		return $this->_sectors;
	}
	
	
}