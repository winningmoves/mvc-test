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
    
    protected function _initAutoLoad() {
        $this->_fc = Zend_Controller_Front::getInstance();
        
        $this->_acl = new Application_Model_LibraryAcl();
        $this->_auth = Zend_Auth::getInstance();
        
        $this->_fc->registerPlugin(new Plugin_AccessCheck($this->_acl, $this->_auth), 1);
    }

}

