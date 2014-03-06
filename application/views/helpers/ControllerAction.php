<?php
class Zend_View_Helper_ControllerAction extends Zend_View_Helper_Abstract
{
    public function controllerAction(){
        $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        
        $return["controller"] = $controller;
        $return["action"] = $action;
        
        return $return;
    }
}
?>
