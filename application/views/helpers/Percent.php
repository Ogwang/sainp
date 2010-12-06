<?php
 
/**
 * Formats a number to pretty percent notation.
 * Accepts an array of numbers to calculate on-the-fly
 * 
 * @author Hector Virgen
 */
class View_Helper_Percent
{
    /**
     * Flag to enable/disable trimming of trailing zeroes and dot
     *
     * @var boolean
     */
    protected $_trimTrail = true;
    
    /**
     * Percent symbol to append to formatted number
     *
     * @var string
     */
    protected $_symbol = '%';
    
    /**
     * Character to use as thousands separator
     *
     * @var string
     */
    protected $_thousandsSep = '.';
    
    /**
     * Character to use as decimal point
     *
     * @var string
     */
    protected $_decPoint = ',';
    
    /**
     * Formats a number or array of numbers to percent notation
     *
     * @param numeric|array $data - Percent value or array of numbers to calculate percent
     * @param int $digits - Number of digits to display after the dot
     * @return string - Formatted percent
     */
    public function percent($data = null, $digits = 0)
    {
        // Return this if no parameters were passed
        if (null === $data) {
            return $this;
        }
        
        // Determine percent value
        if (is_array($data)) {
            list($val, $maxval) = $data;
            $percent = $this->calcPercent($val, $maxval);
        } else if (is_numeric($data)) {
            $percent = (float) $data;
        } else {
            throw new Zend_View_Exception("Data must be a numeric or array of value, maxvalue.");
        }
        
        $percent = $percent * 100;
        
        $percent = (string) number_format($percent, (int) $digits, $this->_decPoint, $this->_thousandsSep);
        
        // Remove trailing zeroes and dot
        if ($this->_trimTrail AND $digits > 0) {
            $percent = rtrim($percent, '0');
            $percent = rtrim($percent, $this->_decPoint);
        }
        
        // Append percent symbol
        $percent .= $this->_symbol;
        
        return $percent;
    }
    
    /**
     * Calculates the percent based on value and maxvalue.
     *
     * @param numeric $val - Current value
     * @param numeric $maxval - Total value
     * @return float - percent of total value
     */
    public function calcPercent($val, $maxval)
    {
        $maxval = (float) $maxval;
        if (0 == $maxval) {
            throw new Zend_View_Exception("Maxval must be a non-zero value.");
        }
        
        return (float) $val / $maxval * 100;
    }
    
    /**
     * Enables or disabled the trimming of trailing zeroes and dots
     *
     * @param boolean $flag - true or false
     * @return $this - Fluent interface
     */
    public function setTrimTrail($flag)
    {
        $this->_trimTrail = (bool) $flag;
        
        return $this;
    }
    
    /**
     * Sets the symbol to append to the percent value
     *
     * @param string $symbol - Percent symbol
     * @return $this - Fluent interface
     */
    public function setSymbol($symbol)
    {
        $this->_symbol = (string) $symbol;
        
        return $this;
    }
    
    /**
     * Sets the decimal point character
     *
     * @param string $char - Decimal point character
     * @return $this- Fluent interface
     */
    public function setDecPoint($char)
    {
        $this->_decPoint = (string) $char;
        if (empty($this->_decPoint)) {
            throw new Zend_View_Exception("Decimal point character cannot be empty.");
        }
        
        return $this;
    }
    
    /**
     * Sets the thousands separator to use
     *
     * @param string $sep
     * @return $this - Fluent interface
     */
    public function setThousandsSep($sep)
    {
        $this->_thousandsSep = $sep;
        
        return $this;
    }
}