<?php 

//Operações com matrizes
class Finance_Matrix {

	private $_Array1 = array();
	private $_Array2 = array();
	private $_RowsNum1;	
	private $_ColsNum1;
	private $_RowsNum2;	
	private $_ColsNum2;	
	

	function __construct($array1, $array2 = null) {
		$this->_Array1 = $array1;
		$this->_Array2 = $array2;
		$this->_ColsNum1 = $this->_getColNum($this->_Array1);
		$this->_RowsNum1 = $this->_getRowNum($this->_Array1);
		$this->_ColsNum2 = $this->_getColNum($this->_Array2);
		$this->_RowsNum2 = $this->_getRowNum($this->_Array2);
	}
		
	//Retorna o número de colunas da matriz
	private function _getColNum($array) {
		//$array = $this->_getArray($i);
		if (!isset($array[0][0])) {
			$ColNum = count($array);
		} else {
			$ColNum = count($array[0]);
		}
		return $ColNum;
	}
		
	//Retorna o número de colunas da matriz
	private function _getRowNum($array) {
		//$array = $this->_getArray($i);
		$RowNum = 0;
		if (!isset($array[0][0])) {
			$RowNum = 1;
		} else {
			while (isset($array[$RowNum][0])) {
				$RowNum = $RowNum + 1;
			}
		}
		
		return $RowNum;
	}
		
	//Valida operações de soma, subtração
	private function _validateSum($array1, $array2) {
		$RowsNum1 = $this->_getRowNum($array1);
		$RowsNum2 = $this->_getRowNum($array2);
		$ColsNum1 = $this->_getColNum($array1);
		$ColsNum2 = $this->_getColNum($array2);
		
		if ($RowsNum1 == $RowsNum2) {
			if ($ColsNum1 == $ColsNum2) {
				$SumValid = True;		
			} else {
				$SumValid = False;
			}
		} else {
			$SumValid = False;
		}
		
		return $SumValid;
	}
		
	//Valida operações de multiplicação
	private function _validateMultip($array1, $array2) {
		$ColsNum1 = $this->_getColNum($array1);
		$RowsNum2 = $this->_getRowNum($array2);
		
		if ( $ColsNum1 == $RowsNum2) {
			$MultipValid = True;	
		} else {
			$MultipValid = False;
		}
		
		return $MultipValid;
	}
		
	//Soma duas matrizes
	function sumMatrix($array1, $array2) {
		$RowsNum1 = $this->_getRowNum($array1);
		$ColsNum2 = $this->_getColNum($array2);
		$Valid = $this->_validateSum($array1, $array2);
		
		if ($Valid) {
			if ($ColsNum1 == 1) {
				for ($j=0;$j<$RowsNum1;$j++) {
					$ArraySum[$j]=$array1[$j]+$array2[$j];
				}
			} else {
				for ($i=0;$i<$RowsNum1;$i++) {
					for ($j=0;$j<$ColsNum1;$j++) {
						$ArraySum[$i][$j]=$array1[$i][$j]+$array2[$i][$j];
					}
				}
			}
			
			return $ArraySum;
		} else {
			return null;
		}
	}
		
	//Subtrai duas matrizes
	function leftMatrix($array1, $array2) {
		$RowsNum1 = $this->_getRowNum($array1);
		$ColsNum2 = $this->_getColNum($array2);
		$Valid = $this->_validateSum($array1, $array2);
		
		if ($Valid) {
			if ($ColsNum1 == 1) {
				for ($j=0;$j<$RowsNum1;$j++) {
					$ArrayLeft[$j]=$array1[$j] - $array2[$j];
				}
			} else {
				for ($i=0;$i<$RowsNum1;$i++) {
					for ($j=0;$j<$ColsNum1;$j++) {
						$ArrayLeft[$i][$j]=$array1[$i][$j] - $array2[$i][$j];
					}
				}
			}
			
			return $ArrayLeft;
		} else {
			return null;
		}
	}
	
	function multiplicationMatrix($array1, $array2) {
		$ColsNum1 = $this->_getColNum($array1);
		$ColsNum2 = $this->_getColNum($array2);
		$RowsNum1 = $this->_getRowNum($array1);
		$RowsNum2 = $this->_getRowNum($array2);
		$Valid = $this->_validateMultip($array1, $array2);
		
		if (($Valid) && ($RowsNum1 == 1)) {
			for ($j = 0 ; $j < $ColsNum2 ; $j++) {
				$ArrayMultipli[$j]= 0;
				for ($m = 0 ; $m < $RowsNum2 ; $m++) {
						$ArrayMultipli[$j] += $array1[$m]*$array2[$m][$j];
					}
				}
		} elseif (($Valid) && ($RowsNum1 > 1)) {
			for ($i = 0 ; $i < $ColsNum2 ; $i++) {
				for ($j = 0 ; $j < $ColsNum2 ; $j++) {
				$ArrayMultipli[$i][$j]= 0;
					for ($m = 0 ; $m < $RowsNum2 ; $m++) { 
						$ArrayMultipli[$i][$j] += $array1[$i][$m]*$array2[$m][$j];
					}
				}
			}
		} else {
			$ArrayMultipli = null;
		}

		return $ArrayMultipli;
	}
		
