<?php

Class Database_ROPC {
	
	/* O arquivo ROPC contém as informações sobre o resumo das posições do mercado de opções registradas na BOVESPA/CLBC, segmentadas por séries e vencimentos e totalizadas por posição. */
	
	
	//posicao dos campos no arquivo .TXT
	private $campo01 = array(1,2);
	private $campo02 = array(3,14);
	private $campo03 = array(15,24);
	private $campo04 = array(25,32);
	private $campo05 = array(33,39);
	private $campo06 = array(40,42);
	private $campo07 = array(43,54);
	private $campo08 = array(55,55);
	private $campo09 = array(56,62);
	private $campo10 = array(63,75);
	private $campo11 = array(76,90);
	private $campo12 = array(91,105);
	private $campo13 = array(106,120);
	private $campo14 = array(121,135);
	private $campo15 = array(136,142);
	private $campo16 = array(143,149);
	private $campo17 = array(150,152);
	private $campo18 = array(153,153);
	private $campo19 = array(154,156);
	private $campo20 = array(157,160);
	
	private $tipo_registro;
	private $nome_socied_emiss;
	private $espec_papel;
	private $data_venc_serie_opcoes;
	private $numero_serie;
	private $tipo_mercado;
	private $cod_papel_neg;
	private $ind_moeda;
	private $fator_cotacao;
	private $preco_exercicio;
	private $pos_total_coberta;
	private $pos_total_travada;
	private $pos_total_descobertas;
	private $pos_total;
	private $quant_clientes_titulares;
	private $quant_clientes_lanc;
	private $distribuicao;
	private $estilo;
	private $tipo_ativo;
	private $reserva;
	
	private $path;
	private $file;
	
	
	function __construct($path, $file) {
		
	}
	
	
	private function setVars() {
		$this->path = $path;
		$this->file = $file;
	}
	
	
	private function importFile() {
		
	}
	
	
}
