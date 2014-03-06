<?php
class Application_Model_LibraryAcl extends Zend_Acl{
    public function __construct() {
        /***************************************** Resources *****************************/
        $this->add(new Zend_Acl_Resource("index"));
        $this->add(new Zend_Acl_Resource("error"));
        $this->add(new Zend_Acl_Resource("auth"));
        $this->add(new Zend_Acl_Resource("users"));
        //$this->add(new Zend_Acl_Resource("contact"), "info");
        
        /***************************************** User Roles *******************************/
        $this->addRole(new Zend_Acl_Role(null));
        $this->addRole(new Zend_Acl_Role("guest"), null);
        $this->addRole(new Zend_Acl_Role("user"), "guest");
        $this->addRole(new Zend_Acl_Role("administrator"), "user");
        
        
        /************ Access ******************/
        /****************************************** Guest ************************************/
        $this->deny("guest", "index");
        $this->allow("guest", "index", "index");
        $this->deny("guest", "auth");
        $this->deny("user", "users", "add");
        $this->allow("guest", "auth", "login");
        
        $this->allow("guest", "error");
        $this->allow("user", "index");
        $this->allow("user", "auth");
        $this->allow("user", "error");
        
        $this->allow("administrator", "users");
        
    }
}
