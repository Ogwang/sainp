<?php

Class forms_sell extends Zend_Form {
	
	public $elementDecorators = array(
                                    'ViewHelper',
                                    'Errors',
                                    'Description',
                                    array('HtmlTag',array('tag' => 'td')),
                                    array('Label',array('tag' => 'td','class' =>'element')),
                                    array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
                                    
    public $buttonDecorators = array(
                                    'ViewHelper',
                                    array('HtmlTag',array('tag' => 'td')),
                                    //array('Label',array('tag' => 'td')), NO LABELS FOR BUTTONS
                                    array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
	
	
	function init() {
		$this->setAction('http://localhost/sainp/portfolio/updatesell');
        $this->setMethod('POST');
        
        $stocksList = $this->getStocks();
        $this->addElement('select', 'stock', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Ativo:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'multioptions' => $stocksList,
            'required'   => true,
        ));
        
        
        $this->addElement('text', 'day', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Dia:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        
        $this->addElement('text', 'month', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Mes:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        
        $this->addElement('text', 'year', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Ano:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        
        $this->addElement('text', 'quantity', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Quantidade:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        
        $this->addElement('text', 'value', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Valor por Acao:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        
        $this->addElement('submit', 'submit', array(
            'decorators' => $this->buttonDecorators,
            'label'       => 'Vender',
        ));
        
	}
        
        
    function loadDefaultDecorators() {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            'Form',
            'Errors'
        ));
	}
	
	
	function getStocks() {
		Zend_Loader::loadClass('table_Portfolio');
		$stocks = new table_Portfolio();
		$stocksList = $stocks->stocks();
		
		foreach ($stocksList as $key => $value) {
			$stock[$value] = $value;
		}
		return $stock;
	}
}