<?php

Class table_Sell extends Zend_Db_Table {
	
	protected $_name = 'sis_venda';
	protected $_primary = 'VEND_NM_ID';
	
	function init() {
		Zend_Loader::loadClass('table_Portfolio');
	}
	
	public function sellStock($stock, $quantity) {
		$portfolio = new table_Portfolio();
		$where = "CAUS_NM_ID={$_SESSION['userid']} and COAT_TX_ID='{$stock}'";
		$port = $portfolio->fetchRow($where);
		
		$qty = $port->CART_NM_Quantidade;
		$totalQty = $qty - $quantity;
		
		$data = array(
							'CAUS_NM_ID' => $_SESSION['userid'],
							'COAT_TX_ID' => $stock,
							'CART_NM_Quantidade' => $totalQty);
		if ($totalQty > 0) {
			$portfolio->update($data, $where);
		} else {
			$portfolio->delete($where);
		}
	}
}