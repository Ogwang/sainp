<?php 

// Classe para manipulação das linhas de carteira
class Carteira {
	
	var $usuario;
	var $ativo;
	var $quantidade;
	
	function __construct($usuario, $ativo, $quantidade) {
		$this->usuario=$usuario;
		$this->ativo=$ativo;
		$this->quantidade=$quantidade;
		}
	
	function alterar_ativo ($usuario, $ativo, $quantidade) {
		$this->quantidade = $this->quantidade+$quantidade;
	}
}
	

?>
