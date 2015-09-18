<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
    public $validate = array(
        'Email'     => array(
            'unique' => array(
                'rule'      => 'isUnique',
                'message'   => 'Email already exists'
            ),
            'Email' => array(
                'rule'     => 'email',
                'required' => true,
                'message'  => 'Enter correct email'
            ),
            'Email' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Enter the email Address'
            )
        ),
        'Password'  => array(
            'rule'      => array('minLength', 8),
            'message'   => 'Password must be at least 8 characters long',
        )
    );
    
    public function beforeSave($options = array()) {
        if (!empty($this->data['User']['Password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data['User']['Password'] = $passwordHasher->hash(
                $this->data['User']['Password']
            );
        }
        return true;
    }
    
    public function addUser( $user_data ){
        $this->set($user_data);
        if(!$this->validates()){
            $errors = $this->validationErrors;
            foreach($errors as $error):
                throw new BadRequestException($error[0]);
            endforeach;
        }

        $this->create();
        if(!$this->save($user_data)) {
            throw new InternalErrorException('User cannot Created');
        }
    }
    
    public function getUser($id = NULL){
        if( $id ) {
               return $this->findByid($id);
           }
         else {
           throw new NotFoundException('User Id not found');
        }
    }
    
    public function getAllUser() {
        if($this->find('all')) {
            return $this->find('all');
        } else {
            throw new NotFoundException('No record in the data base or Empty Table');
        }
    }
    
     public function editUser( $user_data ) {
        $this->set($user_data);
        if(!$this->validates()){
            $errors = $this->validationErrors;
            foreach($errors as $error):
                throw new BadRequestException($error[0]);
            endforeach;
        }
        if(!$this->save($user_data)) {
            throw new InternalErrorException('User cannot be updated');
        }
    }
    
    public function deleteUser($id) {
        if($id) {
            $product = $this->find('first', array(
                'conditions' => array(
                    'user.id' => $id
                ),
                'recursive' => -1
            ));
            
            if(!empty($product)) {
                if(!$this->delete($id)) {
                    throw new InternalErrorException('User cannot be deleted!');
                }
            } else {
                    throw new InternalErrorException('No user to delete for this id');
            }
        } else {
            throw new NotFoundException('User Id not found');
        }
    }
    
    public function authenticate($data) {
        $user = $this->find('first', array(
            'conditions' => array('User.Email' => $data['Email']),
        ));
        if(empty($user)) {
            throw new UnauthorizedException('Email Address is incorrect');
        }
        if($user['User']['Password'] !== AuthComponent::password($data['Password'])) {
            throw new UnauthorizedException('Password is incorrect');
        } 
        unset($user['User']['Password']);
        return $user;
    }
}
?>