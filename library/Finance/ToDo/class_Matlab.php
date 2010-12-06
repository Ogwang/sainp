<?php


class Matlab {
	
	public $Path;
	public $WorkDir;
	public $TempDir;
	public $TempFile;
	
	
	function __construct($user_id) {
		$this->Path = "C:\\MATLAB6p5\\bin\\win32\\matlab.exe";
		$this->WorkDir = "C:\\xampplite\\htdocs\\SAINP\\includes\\matlab\\";
		$this->TempDir = "C:\\xampplite\\htdocs\\SAINP\\includes\\temp\\";
		$this->TempFile = "user_{$user_id}.php";		
	}
	
	
	function CreateMatrix($array) {
		$NumRows = count($array);
		$NumCols = count($array[0]);
		
		$matrix = "[";
		if ($NumCols == 1) {
			for ($l=0;$l<$NumRows;$l++) {
				if ($l!=0) {
					$matrix = $matrix . ";" . $array[$l];
				} else {
					$matrix = $matrix . $array[$l];
				}
			}
		} else {
			for ($c=0;$c<$NumCols;$c++) {
				if ($c!=0) {
					$matrix = $matrix . ";";
				}
				for ($l=0;$l<$NumRows;$l++) {	
					if ($l!=0) {
						$matrix = $matrix . "," . $array[$l][$c];
					} else {
						$matrix = $matrix . $array[$l][$c];
					}
				}
			}
		}
		
		$matrix = $matrix . "]";
		
		return $matrix;
		
		
	}
	

	
	
}
?>