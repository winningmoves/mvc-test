<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function loginAction()
    {
        $form = new Application_Form_Login();
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($this->_process($form->getValues())) {
                    // We're authenticated! Redirect to the home page
                    
                    $uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
                    if($uri === "/auth/login"){
                        $this->_helper->redirector('index', 'index');
                    }else{
                        $this->_redirect($uri);
                    }
                }
            }
        }
        
        $this->view->form = $form;
    }
    
    
    
    protected function _process($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['username']);
        $adapter->setCredential($values['password']);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        
        return false;
    }
    
    protected function _getAuthAdapter() 
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('users')
        ->setIdentityColumn('username')
        ->setCredentialColumn('password')
        ->setCredentialTreatment('SHA1(CONCAT(?,salt))');

        return $authAdapter;
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
        Zend_Session::destroy();
        Zend_Session::regenerateId();
        $this->_helper->redirector('index', 'index'); // back to login page
    }
    
    public function notAllowedAction() {
        
    }

}















