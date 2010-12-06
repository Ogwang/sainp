<?php
/** Este programa й um software livre; vocк pode redistribui-lo e/ou modifica-lo dentro dos termos da Licenзa Pъblica Geral GNU como publicada pela Fundaзгo do Software Livre (FSF); na versгo 2 da Licenзa, ou (na sua opniгo) qualquer versгo.
*
* @author Theoziran Lima<theoziran@gmail.com>
* http://blog.theoziran.com.br/2009/12/15/consulta-bolsa-de-valores-direto-na-bovespa-com-php/
*
**/
class CotacaoBovespaException extends Exception {

}
class CotacaoBovespa {
    const URL = "http://www.bmfbovespa.com.br/cotacoes2000/formCotacoesMobile.asp";
    
    private $_codigo;
    private $_descricao;
    private $_ibovespa;
    private $_delay;
    private $_data;
    private $_hora;
    private $_oscilacao;
    private $_ultimoValor;
    private $_quatidadeNegoc;
    private $_mercado;
    
    public function __get($var) {
        $var = "_".$var;
        return $this->$var;
    }
    
    public static function find($codigo) {
        $content = self::connect($codigo);
        if($content) {
            $xml = simplexml_load_string($content);
            if(!$xml->NUMERO_DO_ERRO) {
                $obj = new CotacaoBovespa();
                $obj->codigo = $xml->PAPEL["CODIGO"];
                $obj->descricao = $xml->PAPEL["DESCRICAO"];
                $obj->ibovespa = $xml->PAPEL["IBOVESPA"];
                $obj->delay = $xml->PAPEL["DELAY"];
                $obj->data = $xml->PAPEL["DATA"];
                $obj->hora = $xml->PAPEL["HORA"];
                $obj->oscilacao =$xml->PAPEL["OSCILACAO"];
                $obj->ultimoValor = $xml->PAPEL["VALOR_ULTIMO"];
                $obj->quantidadeNegoc = $xml->PAPEL["QUANT_NEG"];
                $obj->mercado = $xml->PAPEL["MERCADO"];
                return $obj;
            }else throw new CotacaoBovespaException("Cуdigo nгo encontrado");
        }else throw new CotacaoBovespaException("Nгo houve resposta do servidor");
    }
    
    private static function connect($codigo) {
        return file_get_contents(self::URL."?codsocemi=".$codigo);
    }
}

//Consulta um ativo da Petrobrбs
CotacaoBovespa::find("petr4");
?>