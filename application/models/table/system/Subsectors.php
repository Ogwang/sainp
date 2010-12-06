<?php

Class table_system_Subsectors extends Zend_Db_Table {
	
	protected $_name = 'sis_classificacao_subsetor';
	protected $_primary = 'CLSU_NM_ID';
	
	private $_sectors;
	private $_subsectors;
	private $_ID;
	
	
	function init() {
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
	}
	
	function getSubsector($sector=null,$subsector=null) {
		if ($sector == null && $subsector == null) {
			$sql = 'select * from sis_classificacao_subsetor';
		} elseif ($sector != null && $subsector == null) {
			$sql = sprintf("select * from sis_classificacao_subsetor where CLSE_NM_ID='%s'",$sector);
		} elseif ($sector == null && $subsector != null) {
			$sql = sprintf("select * from sis_classificacao_subsetor where CLSU_NM_ID='%s'",$subsector);
		} elseif ($sector != null && $subsector != null) {
			$sql = sprintf("select * from sis_classificacao_subsetor where CLSE_NM_ID='%s' and CLSU_NM_ID='%s'",$sector,$subsector);
		}
		$this->_subsectors = $this->db->fetchAll($sql);
		return $this->_subsectors;
	}
	
	
}