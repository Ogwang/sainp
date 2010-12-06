
<?php //Accounting Indicators functions//

$ano1= date(Y)-1;
$ano2=date(Y)-2;
$ano3=date(Y)-3;
/*
$colname_empresumo = "-1";
if (isset($_GET['empresa'])) {
  $colname_empresumo = (get_magic_quotes_gpc()) ? $_GET['empresa'] : addslashes($_GET['empresa']);
}
mysql_select_db($database_SAINP, $SAINP);
$query_empresumo = sprintf("SELECT sis_codigo_empresa.COEM_TX_ID, sis_codigo_empresa.COEM_Nome_Pregao, sis_codigo_empresa.COEM_Denominacao_Social, sis_classificacao_setorial.CLSE_TX_Setor_Economico, sis_classificacao_setorial.CLSE_TX_Subsetor, sis_classificacao_setorial.CLSE_TX_Segmento FROM sis_codigo_empresa Inner Join sis_classificacao_setorial ON sis_codigo_empresa.CLSE_NM_ID = sis_classificacao_setorial.CLSE_NM_ID WHERE sis_codigo_empresa.COEM_TX_ID =  '%s'", $colname_empresumo);
$empresumo = mysql_query($query_empresumo, $SAINP) or die(mysql_error());
$row_empresumo = mysql_fetch_assoc($empresumo);
$totalRows_empresumo = mysql_num_rows($empresumo);
*/
// Cotação de Mercado
	function cotacaomercado($varano, $varmes, $vardia, $varempresa){
	
	mysql_select_db($database_SAINP, $SAINP);
	$query = sprintf("SELECT bov_historico.PREULT FROM bov_historico WHERE bov_historico.ANO =  '%s' AND bov_historico.MES =  '%s' AND bov_historico.DIA =  '%s' AND bov_historico.CODNEG =  '%s'", $varano, $varmes, $vardia, $varempresa);
	
	$indicadores = mysql_query($query, $SAINP) or die(mysql_error());
	$row_indicadores = mysql_fetch_assoc($indicadores);

	$var_cotacao=$row_indicadores['PREULT'];
	
	return $var_cotacao;
	}



