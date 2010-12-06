<?php

Class Weboctave_htmldom {
	
	private $_html;
	/*public $tags = array(
						'html' => array('<html>','</html>'),
						'body' => array('<body>','</body>'),
						'head' => array('<head>', '</head>'),
						'pre' => array('<pre>', '</pre>')
						); //define os tags html de abertura e fechamento*/
	public $_ocTags;
	private $_start;
	private $_end;

	function __construct($html) {
		$this->_html = $html;
	}
	
	/*public function setTag($tag) {
		$this->_ocTags = $this->tags[$html];
	}
	
	private function _getTagtext() {
		$string = substr($this->_html, $this->_start, $this->_end - $this->_start);
		return $string;
	}
	
	private function _findStart() {
		$this->_start = strrpos($this->_html, $this->_ocTags[0]);
		return $this->_start;
	}
	
	private function _findEnd() {
		$this->_end = strrpos($this->_html, $this->_ocTags[1]);
		return $this->_end;
	}*/
	
	public function getOctaveResult($iterations) {
		//$start="x0 =", $end="obj0 =";
		
		$this->_htmlSet = $this->_html;
		if ($iterations == 0) {
			$i = 0;
		} else {
			$i = 1;
			$iterations++;
		}
		
		do {
			$start = sprintf('x%s =', $i);
			$end = sprintf('obj%s =', $i);
			
			$resultStart = stripos($this->_htmlSet, $start) + 9;
			$this->_htmlSet = substr($this->_htmlSet, $resultStart, strlen($this->_htmlSet)-$resultStart);
			$resultStart = stripos($this->_htmlSet, $start) + 9;
			$resultObj = stripos($this->_htmlSet, "obj");
			
			$resultEnd = stripos($this->_htmlSet, "<");
			
			//$stringEnd = strrpos($this->_html, "</pre><p ");
			
			$rawxResult = substr($this->_htmlSet, 0, $resultObj);
			$resultX[$i] = explode("\n   ",$rawxResult);
			
			foreach ($resultX[$i] as $key => $value) {
				$resultX[$i][$key] = $value*1;
			}
			
			$objResult[$i] = trim(substr($this->_htmlSet, $resultObj+6, 12))*1;
			
			$i++;
		} while ($i < $iterations);
		
		
		
		return array($resultX, $objResult);
		
	}

}