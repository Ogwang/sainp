<?php
class Finance {

//
public function CAPM($riskfree,$Beta,$riskmarket)
	{
	$riskstock=$riskfree+($Beta*($riskmarket-$riskfree));
	return $riskstock;	
	}
//
public function WACC($stock,$bond,$returnstock,$returnbond,$ir) {
	$rwacc = (($stock/($stock+$bond))*$returnstock) + (($bond/($stock+$bond))*$returnbond);
	return $rwacc;
}


function MarketReturn($return)
	{
	$rm[$N]=$return;
	$N=$N+1;	
	}

function RiskMarket($tn='10')
	{
	$riskmarket=1;
	for ($N=1;$N<=$tn;$N++)
		{
		$riskmarket = 	$riskmarket * $rm[$N];
		}
	$riskmarket=pow($riskmarket,$tn);
	return $riskmarket;
	}

//Risco da renda fixa

function RiskFreeReturn($return)
	{
	$rf[$N]=$return;
	$N=$N+1;	
	}

function RiskFree($tn=10)
	{
	$riskfree=1;
	for ($N=1;$N<=$tn;$N++)
		{
		$riskfree = $riskfree * $rm[$N];
		}
	$riskfree=pow($riskfree,$tn);
	return $riskfree;
	}

//Risco da ativo

function StockReturn($return)
	{
	$rs[$N]=$return;
	$N=$N+1;	
	}

function RiskStock($tn='10')
	{
	$riskstock=1;
	for ($N=1;$N<=$tn;$N++)
		{
		$riskstock = $riskstock * $rm[$N];
		}
	$riskstock=pow($riskstock,$tn);
	return $riskstock;
	}


//Calcular a rentabilidade real da carteira
function RentabilidadeReal ($ano, $mes, $dia, $user_id)
{

	$compra_array=array();
	$venda_array=array();
	$c=0;
	$v=0;
	$l=1;
	
	
	//compra
	$SQLcompras = sprinft("SELECT * FROM sis_compra WHERE CAUS_NM_ID= %s ORDER BY COAT_TX_ID",$user_id);
	$SQL_compras_rcs=mysql_query($SQLcompras, SAINP) or die('Could not select the database: ' . mysql_error() );
	
	while ($row_compras = mysql_fetch_array($SQL_compras_rcs)) {
		if ($compra_array[$l]['ativo']==$compra_array[($l-1)]['ativo']) {
			$compra_array[$c]['ativo'] = $row_compras[$c]['COAT_TX_ID'];
			$compra_array[$c]['total'] = $compra_array[$c]['total'] + ($row_compras[$l]['COMP_NM_Acoes']*$row_compras[$l]['COMP_VL_Preco'])+$row_compras[$l]['COMP_VL_Emolumentos']+$row_compras[$l]['COMP_VL_Corretagem'];
			$l++;
		} else {
			$l++;
			$c++;
		}
	}
	
	//venda
	$SQLvendas=sprinft("SELECT * FROM sis_venda WHERE CAUS_NM_ID= %s",$user_id);
	$SQL_vendas_rcs=mysql_query($SQLvendas, SAINP) or die('Could not select the database: ' . mysql_error() );
	while ($row_vendas = mysql_fetch_array($SQL_vendas_rcs)) {
		if ($venda_array[$l]['ativo']==$venda_array[($l-1)]['ativo']) {
			$venda_array[$v]['ativo'] = $row_vendas[$l]['COAT_TX_ID'];
			$venda_array[$v]['total'] = ($row_vendas[$l]['VEND_NM_Acoes']*$row_vendas[$l]['VEND_VL_Preco'])+$row_vendas[$l]['VEND_VL_Emolumentos']+$row_vendas[$l]['VEND_VL_Corretagem'];
			$l++;
		} else {
			$l++;
			$v++;
		}
	}

}

}




?>
