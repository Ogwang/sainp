<?php
 
class View_Helper_Number {
	
	protected $_decPoint = ',';
	protected $_thousandsSep = '.';
	
	function number($data, $decimals = 0) {
		$formated = (string) number_format($data, $decimals, $this->_decPoint, $this->_thousandsSep);
		
		return $formated;
	}
}