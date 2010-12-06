<?php

Class Bovespa_stocks {
    var $_URL  = 'http://finance.yahoo.com/d/quotes.csv?f=sl1d1t1c1ohgv&e=.csv&s=';

    function __construct() {
        // do nothing
    }

    public function get_quotes($stocks_list) {
        if (!$stocks_list) return array();
        
        $this->stocks_list = $this->append_Bovespa($stocks_list);
        
        $symbols = '';
        foreach($this->stocks_list as $symbol) {
            $symbol = rawurldecode($symbol);
            $symbols .= $symbols == '' ? $symbol : '+'.$symbol;
        }
        
        $lines  = $this->get_data($symbols);
        $this->last_quotes = $this->calculate($lines);
        
        return $this->last_quotes;
    }
    
    private function append_Bovespa($stocks_list) {
		foreach ($stocks_list as $key => $value) {
			$stocks_list[$key] = $value . ".SA";
		}
		return $stocks_list;
    }

    private function get_data(&$symbols) {
        $url = $this->_URL.$symbols;
        $fp = fopen($url, "r");
        $result = '';
        
        while(!feof($fp)) {
            $result .= fread($fp, 1024);
        }
        $lines = split("\n", $result, count($this->stocks_list));
        
        return $lines;
    }

    private function calculate(&$lines) {
        $quotes = array();
        
        foreach($lines as $line) {
            $data = $this->parse($line);
            
            if ($data[1] > 0 && $data[4] != 0) {
                $pchange = round((10000*$data[1]/($data[1]-$data[4]))/100-100, 2);
            }
            else $pchange = 0;
            
            if ($data[4] > 0) {
                $pchange = '+'.$pchange;
            }
            else if($data[4] == 0) $pchange = 0;
            
            $name = isset($this->stocks_list[$data[0]]) ? $this->stocks_list[$data[0]] : $data[0];
            $name = $name != '' ? $name : $data[0];
            $name = substr($name,0,-3);
            
            $quotes[] = array(
                    'symbol'     => $data[0],
                    'price_last' => $data[1],
                    'date'       => $data[2],
                    'time'       => $data[3],
                    'dchange'    => $data[4],
                    'price_min'  => $data[5],
                    'price_max'  => $data[6],
                    'pchange'    => $pchange,
                    'name'       => $name,
                    'volume'     => $data[8]
            );
        }
        
        return $quotes;
    }

    private function parse(&$line) {
        $line = ereg_replace('"','',$line);
        
        //symbol, price_last, date, time, dchange, price_min, price_max, not used, volume
        return split(',', $line);
    }

    private function get_last() {
        return $this->last_quotes;
    }
}