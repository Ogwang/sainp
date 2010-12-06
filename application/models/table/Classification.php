<?php

Class table_Classification extends Zend_Db_Table {
	
	protected $_name = 'sis_classificacaosetorial';
	protected $_primary = 'CLSE_NM_ID';
	
	private $_sectors;
	private $_subsectors;
	private $_segments;
	private $_ID;
	
	
	function init() {
		Zend_Loader::loadClass('table_system_Sectors');
		Zend_Loader::loadClass('table_system_Subsectors');
		Zend_Loader::loadClass('table_system_Segments');
		
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
	}
	
	
	public function getSector($sector=null) {
		$sectors = new table_system_Sectors();
		$sec = $sectors->getSector($sector);
		return $sec;
	}
	
	public function getSubsector($sector=null,$subsector=null) {
		$subsectors = new table_system_Subsectors();
		$sub = $subsectors->getSubsector($sector,$subsector);
		return $sub;
	}
	
	public function getSegment($sector=null,$subsector=null,$segment=null) {
		$segments = new table_system_Segments();
		$seg = $segments->getSegment($sector,$subsector,$segment);
		return $seg;
	}
	
	
	public function getClassification ($company=null) {
		$sql = "SELECT sis_codigo_empresa.COEM_TX_ID, sis_codigo_empresa.COEM_Nome_Pregao, sis_classificacao_setor.CLSE_TX_Setor_Economico, sis_classificacao_setor.CLSE_NM_ID, sis_classificacao_subsetor.CLSU_TX_Subsetor_Economico, sis_classificacao_subsetor.CLSU_NM_ID, sis_classificacao_segmento.CLSG_TX_Segmento_Economico, sis_classificacao_segmento.CLSG_NM_ID
FROM sis_codigo_empresa LEFT JOIN sis_classificacao_setor ON sis_codigo_empresa.CLSE_NM_ID = sis_classificacao_setor.CLSE_NM_ID LEFT JOIN sis_classificacao_subsetor ON sis_codigo_empresa.CLSU_NM_ID = sis_classificacao_subsetor.CLSU_NM_ID LEFT JOIN sis_classificacao_segmento ON sis_codigo_empresa.CLSG_NM_ID = sis_classificacao_segmento.CLSG_NM_ID";
		if ($company != null) {
			$sql = sprintf("%s WHERE sis_codigo_empresa.COEM_TX_ID = '%s'", $sql, $company);
		}
		$this->_classification = $this->db->fetchAll($sql);
		return $this->_classification;
		
	}
	
	
	
	
	
	
	
	
}