<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

	public function getAllUsers(){
            $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('u'=>'users'), 'u.*');

        //echo $select->__toString();

            return $this->fetchAll($select)->toArray();
	}
        
	public function getUser1($id){
            $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('u'=>'users'), 'u.*')
                ->where("id = ?", $id);

        //echo $select->__toString();

            return $this->fetchAll($select)->toArray();
	}
        
        public function getUser($id){
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row){
			throw new Exception("Could not find row $id");
		}
		return $row->toArray(); 
	}
        
	public function checkUsername($username){
            $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('u'=>'users'), 'u.*')
                ->where("username = ?", $username);

        //echo $select->__toString();

            return $this->fetchAll($select)->toArray();
	}
        
        public function addUser($username, $password, $role, $salt, $date_created, $email, $active){
		$data = array(
			'username' => $username,
			'password' => $password,
            'role' => $role,
            'salt' => $salt,
            'date_created' => $date_created,
            'email' => $email,
            'active' => $active
		);
		
                return $this->insert($data);
	}
	
	public function updateUser($id, $username, $password, $role, $salt, $email, $active){
            $data = array("username" => $username, "email" => $email);
            
            if(!is_null($password)){
                $data['password'] = $password; 
            }
            
            if(!is_null($role)){
                $data['role'] = $role; 
            }
            
            if(!is_null($salt)){
                $data['salt'] = $salt; 
            }
            if(!is_null($active)){
                $data['active'] = $active; 
            }
            
            $this->update($data, 'id = '. (int)$id);
	}
	
	public function deleteUser($id){
		return $this->delete('id =' . (int)$id);
	}
}

