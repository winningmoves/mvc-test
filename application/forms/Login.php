<?php
class Application_Form_Login extends Zend_Form{
    public function init()
    {
        $this->setName("login");
        $this->setMethod('post');

        $this->addElement(
            'text', 
            'username', 
            array(
                'filters' => array(
                    'StringTrim', 
                    'StringToLower'
                ),
                'validators' => array(
                    array(
                        'StringLength', 
                        false, 
                        array(
                            0, 
                            50
                        )
                    ),
                ),
                'required' => true,
                'label' => 'Username:',
                'class' => 'form-control',
            )
        );

        $this->addElement('password', 'password', array(
        'filters' => array('StringTrim'),
        'validators' => array(
        array('StringLength', false, array(0, 50)),
        ),
        'required' => true,
        'label' => 'Password:',
        'class' => 'form-control'
        ));

        $this->addElement('submit', 'login', array(
        'required' => false,
        'ignore' => true,
        'label' => 'Login',
        'class' => 'btn btn-large btn-block btn-success'
        ));
    }
}
?>
