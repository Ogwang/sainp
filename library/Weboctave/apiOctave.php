<?php

require_once('Finance/Matrix.php');
require_once('http.php');
require_once('htmldom.php');

Class Weboctave_apiOctave extends Finance_Matrix {
	
	private $_fileLocation;
	private $_outputFile; //arquivo de saída para execução no Octave
	private $_inputFile; //arquivo com o resultado gerado pelo Octave
	private $_script;
	private $_workFile;
	private $_octaveResult;
	private $_covArray;
	private $_expReturns;
	private $_cov;
	private $_numAssets;
	private $_iterations;
	private $_x0;
	private $_q;
	private $_lb;
	private $_ub;
	private $_A;
	private $_b;
	private $_Ain;
	private $_Alb;
	private $_Aub;
	public $maxReturn;
	public $minReturn;
	public $X;
	public $Dev;
	public $Ret;
	
	
	function __construct($userid, $cov, $expReturns) {
		//$this->_defineFiles($userid);
		$this->_setArrays($cov,$expReturns);
	}
	
	/*private function _defineFiles($userid) {
		//$this->_fileLocation = "C:\\xampplite\\htdocs\\SAINP\\public\\data\\";
		//$this->_inputFile = $userid . "input" . ".txt";
		$this->_outputFile = $userid . "output" . ".m";
		//$this->_workInputFile = $this->_fileLocation . $this->_inputFile;
		$this->_workOutputFile = PUBLIC_DIR . DIRECTORY_SEPARATOR . $this->_outputFile;
	}*/
	
	//Create arrays according to octave standard
	private function _setArrays($cov,$expReturns) {
		$this->_createCovariances($cov);
		$this->_createReturns($expReturns);
		$this->_defineZerosMatrix();
		$this->_defineOnesMatrix();
		$this->_expReturns = $expReturns;
		$this->_cov = $cov;
		
	}
	
	private function _createReturns($expReturns) {
		$i = 0;
		$retMatrix = "[";
		while ($i<$this->_numAssets){ 
			$retMatrix = $retMatrix . $expReturns[$i] . ",";
			$i++;
		}
		$retMatrix = substr($retMatrix,0,-1);
		$retMatrix = $retMatrix . "]";
		
		$this->_Ain = $retMatrix;
	}
	
	private function _createCovariances($cov) {
		$this->_numAssets = count($cov);
		$this->_covArray = "[";
		$i = $j = 0;
		while ($i<$this->_numAssets){ 
			while ($j<$this->_numAssets){
				$this->_covArray = $this->_covArray . $cov[$i][$j] . ",";
				$j++;
			}
			$this->_covArray = substr($this->_covArray,0,-1);
			$this->_covArray = $this->_covArray . ";";
			$j=0;
			$i++;
		}
		$this->_covArray = substr($this->_covArray,0,-1);
		$this->_covArray = $this->_covArray . "]";
	}
	
	private function _createExpReturns($expReturns) {
		$i = 0;
		$retMatrix = "[";
		while ($i<$this->_numAssets){ 
			$retMatrix = $retMatrix . $expReturns[$i] . ",";
			$i++;
		}
		$retMatrix = substr($retMatrix,0,-1);
		$retMatrix = $retMatrix . "]";
		
		$this->_Ain = $retMatrix;
	}
	
	private function _defineZerosMatrix() {
		$i = 0;
		$zeroMatrix = "[";
		while ($i<$this->_numAssets){ 
			$zeroMatrix = $zeroMatrix . 0 . ";";
			$i++;
		}
		$zeroMatrix = substr($zeroMatrix,0,-1);
		$zeroMatrix = $zeroMatrix . "]";
		
		$this->_x0 = $this->_q = $this->_lb = $zeroMatrix;
	}
	
	private function _defineOnesMatrix() {
		$this->_ub = str_replace(0,1,$this->_x0);
		$this->_A = str_replace(";",",",$this->_ub);
		$this->_b = 1;
	}
	
	/*private function _defineVars() {
		$this->_x0 = 0;
		$q = array(1,1);
		$A = array(1,1);
		$b = 1;
		$this->_script = "[x, obj, info, lambda] = qp({$x0},{$cov},{$q},{$A},{$b},0,1,[],[],[])";
		
	}*/
	
	private function _createScript($Alb = null) {
		//$textScript = "file_id = fopen('{$this->_workInputFile}','w')\nfdisp(file_id, '{$this->_script}')\nfclose(file_id)\n";
		$script = null;
		
		if ($Alb == null) {
			$script = "[x0,obj0] = qp({$this->_x0},{$this->_covArray},{$this->_q},{$this->_A},{$this->_b},{$this->_lb},{$this->_ub})";
			$this->_iterations = 0;
		} else {
			
			$this->_iterations = count($Alb);
			//create script with qp functions
			for ($i=1;$i<=$this->_iterations;$i++) {
				$script = $script . "[x{$i},obj{$i}] = qp({$this->_x0},{$this->_covArray},{$this->_q},{$this->_A},{$this->_b},{$this->_lb},{$this->_ub},{$Alb[$i]},{$this->_Ain},{$Alb[$i]})\n";
			}
		}
		
		$this->_script = $script;
		
		return $this->_script;
	}
	
	private function _callWeboctave() {
		$form = array('commands' => $this->_script); 
	    $http_client = new http( HTTP_V11, true ); 
	    $http_client->host = 'hara.mimuw.edu.pl'; 
	    $status = $http_client->post( '/weboctave/web/index.php#menus', $form);
	    $this->_octaveResult = $http_client->get_response_body(); 
	    $http_client->disconnect(); 
	    unset( $http_client ); 
	    
	    $dom = new Weboctave_htmldom($this->_octaveResult);
		return $dom->getOctaveResult($this->_iterations);
	}
	
	/*private function _optResult($result) {

		$count=count($result);
		$n=$k=0;
		foreach ($result as $key => $value) {
			if (strstr($value, "x")) {
				$Xposition[$n] = $key;
				$n++;
			} elseif (strstr($value, "o")) {
				$Oposition[$k] = $key;
				$k++;
			}
		}
		
		$sets = count($Xposition);
		
		for ($i=0;$i<$sets;$i++) {
			$j = $Xposition[$i] + 2;
			$n = 0;
			do {
				$X[$i][$n] = trim($result[$j])*1;
				$n++;
				$j++;
			} while ($result[$j] != null);
		}
		
		
		for ($i=0;$i<$sets;$i++) {
			$j = $Oposition[$i];
			$semiRawObj = explode("=",$result[$j]);
			$obj[$i] = trim($semiRawObj[1])*1;
		}
		
		return array($X,$obj); //resultado final
	}*/
	
	private function _mvPorfolio() {
		$this->_createScript();
		list($x0,$obj0) = $this->_callWeboctave();
		$weights = $x0[0];
		$stdDev = sqrt(2*$obj0[0]);
		
		//set minimum return
		/*$i=0;
		foreach ($w as $key => $value) {
			$weights[$i] = $value;
			$i++;
		}*/
		$ret = new Finance_Matrix($this->_expReturns);
		$returns = $ret->transpose($this->_expReturns);
		$minret = new Finance_Matrix($weights,$returns);
		
		$minReturn = $minret->multiplicationMatrix($weights,$returns);
		$this->minReturn = $minReturn[0];
		
		return array($weights,$stdDev,$this->minReturn);
	}
	
	private function _maxretPortfolio() {
		
		$this->maxReturn = max($this->_expReturns);
		
		foreach ($this->_expReturns as $key => $value) {
			if ($value == $this->maxReturn) {
				$MaxArray[$key] = $this->_expReturns[$key];
			}
		}
		
		//$this->_numAssets = 3;
		
		for ($i=0;$i<$this->_numAssets;$i++) {
			if (array_key_exists($i,$MaxArray)) {
				$variances[$i] = $this->_cov[$i][$i];
			}
		}
		
		$minvar = min($variances);
		
		foreach ($variances as $key => $value) {
			if ($value == $minvar) {
				$MinArray[$key] = $variances[$key];
				$MinKey = $key;
			}
		}
		
		for ($i=0;$i<$this->_numAssets;$i++) {
			if ($i == $MinKey) {
				$weights[$i] = 1;
			}
			else {
				$weights[$i] = 0;
			}
		}
		
		$stdDev = sqrt($variances[$MinKey]);
		
		return array($weights,$stdDev,$this->_expReturns[$MinKey]);
		
	}
	
	private function _iterationReturns($sets) {
		$delta = ($this->maxReturn - $this->minReturn)/($sets - 2);
		for ($i=1;$i<($sets - 1);$i++) {
			$returns[$i] = $this->minReturn + $i*$delta;
		}
		return $returns;
	}
	
	private function _midPorfolio ($portReturns) {
		$this->_createScript($portReturns);
		list($x,$obj) = $this->_callWeboctave();
		
		$count = count($obj);
		for ($i=1;$i<=$count;$i++) {
			$weights = $x[$i];
			$stdDev[$i] = sqrt(2*$obj[$i]);
			
			$ret = new Finance_Matrix($this->_expReturns);
			$returns = $ret->transpose($this->_expReturns);
			$ret = new Finance_Matrix($weights,$returns);
			$retu = $ret->multiplicationMatrix($weights,$returns);
			$Ret[$i] = $retu[0];
		}
		
		return array($x,$stdDev,$Ret);
	}
	
	public function efficientFrontier($sets = 10) {
		list($this->X[0],$this->Dev[0],$this->Ret[0]) = $this->_mvPorfolio(); //MV Point
		
		list($Xf,$Devf,$Retf) = $this->_maxretPortfolio(); //Maximum return point
		
		$portReturns = $this->_iterationReturns($sets); //returns between min and max
		list($X,$Dev,$Ret)= $this->_midPorfolio($portReturns);
		foreach ($Dev as $key => $value) {
			array_push($this->X,$X[$key]);
			array_push($this->Dev,$Dev[$key]);
			array_push($this->Ret,$Ret[$key]);
		}
		
		array_push($this->X,$Xf);
		array_push($this->Dev,$Devf);
		array_push($this->Ret,$Retf);
		
		return array($this->X,$this->Dev,$this->Ret);
	}
	
}