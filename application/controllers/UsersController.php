<?php

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction(){
        $db = new Application_Model_DbTable_Users();
        
        $request = $this->getRequest();
        
        $users = $db->getAllUsers("all");
            
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($users));

        $page = $request->page;

        if($page === "all"){
            $all = $paginator->getTotalItemCount();
            $paginator->setItemCountPerPage($all);
            $this->view->controls = FALSE;
        }else{
            $paginator->setItemCountPerPage(15);
            $paginator->setCurrentPageNumber($page);
            $this->view->controls = TRUE;
        }

        $this->view->users = $paginator;

        $this->view->addUser = TRUE;
    }
    
    public function addAction(){
        $form = new Application_Form_AddUser();
        $request = $this->getRequest();
        
        //$form->removeElement('role');
        $form->removeElement('active');
        $form->setName("create_user");

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $username = $request->getParam("username");
                $email = $request->getParam("email");
                $password = $request->getParam("password");
                
                $db = new Application_Model_DbTable_Users();
                
                $check_user = $db->checkUsername($username);
            
                if(!$check_user){

                    $salt = sha1(uniqid(rand(), true));
                    $password_salted = sha1($password.$salt);
                    
                    $role = $request->getParam("role");
                    $active = 1;

                    $date_created = date("Y-m-d H:i:s");


                    $result = $db->addUser($username, $password_salted, $role, $salt, $date_created, $email, $active);

                    if($result){
                        $this->_helper->redirector('index', 'users');
                    }

                }else{
                    $information = "<p class='error'>Username '$username' already exists, try another</p>";
                }
                
            }else{
                $information = "<p class='error'>You filled in the form wrong</p>";
                
            }
        }else{
            $this->view->show = TRUE;
            $this->view->form = $form;
        }
    }

    public function editAction(){
        $form = new Application_Form_AddUser();
        $form->setName("edit_user");
        
        $db = new Application_Model_DbTable_Users();
            
        $request = $this->getRequest();
        
        $temp = $this->_helper->userRole->userRole();
        $role = $temp['role'];
        $current_id = (int)$temp['id'];
        
        if($request->getParam('id') && !$request->isPost()){
            
            $form->populate($db->getUser($request->id, "All"));
            $form->getElement('login')->setLabel('Update');
            $form->getElement('password')->setAttrib('required', NULL);
            $form->getElement('password2')->setAttrib('required', NULL);
            
            if($role !== 'administrator'){
                $form->getElement('role')->setAttrib('disabled', TRUE);
                $form->getElement('active')->setAttrib('disabled', TRUE);
            }
            $this->view->form = $form;
            
        }else if($request->isPost()){
            
            $id = (int)$request->id;
            $username = $request->getParam('username');
            $email = $request->getParam('email');

            if($role == "administrator"){
                $role = $request->getParam('role');
                $active = $request->getParam('active');
            }else{
                $role = NULL;
                $active = NULL;
            }
            
            
            $password = NULL;
            $salt = NULL;
            
            if($request->getParam('password') != ""){
                if($request->getParam('password') == $request->getParam('password2')){
                    $password_tmp = $request->getParam('password');
                    
                    $salt = sha1(uniqid(rand(), true));
                    $password = sha1($password_tmp.$salt);
                }
            }
            
            $result = $db->updateUser($id, $username, $password, $role, $salt, $email, $active);
            
            if($result){
                $information = "<p class='success'>User '$username' has been updated</p>";
                
                $this->_helper->redirector('index');
            }else{
                $information = "<p class='error'>Nothing was updated, try again in a bit</p>";
                
                $this->_helper->redirector('index');
            }
        }
    }
    
    public function deleteAction(){
        $request = $this->getRequest();
        $temp = $this->_helper->userRole->userRole();
        $role = $temp['role'];
        $current_id = (int)$temp['id'];
        
        if($role === "administrator"){
            if($request->getParam('id')){
                $db = new Application_Model_DbTable_Users();
                $user = $db->deleteUser($request->getParam('id'));

                if($user){
                    $information = "<span class='success'>User has been removed</span>";

                    $this->_helper->redirector('index');
                }
            }
        }
    }
}















