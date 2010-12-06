<?php

Class table_system_Segments extends Zend_Db_Table {
	
	protected $_name = 'sis_classificacao_segmento';
	protected $_primary = 'CLSG_NM_ID';
	
	private $_sectors;
	private $_subsectors;
	private $_segments;
	private $_ID;
	
	
	function init() {
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
	}
	
	function getSegment($sector=null,$subsector=null,$segment=null) {
		if ($sector == null && $subsector == null && $segment == null) {
			$sql = 'select * from sis_classificacao_segmento';
		} elseif ($sector != null && $subsector == null && $segment == null) {
			$sql = sprintf("select * from sis_classificacao_segmento where CLSE_NM_ID='%s'",$sector);
		} elseif ($sector == null && $subsector != null && $segment == null) {
			$sql = sprintf("select * from sis_classificacao_segmento where CLSU_NM_ID='%s'",$sector);
		} elseif ($sector != null && $subsector != null && $segment == null) {
			$sql = sprintf("select * from sis_classificacao_segmento where CLSE_NM_ID='%s' and CLSU_NM_ID='%s'",$sector,$subsector);
		} elseif ($sector == null && $subsector == null && $segment != null) {
			$sql = sprintf("select * from sis_classificacao_segmento where CLSG_NM_ID='s'",$segment);
		} elseif ($sector != null && $subsector == null && $segment != null) {
			$sql = sprintf("select * from sis_classificacao_segmento where CLSE_NM_ID='%s' and CLSG_NM_ID='%s'",$sector, $segment);
		} elseif ($sector == null && $subsector != null && $segment != null) {
			$sql = sprintf("select * from sis_classificacao_segmento where CLSU_NM_ID='%s' and CLSG_NM_ID='%s'",$sector, $segment);
		} elseif ($sector != null && $subsector != null && $segment != null) {
			$sql = sprintf("select * from sis_classificacao_segmento where CLSE_NM_ID='%s' and CLSU_NM_ID='%s' and CLSG_NM_ID='%s'",$sector,$subsector, $segment);
		}
		$this->_segment = $this->db->fetchAll($sql);
		return $this->_segment;
	}
	
	
}