<?php

Class table_Buy extends Zend_Db_Table {
	
	protected $_name = 'sis_compra';
	protected $_primary = 'COMP_NM_ID';
	
	function init() {
		Zend_Loader::loadClass('table_Portfolio');
	}
	
	
	public function buyStock($stock, $quantity) {
		$portfolio = new table_Portfolio();
		$where = "CAUS_NM_ID={$_SESSION['userid']} and COAT_TX_ID='{$stock}'";
		$port = $portfolio->fetchRow($where);
		
		if ($port != null) {
			$qty = $port->CART_NM_Quantidade;
			$totalQty = $qty + $quantity;
			$data = array('CART_NM_Quantidade' => $totalQty);
			$portfolio->update($data, $where);
		} else {
			$data = array(
							'CAUS_NM_ID' => $_SESSION['userid'],
							'COAT_TX_ID' => $stock,
							'CART_NM_Quantidade' => $quantity);
			$portfolio->insert($data);
		}		
	}
	
	

}