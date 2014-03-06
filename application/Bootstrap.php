<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected $_acl;
    protected $_auth;
    protected $_fc;
    
    public function _initLoader()
    {
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers'); 
    }

}

