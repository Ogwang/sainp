<?php

Class table_Portfolio extends Zend_Db_Table {
	
	protected $_name = 'sis_carteira';
	protected $_primary = 'CART_NM_ID';
	public $stocks;
	
	function init() {
		//$this->stocks();
	}
	
	function weights() {

		$stocks = $this->stocks();
		
		$sql_qties = sprintf("select CART_NM_Quantidade from sis_carteira where CAUS_NM_ID=%s", $_SESSION['userid']);
		$TotalPorftolio = array_sum($this->db->fetchCol($sql_qties));
		
		foreach ($stocks as $stock) {

			$sql_qty = sprintf("select CART_NM_Quantidade from sis_carteira where CAUS_NM_ID=%s and COAT_TX_ID='%s'", $_SESSION['userid'], $stock);
			$stockQty = $this->db->fetchCol($sql_qty);
			$weight[$stock] = round(($stockQty[0] / $TotalPorftolio), 2);		
		}
		
		//$this->weight = $weight;
		
		return $weight;
	}
	
	function stocks() {
		$this->db = Zend_Registry::get('db');
		$this->db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$sql_stocks = sprintf("select COAT_TX_ID from sis_carteira where CAUS_NM_ID=%s", $_SESSION['userid']);
		$this->stocks = $this->db->fetchCol($sql_stocks);
		
		return $this->stocks;
	}
	
	public function getCompany() {
		$compCode = new table_Stocks();
		foreach ($this->stocks as $stock) {
			$code[$stock] = $compCode->getCompany($stock);
		}
		return $code;
	}
	
	
}