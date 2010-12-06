<?php

Class forms_registration extends Zend_Form {
	
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
		$this->setAction('../index/regupdate');
        $this->setMethod('POST');
        
        $this->addElement('text', 'login', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Login:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        $this->addElement('password', 'password', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Senha:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        $this->addElement('text', 'firstname', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Nome:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        $this->addElement('text', 'lastname', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Sobrenome:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        $this->addElement('text', 'login', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Login:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        /*$this->addElement('text', 'day', array(
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
        ));*/
        
        
        $this->addElement('text', 'email', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Email:',
            'validators'  => array(
                                array('stringLength', 4, 10)
                            ),
            'required'   => true,
        ));
        
        $this->addElement('submit', 'submit', array(
            'decorators' => $this->buttonDecorators,
            'label'       => 'Salvar',
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
	
}