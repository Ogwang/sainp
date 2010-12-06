<?php

require_once('Finance/Matrix.php');

Class Finance_Optimize extends Finance_Matrix {
	
	private $_varArray = array("w1","w2","w3","w4","w5","w6","w7","w8","w9","w10"); //Pesos
	private $_lagrangeArray = array("L1","L2","L3","L4","L5","L6","L7","L8","L9","L10"); //Langrangeanos
	
	private $_covArray;
	private $_weights;
	private $_expReturns;
	private $_targetReturn;
	private $_numAssets;
	private $_loop;
	
	private $_objFunction;
	private $_derivedFunction;
	private $_returnContraints;
	private $_weightContraints;
	private $_lagrFunction;
	
	
	//Variáveis para teste
	private $var1;
	private $var2;
	private $var3;
	
	
	function __construct($weights, $returns, $targetReturn) {
		$this->_weights = $weights;
		$this->_covArray = $this->covarianceMatrix($returns);
		$this->_targetReturn = $targetReturn;
		$this->_numAssets = count($this->_weights);
		$this->_loop = 4*$this->_numAssets + 1;
		$this->_expReturns = $this->_expectedReturn($returns);
		
		$this->_objectiveFunction(); //cria a função objetivo
		$this->_returnConstraint();
		$this->_weightConstraint();
		
		$this->_lagrangeFunction();
		
		$this->_deriveFunction(); //deriva a função objetivo
		
	}
	
	private function _expectedReturns($returns) {
		for ($i = 0 ; $i < $this->_numAssets ; $i++) {
			$expReturns[$i] = $this->meanMatrix($returns[$i]);
		}
		return $expReturns;
	}
	
	
	//Função objetivo na forma matricial
	private function _objectiveFunction() {
		
		//Popular Wi^2 + Vari^2
		for ($i =0 ; $i < $this->_numAssets ; $i++) {
			$output_var[$i] = $this->_varArray[$i];
			$output_cte[$i] = $this->_covArray[$i][$i] * $this->_covArray[$i][$i];
			$output_exp[$i] = 2;
			$k = $i + 1;
		}
		
		//Popular 2WiWjDPij
		for ($i=0 ; $i < ($this->_numAssets - 1) ; $i++) {
			for ($j = $i+1 ; $j < $this->_numAssets ; $j++) {
				$output_var[$k] = 	$this->_varArray[$i] . $this->_varArray[$j];
				$output_cte[$k] = 2*$this->_covArray[$i][$j];
				$output_exp[$k] = 1;
				$k = $k + 1;
			}
		}
		
		$this->_objFunction[0] = $output_cte;
		$this->_objFunction[1] = $output_var;
		$this->_objFunction[2] = $output_exp;
		
	}
	

			
	
	
	//Restrição do retorno
	private function _returnConstraint() {
		//$TotalAssets = count($weights);
		for ($i = 0 ; $i < $this->_numAssets ; $i++) {
			$output_var[$i] = $this->_varArray[$i];
			$output_exp[$i] = 1;
			$k = $k + 1;
		}
		$output_var[$k] = 1;
		$output_exp[$k] = 1;
		$output_cte = $this->_expReturns;
		$output_cte[$k] = $this->_targetReturn*(-1);
		
		$this->_returnContraints[0] = $output_cte;
		$this->_returnContraints[1] = $output_var;
		$this->_returnContraints[2] = $output_exp;			
	}
	
	//Restrição para somatório dos pesos iguais a 1
	private function _weightConstraint() {
		//$TotalAssets = count($weights);
		
		for ($i = 0 ; $i < $this->_numAssets  ; $i++) {
			$output_var[$i] = $this->_varArray[$i];
			$output_exp[$i] = 1;
			$output_cte[$i] = 1;
			$k = $k + 1;
		}
		
		$output_var[$k] = 1;
		$output_exp[$k] = 1;
		$output_cte[$k] = -1;
		
		$this->_weightContraints[0] = $output_cte;
		$this->_weightContraints[1] = $output_var;
		$this->_weightContraints[2] = $output_exp;				
	}

	public function _lagrangeFunction() {
		
		$this->_lagrFunction = $this->_objFunction;
	
	
		for ($i = 0 ; $i < $this->_numAssets ; $i++) {
			$this->_returnContraints[1][$i] = $this->_returnContraints[1][$i].$this->_lagrangeArray[0];
			$this->_weightContraints[1][$i] = $this->_weightContraints[1][$i].$this->_lagrangeArray[1];
			}

		$this->_returnContraints[1][$this->_numAssets] = $this->_lagrangeArray[0];
		$this->_weightContraints[1][$this->_numAssets] = $this->_lagrangeArray[1];

		for ($i = 0 ; $i <= 2 ; $i++) {
			for ($j = 0 ; $j < ($this->_numAssets+1) ; $j++) {
				array_push($this->_lagrFunction[$i],$this->_returnContraints[$i][$j]);
			}
		}
		
		for ($i = 0 ; $i <= 2 ; $i++) {
			for ($j = 0 ; $j < ($this->_numAssets+1) ; $j++) {
				array_push($this->_lagrFunction[$i],$this->_weightContraints[$i][$j]);
			}
		}

	}
	
	
	private function _deriveFunction() {
		//$TotalAssets = count($ObjFunction) - 1;
		//$loop = 4*$this->_numAssets + 1;
		
		for ($N=0 ; $N < $this->_numAssets ; $N++) {
			for ($j = 0 ; $j < $this->_loop ; $j++) {
				$derive_parts = $this->_deriveVariables($this->_lagrFunction[0][$j],$this->_lagrFunction[1][$j],$this->_lagrFunction[2][$j],$this->_lagrFunction[1][$N]);
				
				$this->_derivedFunction[$N][0][$j] = $derive_parts[0];
				$this->_derivedFunction[$N][1][$j] = $derive_parts[1];
				$this->_derivedFunction[$N][2][$j] = $derive_parts[2];
			}
		}
		
		$this->_teste();
	}
	
	
	private function _deriveVariables($coef,$variable,$exponent,$derive_var) {
	if ($variable <> $derive_var) {
		if (strstr($variable,$derive_var)) {
			$derivedvar = explode($derive_var,$variable);
			$DeriveVariables[0] = $coef * $exponent;
			$DeriveVariables[1] = trim($derivedvar[0].$derivedvar[1]);
			$DeriveVariables[2] = 1;
		} else {
			$DeriveVariables[0] = 0;
			$DeriveVariables[1] = 0;
			$DeriveVariables[2] = 0;
		}
	} else {
		$DeriveVariables[0] = $coef * $exponent;
		$DeriveVariables[1] = $variable;
		$DeriveVariables[2] = $exponent - 1;
	}
		
	if ($DeriveVariables[2] == 0) {
		$DeriveVariables[1] = 0;
	}
	return $DeriveVariables;
}
	
	
	public function optimizationFunction() {
		$opt1 = $this->_derivedFunction[0];
		$opt2 = $this->_derivedFunction[1];
		
		$optFunction[0][0] = $opt2[0][2] + $opt1[0][0];
		$optFunction[1][0] = "w1";
		$optFunction[2][0] = $opt1[2][3];
		
		$optFunction[0][1] = $opt2[0][1] + $opt1[0][2];
		$optFunction[1][1] = "w2";
		$optFunction[2][1] = $opt1[2][1];
		
		$optFunction[0][2] = $opt2[0][4] + $opt1[0][3];
		$optFunction[1][2] = "L1";
		$optFunction[2][2] = $opt1[2][3];
		
		$optFunction[0][3] = $opt2[0][7] + $opt1[0][6];
		$optFunction[1][3] = "L2";
		$optFunction[2][3] = $opt1[2][7];
		
		$optFunction[0][4] = $opt2[0][0] + $opt2[0][3] +$opt2[0][5] +$opt2[0][6] + $opt2[0][8] + $opt1[0][1] + $opt1[0][4] + $opt1[0][5] + $opt1[0][7] + $opt1[0][8];
		$optFunction[1][4] = 0;
		$optFunction[2][4] = 0;
		
		
		
		
		return $optFunction;
		
		
	}
	
			
			
	private function _teste() {
		$this->var1 = $this->_derivedFunction[0];
		$this->var2 = $this->_derivedFunction[1];
	}
	
	
}