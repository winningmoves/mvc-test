<?php
class Zend_Controller_Action_Helper_UserRole extends Zend_Controller_Action_Helper_Abstract
{
    public function userRole(){
        $auth = Zend_Auth::getInstance();
        $role = array();
        
        if ($auth->hasIdentity()) {
            $role['role'] = $auth->getIdentity()->role;
            $role['id'] = $auth->getIdentity()->id;
            return $role;
        }else{
            $role['role'] = "guest";
            $role['id'] = NULL;
            return $role;
        }
    }
}
?>