// Definição de Variáveis
	// Índices de Liquidez
		// Índice de Liquidez Geral [ILG =(AC+ELP)/(PC+ELP)] 
		function ILG ($varano, $varempresa){
			
			mysql_select_db($database_SAINP, $SAINP);
			$query = sprintf("SELECT * FROM bal_balanco_patrimonial WHERE COEM_TX_ID =  '%s' AND BAPA_NM_Ano =  '%s'", $varempresa, $varano);
			$indicadores = mysql_query($query, $SAINP) or die(mysql_error());
			$row_indicadores = mysql_fetch_assoc($indicadores);
			
			$var_ilg = ($row_indicadores['BAPA_VL_Ativo_Circulante'] + $row_indicadores['BAPA_VL_ExigivelLP'])/($row_indicadores['BAPA_VL_Passivo_Circulante'] + $row_indicadores['BAPA_VL_ExigivelLP']);

			return $var_ilg;
		}

			
	// Índices de Alavancagem		
		// Índices de Endividamento Geral [IEG =(AT-PL)/AT]
		function IEG($varano, $varempresa){
			
			mysql_select_db($database_SAINP, $SAINP);
			$query = sprintf("SELECT * FROM bal_balanco_patrimonial WHERE COEM_TX_ID =  '%s' AND BAPA_NM_Ano =  '%s'", $varempresa, $varano);
			$indicadores = mysql_query($query, $SAINP) or die(mysql_error());
			$row_indicadores = mysql_fetch_assoc($indicadores);
		
			$var_ieg = ($row_indicadores['BAPA_VL_Ativo_Circulante']+$row_indicadores['BAPA_VL_Ativo_Nao_Circulante']-$row_indicadores['BAPA_VL_Patrimonio_Liquido'])/($row_indicadores['BAPA_VL_Ativo_Circulante']+$row_indicadores['BAPA_VL_Ativo_Nao_Circulante']);
			
			return $var_ieg;
		}
		
		
	// Índices de Atividade	
		// Giro de Estoque (GE = CMV/Estoque)
		function GE($varano, $varempresa){
		
		mysql_select_db($database_SAINP, $SAINP);
		$query = sprintf("SELECT bal_dre.DREE_VL_Custo_Bens_Vendidos, bal_balanco_patrimonial.BAPA_VL_Estoques, bal_dre.COEM_TX_ID
FROM bal_dre Inner Join bal_balanco_patrimonial ON bal_dre.COEM_TX_ID = bal_balanco_patrimonial.COEM_TX_ID WHERE bal_dre.COEM_TX_ID =  '%s' AND bal_dre.DREE_NM_Ano =  '%s' AND bal_balanco_patrimonial.BAPA_NM_Ano =  '%s'", $varempresa, $varano, $varano);

		$indicadores = mysql_query($query, $SAINP) or die(mysql_error());
		$row_indicadores = mysql_fetch_assoc($indicadores);
		
		$var_GE = $row_indicadores['DREE_VL_Custo_Bens_Vendidos']/$row_indicaddores['BAPA_VL_Estoques'];
		
		return $var_GE;
		}
	
	
	
	// Índices de Rentabilidade
		// Índice Lucro por Ação [LPA = (Lucro Líquido)/(Número de Ações)]
		function lpa($varano, $varempresa){
		
		mysql_select_db($database_SAINP, $SAINP);
		$query = sprintf("SELECT bal_capital_social.COEM_TX_ID, bal_capital_social.CASO_NM_Qtde_total_acoes, bal_fluxo_caixa.FLCA_VL_Lucro_Liquido FROM bal_capital_social Inner Join bal_fluxo_caixa ON bal_capital_social.COEM_TX_ID = bal_fluxo_caixa.COEM_TX_ID WHERE bal_capital_social.COEM_TX_ID =  '%s' AND bal_fluxo_caixa.FLCA_NM_Ano =  '%s'", $varempresa, $varano);
		
		$indicadores = mysql_query($query, $SAINP) or die(mysql_error());
		$row_indicadores = mysql_fetch_assoc($indicadores);
		
		$var_lpa = $row_indicadores['FLCA_VL_Lucro_Liquido']/$row_indicadores['CASO_NM_Qtde_total_acoes'];

		return $var_lpa;		
		}
		
		
		// Índice Preço por Lucro [P/L=(Cotação de Mercado)/(LPA)]
			function precolucro($varano, $varmes, $vardia, $varempresa){
			
			$var_precolucro = cotacaomercado($varano, $varmes, $vardia, $varempresa)/lpa($varano, $varempresa);
			
			return $var_precolucro;
			}

		// Índice Preço/Valor Patrimonial da Ação [P/VPA=(Cotação de Mercado)/(Valor Patrimonial da Ação)]
			function precoVPA ($varano, $varmes, $vardia, $varempresa){

			mysql_select_db($database_SAINP, $SAINP);
			$query = sprintf("SELECT bal_balanco_patrimonial.BAPA_VL_Patrimonio_Liquido, bal_capital_social.CASO_NM_Qtde_total_acoes, bal_balanco_patrimonial.COEM_TX_ID FROM bal_balanco_patrimonial Inner Join bal_capital_social ON bal_balanco_patrimonial.COEM_TX_ID = bal_capital_social.COEM_TX_ID WHERE bal_balanco_patrimonial.COEM_TX_ID =  '%s' AND bal_balanco_patrimonial.BAPA_NM_Ano =  '%s'", $varempresa, $varano);
		
			$indicadores = mysql_query($query, $SAINP) or die(mysql_error());
			$row_indicadores = mysql_fetch_assoc($indicadores);

			$var_precoVPA = cotacaomercado($varano, $varmes, $vardia, $varempresa)/($row_indicadores['BAPA_VL_Patrimonio_Liquido']/$row_indicadores['CASO_NM_Qtde_total_acoes']);
			
			return $var_precoVPA;
			}



// Índices Financeiros

	// Rentabilidade












?>