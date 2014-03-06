<?php
class Application_Form_AddUser extends Zend_Form{
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
                'class' => 'form-control'
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
        
        $this->addElement('password', 'password2', array(
        'filters' => array('StringTrim'),
        'validators' => array(
        array('StringLength', false, array(0, 50)),
        ),
        'required' => true,
        'label' => 'Confirm Password:',
        'class' => 'form-control'
        ));
        
        $this->addElement('text', 'email', array(
        'filters' => array('StringTrim'),
        'validators' => array(
        array('StringLength', false, array(0, 50)),
        ),
        'required' => true,
        'label' => 'Email:',
        'class' => 'form-control'
        ));
        
        $this->addElement('select', 'role', array(
        'filters' => array('StringTrim'),
        'validators' => array(
        array('StringLength', false, array(0, 50)),
        ),
        'required' => true,
        'label' => 'Role:',
        'class' => 'form-control',
        'multiOptions' => array( 'administrator' => 'administrator', 'user' => 'user', )
        ));
        
        $this->addElement('select', 'active', array(
        'filters' => array('StringTrim'),
        'validators' => array(
        array('StringLength', false, array(0, 50)),
        ),
        'required' => true,
        'label' => 'Active:',
        'class' => 'form-control',
        'multiOptions' => array( '0' => 'off', '1' => 'on', )
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
