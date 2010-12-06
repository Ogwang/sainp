<?php

Class Bovespa_readHistoric extends Zend_Db_Table {
    private $_array_positions;
    private $_n;
    private $_filaname;
    private $_fd;
    private $_array_result;
    private $_line;
    
    public function __construct() {
        $this->db = Zend_Registry::get('db');
        $this->_deleteTemp();
        $this->_updateTemp();
        $this->_updateFinal();
    }
    
    private function _deleteTemp() {
        $sql = "DELETE FROM bov_historico_temp";
        $this->db->fetchAll($sql);
    }
        
    private function _updateTemp() {

        $_array_positions = array(
                                "TIPREG" => 2,
                                "PREGANO" => 6,
                                "PREGMES" => 8,
                                "PREGDIA" => 10,
                                "CODBDI" => 12,
                                "CODNEG" => 24,
                                "TPMERC" => 27,
                                "NOMRES" => 39,
                                "ESPECI" => 49,
                                "PRAZOT" => 52,
                                "MODREF" => 56,
                                "PREABE" => 69,
                                "PREMAX" => 82,
                                "PREMIN" => 95,
                                "PREMED" => 108,
                                "PREULT" => 121,
                                "PREOFC" => 134,
                                "PREOFV" => 147,
                                "TOTNEG" => 152,
                                "QUATOT" => 170,
                                "VOLTOT" => 188,
                                "PREEXE" => 201,
                                "INDOPC" => 202,
                                "DATVENANO" => 206,
                                "DATVENMES" => 208,
                                "DATVENDIA" => 210,
                                "FATCOT" => 217,
                                "PTOEXE" => 230,
                                "CODISI" => 242,
                                "DISMES" => 245);

    $_n = 0;

    $_filename = "./public/data/Bovespa/quotations/COTAHIST.txt";

    $_fd = fopen($_filename, "r");

    if ($_fd) {
        while (!feof($_fd)) {
            $_line = fgets($_fd);
            $start_pos = 0;
            foreach ($_array_positions as $i => $value) {
                $_array_result[$_n][$i] = trim(substr($_line, $start_pos, $value - $start_pos));
                $start_pos = $value;
            }
            
            if ($_array_result[$_n]["TIPREG"] == "01") {
            
                $sql = sprintf("INSERT INTO bov_historico_temp 
                                    (TIPREG , PREGANO , PREGMES , PREGDIA , CODBDI , CODNEG , TPMERC , NOMRES , ESPECI , PRAZOT , MODREF , PREABE , PREMAX , PREMIN , PREMED , PREULT , PREOFC , PREOFV , TOTNEG , QUATOT , VOLTOT , PREEXE , INDOPC , DATVENANO , DATVENMES , DATVENDIA , FATCOT , PTOEXE , CODISI , DISMES)
                                     VALUES 
                                     ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                                        
                                        $_array_result[$_n]["TIPREG"], 
                                        $_array_result[$_n]["PREGANO"], 
                                        $_array_result[$_n]["PREGMES"], 
                                        $_array_result[$_n]["PREGDIA"], 
                                        $_array_result[$_n]["CODBDI"], 
                                        $_array_result[$_n]["CODNEG"], 
                                        $_array_result[$_n]["TPMERC"], 
                                        $_array_result[$_n]["NOMRES"], 
                                        $_array_result[$_n]["ESPECI"], 
                                        $_array_result[$_n]["PRAZOT"], 
                                        $_array_result[$_n]["MODREF"], 
                                        $_array_result[$_n]["PREABE"], 
                                        $_array_result[$_n]["PREMAX"], 
                                        $_array_result[$_n]["PREMIN"], 
                                        $_array_result[$_n]["PREMED"], 
                                        $_array_result[$_n]["PREULT"], 
                                        $_array_result[$_n]["PREOFC"], 
                                        $_array_result[$_n]["PREOFV"], 
                                        $_array_result[$_n]["TOTNEG"], 
                                        $_array_result[$_n]["QUATOT"], 
                                        $_array_result[$_n]["VOLTOT"], 
                                        $_array_result[$_n]["PREEXE"], 
                                        $_array_result[$_n]["INDOPC"], 
                                        $_array_result[$_n]["DATVENANO"], 
                                        $_array_result[$_n]["DATVENMES"], 
                                        $_array_result[$_n]["DATVENDIA"], 
                                        $_array_result[$_n]["FATCOT"], 
                                        $_array_result[$_n]["PTOEXE"], 
                                        $_array_result[$_n]["CODISI"], 
                                        $_array_result[$_n]["DISMES"]
                                        );
                $this->db->fetchAll($sql);
            }
            $_n++;
        }
    }
    fclose($_fd);
    }
    
    private function _updateFinal() {
        $sql_days = "SELECT DISTINCT pregdia FROM bov_historico_temp";
        $days = $this->db->fetchCol($sql_days);
        $lastDay = max($days);
        
        $sql = sprintf("INSERT INTO bov_historico SELECT * FROM bov_historico_temp WHERE PREGDIA = '%s'", $lastDay);
        $this->db->fetchAll($sql);
    }
    
}