	function transpose($array) {
		$Rows = $this->_getRowNum($array);
		$Cols = $this->_getColNum($array);

		if ($Rows == 1) {
			for ($i=0;$i<$Cols;$i++) {
				$Transp[$i][0]=$array[$i];
			}				
		} else {
			for ($i=0 ; $i < $Rows ; $i++) {
				for ($j=0 ; $j < $Cols ; $j++) {
					$Transp[$j][$i]=$ArrayM[$i][$j];
				}
			}
		}
		return $Transp;
	}

	//Média de uma Matriz Mx0
	function meanMatrix($array) {
		//$array = $this->_getArray($i);
		$Cols = $this->_getColNum($array);
		//$CountArray = count($array);
		$SumArray = array_sum($array);
		$Mean = $SumArray/$Cols;
		return $Mean;
	}
	
	//Calcular pesos da carteira
	function weight($array) {
		//$array = $this->_getArray($i);
		$total = array_sum($array);
		$lines = count($array);
		
		for ($i=0 ; $i < $lines ; $i++) {
			$weight[$i]	= $array[$i]/$total;			
		}
		return $weight;
	}
	
	//Calcular média da carteira
	function meanPortfolio($array1, $array2) {
		//$array1 = weight;
		//$array2 = return array;
		//$lines = count($this->_Array1);
		$ColsNum1 = $this->_getColNum($array1);
		
		for ($i=0 ; $i < $ColsNum1 ; $i++) {
			$mean += $array1[$i]*$array2[$i];			
		}
		return $mean;
	}
		
	//Covariância entre dois vetores
	function covariance($array1, $array2) {
		$ColsNum1 = $this->_getColNum($array1);
		$ColsNum2 = $this->_getColNum($array2);
		$Mean1 = $this->meanMatrix($array1);
		$Mean2 = $this->meanMatrix($array2);
		$Cov = 0;
		
		if ($ColsNum1 == $ColsNum2) {
			for ($i=0;$i<$ColsNum1;$i++) {
				$Cov = $Cov + ($array1[$i]-$Mean1)*($array2[$i]-$Mean2);
			}
			$Cov = $Cov/$ColsNum1;
			return $Cov;
		} else {
			return null;
		}
	}

	//Cria Matriz de Covariâncias
	function covarianceMatrix($array) {
		$NumRows = $this->_getRowNum($array);
		for ($i = 0 ; $i < $NumRows ; $i++) {
			for ($j = 0 ; $j < $NumRows ; $j++) {
				$CovMatrix[$i][$j] = $this->covariance($array[$i],$array[$j]);
			}
		}
		return $CovMatrix;		
	}
	
	//Variância do Portfolio
	function variancePortfolio ($weights, $returns) {
		$TranspWeight = $this->transpose($weights);
		$ArrayCov = $this->covarianceMatrix($returns);
		
		$object1 = new self($weights, $ArrayCov);
		$Mult = $object1->multiplicationMatrix($weights,$ArrayCov);
		
		$object2 = new self($Mult, $TranspWeight);
		$MatrixCovPort = $object2->multiplicationMatrix($Mult,$TranspWeight);
		
		$CovPort = $MatrixCovPort[0];
		
		return $CovPort;
	}
	
	public function correlation($array1, $array2) {
		$cov = $this->covariance($array1, $array2);
		$dev1 = sqrt($this->covariance($array1, $array1));
		$dev2 = sqrt($this->covariance($array2, $array2));
		$corr = $cov / ($dev1 * $dev2);
		return $corr;
	}
	
	public function correlationMatrix($array) {
		$NumRows = $this->_getRowNum($array);
		for ($i = 0 ; $i < $NumRows ; $i++) {
			for ($j = 0 ; $j < $NumRows ; $j++) {
				if ($i == $j) {
					$CorrMatrix[$i][$j] = 1;
				} else {
					$CorrMatrix[$i][$j] = $this->correlation($array[$i],$array[$j]);
				}
			}
		}
		return $CorrMatrix;		
	}
	
}



?>