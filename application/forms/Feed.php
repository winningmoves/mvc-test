<?php
class Application_Form_Feed extends Zend_Form{
    public function init()
    {
        $this->setName("rssFeed");
        $this->setMethod('post');

        $this->addElement(
            'text', 
            'feed', 
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
                            150
                        )
                    ),
                ),
                'required' => true,
                'label' => 'Feed:',
                'class' => 'form-control',
            )
        );

        $this->addElement('submit', 'Fetch', array(
        'required' => false,
        'ignore' => true,
        'label' => 'Fetch',
        'class' => 'btn btn-large btn-block btn-success'
        ));
    }
}
?>
