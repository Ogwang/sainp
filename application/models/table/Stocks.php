<?php

Class table_Stocks extends Zend_Db_Table {
	
	protected $_name = 'sis_codigo_ativo';
	protected $_primary = 'COAT_TX_ID';
	
	protected $_referenceMap = array(
		'Companies' => array(
			'columns'       =>  array('COEM_TX_ID'),
			'refTableClass' =>  'Companies',
			'refColumns'    =>  array('COEM_TX_ID')
    )
);
	
	
	function getCompany($stock) {
		$asset = new self;
		$where = "COAT_TX_ID='{$stock}'";
		$code = $asset->fetchRow($where);
		return $code->COEM_TX_ID;
	}
}