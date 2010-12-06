<?php

Class Protection {
	
	public $stocks;
	
	
	
	function __construct($stocks) {
		$this->stocks = $stocks;
		$this->init();
	}
	
	
	function init() {
		Zend_Loader::loadClass('table_Returns');
	}
	
	
	
	
	
}