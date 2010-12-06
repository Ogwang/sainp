<?php

Class View_Helper_Table extends Zend_View_Helper_Abstract {
	
	protected $_style = array();
	protected $_data = array();
	protected $_header = array();
	protected $_foot = array();
	protected $_headerSytle;
	protected $_row1Sytle;
	protected $_row2Sytle;
	protected $_footSytle;
	
	
	public function table() {
		$header = $this->Theader();
		$body = $this->Tbody();
		$footer = $this->Tfooter();
		return $header . $body . $footer;
	}
	
	
	public function Theader($header, $style) {
		$this->htmlHeader = "<thead style='{$style}'><tr>";
		foreach ($header as $data) {
			$this->htmlHeader .= "<th>{$data}</th>";
		}
		$this->htmlHeader .= "</thead>";
		return $this->htmlHeader;
	}
	
	public function Tbody($body, $styles) {
		$style = $this->_styles($body, $styles);
		foreach ($body as $row) {
			$this->htmlRow .= "<tr style='{$style}'>";
			foreach ($row as $col) {
				$this->htmlRow .= "<td>{$col}</td>";
			}
			$this->htmlRow .= "</tr>";
		}
		return $this->htmlRow;
		
	}
	
	public function Tfooter($footer, $style) {
		$this->htmlFooter = "<tfoot style='{$style}'><tr>";
		foreach ($footer as $data) {
			$this->htmlFooter .= "<td>{$data}</td>";
		}
		$this->htmlFooter .= "</tr></tfoot>";
		return $this->htmlFooter;
	}
	
	private function _styles($data, $styles) {
		$countData = count($data);
		$countStyles = count($styles);
		for ($i=0;$i<$countData;$i++) {
			$style[$i] = current($styles);
			next($styles);
		}
		
		return $style;
		
	}
	
}