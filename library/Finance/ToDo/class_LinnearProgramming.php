<?php

//Programação Linear
class LinearProgramming {

	private $VarArray = array("W1","W2","W3","W4","W5","W6","W7","W8","W9","W10"); //Pesos
	private $LagrangeArray = array("L1","L2","L3","L4","L5","L6","L7","L8","L9","L10"); //Langrangeanos
	
	public $CovArray;
	public $Weights;
	public $Target_Return;
	
	
	function __construct($CovArray, $Weights, $Target_Return) {
		$this->CovArray = $CovArray;
		$this->Weights = $Weights;
		$this->Target_Return = $Target_Return;
	}
	
	
	function LagrangeObjectiveFunction($CovArray,$weights,$target_return) {
		$original_function = $this->ObjectiveFunction($CovArray);
		$return_constraints = $this->ReturnConstraint($weights,$target_return);
		$weight_constraints = $this->WeightConstraint($weights);

		$TotalAssets = count($weights);
		
		for ($i = 0 ; $i < $TotalAssets ; $i++) {
			$return_constraints[0][$i] = $return_constraints[0][$i].$this->LagrangeArray[0];
			$weight_constraints[0][$i] = $weight_constraints[0][$i].$this->LagrangeArray[1];
			}

		$return_constraints[0][$TotalAssets] = $this->LagrangeArray[0];
		$weight_constraints[0][$TotalAssets] = $this->LagrangeArray[1];

		for ($i = 0 ; $i <= 2 ; $i++) {
			for ($j = 0 ; $j < ($TotalAssets+1) ; $j++) {
				array_push($original_function[$i],$return_constraints[$i][$j]);
			}
		}
		
		for ($i = 0 ; $i <= 2 ; $i++) {
			for ($j = 0 ; $j < ($TotalAssets+1) ; $j++) {
				array_push($original_function[$i],$weight_constraints[$i][$j]);
			}
		}
		
		return $original_function;

		}
	
	
	
	
	
	//Função Objetivo
	function ObjectiveFunction($CovArray) {
		$TotalAssets=count($CovArray);
		
		//Popular Wi^2 + Vari^2
		for ($i =0 ; $i < $TotalAssets ; $i++) {
			//$output = $output . " + " . ($CovArray[$i][$i])^2 . $VarArray[$i];
			//$output = $output . " + " . ($CovArray[$i][$i]^2) . $this->VarArray[$i] . "^2";
			$output_var[$i] = $this->VarArray[$i];
			$output_cte[$i] = $CovArray[$i][$i]^2;
			$output_exp[$i] = 2;
			$k = $i + 1;
		}
		
		//Popular 2WiWjDPij
		for ($i=0 ; $i < ($TotalAssets - 1) ; $i++) {
			for ($j = $i+1 ; $j < $TotalAssets ; $j++) {
				$output_var[$k] = 	$this->VarArray[$i] . $this->VarArray[$j];
				$output_cte[$k] = 2*$CovArray[$i][$j];
				$output_exp[$k] = 1;
				$k = $k + 1;
			}
		}

		$objFunction[0] = $output_var;
		$objFunction[1] = $output_cte;
		$objFunction[2] = $output_exp;
		
		return $objFunction;	
	}
		
		
		//Exibir Equação
		function PrintExpression ($CovArray) {
			$Expression = $this->ObjectiveFunction($CovArray);
			
			$TotalAssets=count($CovArray);
			
			for ($i =0 ; $i <= $TotalAssets ; $i++) {
				$ResultExp = $ResultExp . "(" . $Expression[1][$i] . $Expression[0][$i] . "^" . $Expression[2][$i] . ")" . " + ";
			}
			
			return $ResultExp;
		}
		
		
		//Restrição do retorno
		function ReturnConstraint($weights,$target_return) {
			$TotalAssets = count($weights);
			
			for ($i = 0 ; $i < $TotalAssets ; $i++) {
				$output_var[$i] = $this->VarArray[$i];
				$output_exp[$i] = 1;
				$k = $k + 1;
			}
			$output_var[$k] = 1;
			$output_exp[$k] = 1;
			$output_cte = $weights;
			$output_cte[$k] = $target_return*(-1);
			
			$RetConstraint[0] = $output_var;
			$RetConstraint[1] = $output_cte;
			$RetConstraint[2] = $output_exp;
			
			return $RetConstraint;			
		}
		
		//Restrição para somatório dos pesos iguais a 1
		function WeightConstraint($weights) {
			$TotalAssets = count($weights);
			
			for ($i = 0 ; $i < $TotalAssets ; $i++) {
				$output_var[$i] = $this->VarArray[$i];
				$output_exp[$i] = 1;
				$output_cte[$i] = 1;
				$k = $k + 1;
			}
			
			$output_var[$k] = 1;
			$output_exp[$k] = 1;
			$output_cte[$k] = -1;
			
			$WeightConstraint[0] = $output_var;
			$WeightConstraint[1] = $output_cte;
			$WeightConstraint[2] = $output_exp;
			
			return $WeightConstraint;				
		}
			
		
		function DeriveFunction($ObjFunction) {
			$TotalAssets = count($ObjFunction) - 1;
			for ($N=0 ; $N <= $TotalAssets ; $N++) {
				for ($j = 0 ; $j <= 2 ; $j++) {
					$derive_parts = $this->DeriveVariables($ObjFunction[1][$j],$ObjFunction[0][$j],$ObjFunction[2][$j],$ObjFunction[0][$N]);
					
					$derived_function[$N][0][$j] = $derive_parts[0];
					$derived_function[$N][1][$j] = $derive_parts[1];
					$derived_function[$N][2][$j] = $derive_parts[2];
						
				}
			}
				
			return $derived_function;
		}
			
			
	
		function DeriveVariables($coef,$variable,$exponent,$derive_var) {
			if ($variable <> $derive_var) {
				if (strstr($variable,$derive_var)) {
					$derivedvar = explode($derive_var,$variable);
					$DeriveVariables[0] = trim($derivedvar[0].$derivedvar[1]);
					$DeriveVariables[1] = $coef * $exponent;
					$DeriveVariables[2] = 1;
				} else {
					$DeriveVariables[0] = 0;
					$DeriveVariables[1] = 0;
					$DeriveVariables[2] = 0;
				}
			} else {
				$DeriveVariables[0] = $variable;
				$DeriveVariables[1] = $coef * $exponent;
				$DeriveVariables[2] = $exponent - 1;
			}
				
			if ($DeriveVariables[2] == 0) {
				$DeriveVariables[0] = 0;
			}
				
			return $DeriveVariables;
				
		}
	
	
	
}


?>
