<?php 

class forms_login extends Zend_Form {
	
    public $elementDecorators = array(
                                    'ViewHelper',
                                    'Errors',
                                    'Description',
                                    array('HtmlTag',array('tag' => 'dt')),
                                    array('Label',array('tag' => 'dt','class' =>'element')),
                                    array(array('row' => 'HtmlTag'), array('tag' => 'dd')));
                                    
    public $buttonDecorators = array(
                                    'ViewHelper',
                                    array('HtmlTag',array('tag' => 'dt')),
                                    //array('Label',array('tag' => 'td')), NO LABELS FOR BUTTONS
                                    array(array('row' => 'HtmlTag'), array('tag' => 'dd')));
    
    public function init() {
        $this->setAction('');
        $this->setMethod('POST');

 
        $this->addElement('text', 'username', array(
            'decorators' => $this->elementDecorators,
            'label'       => 'Usuário:',
            'validators'  => array(
                                array('stringLength', 4, 8)
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
 
 
 
 		$this->addElement('submit', 'submit', array(
            'decorators' => $this->buttonDecorators,
            'label'       => 'login',
        ));
        
    }
    
    
    
    public function loadDefaultDecorators() {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl')),
            'Form',
            'Errors'
        ));
    }
}