<?
class Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract{
    
    private $_auth;
    private $_acl;
    protected $_action;
    protected $_controller;
    protected $_currentRole;
    
    public function __construct(Zend_Acl $acl, Zend_Auth $auth) {
        $this->_auth = $auth;
        $this->_acl = $acl;
    }


    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $this->_init($request);  
        
        if (!$this->_acl->isAllowed($this->_currentRole, $this->_controller, $this->_action)) {
            if ('guest' == $this->_currentRole) {
                $request->setControllerName('auth');
                $request->setActionName('login');
            } else {
                $request->setControllerName('auth');
                $request->setActionName('not-allowed');
                //$redirector = new Zend_Controller_Action_Helper_Redirector();
                //$redirector->gotoSimpleAndExit('tools','info','default');
            }
        }
    }
    
    protected function _init($request) {
        $this->_action = $request->getActionName();
        $this->_controller = $request->getControllerName();
        $this->_currentRole = $this->_getCurrentUserRole();
    }
    
    protected function _getCurrentUserRole() {      

        if ($this->_auth->hasIdentity()) {
            $authData = $this->_auth->getIdentity();
            $role = $authData->role;
        } else {
            $role = 'guest';
        }

        return $role;
    }
}