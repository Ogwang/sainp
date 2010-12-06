<?php

Class View_Helper_Date {
	
    private $date_formats = array("mdy"=>"MMMM/dd/yy");

    public function formatDate($date, $formatName, $formatStr = '') {
	    if (!Zend_Date::isDate($date)) {
		    return $date;
		}else{
			$date = new Zend_Date($date);
			if(($formatName != null)&&(in_array($this->date_formats,$formatName))){
				return $date->toString($this->date_formats[$formatName]);
			}else if($formatStr != null){
				return $date->toString($formatStr);
			}
		}
	}
}
