<?php
App::uses('Controller', 'Controller');

class UsersController extends AppController {
    public $uses = array('User', 'Product'); 
    public $out = array();
    
//    public function beforeFilter() {
//       parent::beforeFilter();
//        $this->Auth->allow(array('login', 'logout', 'add'));
//        if($this->Session->check('User.userdetails')){
//            $this->Auth->allow(array('index', 'view', 'edit', 'delete'));
//        } 
//    }
    
    public function index() {
		$this->autoRender = FALSE;
        if($this->request->is('GET')) {
            try {
                $product_data = $this->User->getAllUser();
                $this->out['TotalUsers'] = $product_data;
                $this->message = TRUE;
                $this->code = 200;
            } catch (Exception $ex) {
                $this->message = $ex->getMessage();
                $this->code = $ex->getCode();
            }
        $this->out['message'] = $this->message;
        $this->out['code'] = $this->code;
        return $this->setOutput($this->out);
        }
    }
    
    /**
     * finction to add the admin
     */
    public function add() {       
       $this->autoRender = FALSE;
       $dataSource = $this->User->getDataSource();
       $dataSource->begin();
        if($this->request->is('POST')) {
             try{
                  // $request_data = $this->request->data;
                  $request_data = $this->request->input('json_decode', TRUE);
                  $request_data['Role'] = 'Admin';
                  $request_data['Active'] = 1;   //default to 1(Active)
                  
                  $this->User->addUser( $request_data );
                  $dataSource->commit();
                  $this->message = TRUE;
                  $this->code = 200;
             } catch(Exception $ex) {
                  $dataSource->rollback();
                  $this->message = $ex->getMessage();
                  $this->code = $ex->getCode();
             }
        }
        $this->out['message'] = $this->message;
        $this->out['code'] = $this->code;
        return $this->setOutput($this->out);
    }
    
    public function view( $id = NULL ) {
        $this->autoRender = FALSE;
        if($this->request->is('GET')) {
            try{
                $json_data = $this->User->getUser($id);
                $this->out['ProductDetails'] = $json_data;
                $this->message = TRUE;
                $this->code = 200;
            } catch(Exception $ex) {
                $this->message = $ex->getMessage();
                $this->code = $ex->getCode();
            }
        }
        $this->out['message'] = $this->message;
        $this->out['code'] = $this->code;
        return $this->setOutput($this->out);
    }
    
    /**
     * Using POST method
     */
    public function edit($id) {
        $this->autoRender = FALSE;
        $dataSource = $this->Product->getDataSource();
        $dataSource->begin();
        if($this->request->is('POST')) {
           try{
               $request_data = $this->request->input('json_decode', TRUE);
               //pr($request_data);
               $this->User->editUser( $request_data );
               $dataSource->commit();
               $this->message = TRUE;
               $this->code = 200;
               
           } catch(Exception $ex) {
               $dataSource->rollback();
               $this->message = $ex->getMessage();
               $this->code = $ex->getCode();
           }
        }
        $this->out['message'] = $this->message;
        $this->out['code'] = $this->code;
        return $this->setOutput($this->out);
    }
    
    public function delete($id = NULL) {
        $this->autoRender = FALSE;
        $dataSource = $this->User->getDataSource();
        $dataSource->begin();
        if($this->request->is('DELETE')) {
           try{
               $this->User->deleteUser( $id );
               $dataSource->commit();
               $this->message = TRUE;
               $this->code = 200;
           } catch(Exception $ex) {
               $dataSource->rollback();
               $this->message = $ex->getMessage();
               $this->code = $ex->getCode();
           }
        }
        $this->out['message'] = $this->message;
        $this->out['code'] = $this->code;
        return $this->setOutput($this->out);
    }
    
//    public function login() {
//         if ($this->request->is('POST')) {
//            $request_data = $this->request->input('json_decode', TRUE);
//            pr($request_data);
//            if ($this->Auth->login()) {
//                echo'valid'; die;
//            } else {
//                echo'not valid'; die;
//            }
//        }
//    }
    public function login() {
        $this->autoRender = FALSE;
           if ($this->request->is('POST')) {
           try {
                $request_data = $this->request->input('json_decode', TRUE);
                $user = $this->User->authenticate($request_data);
                if ($user) {
                    $this->setUserSession($user);
                    $this->out['userdetails'] = $user;
                    $this->message = TRUE;
                    $this->code = 200;
                } else {
                    return FALSE;
                }
           } catch(Exception $ex) {
               $this->message = $ex->getMessage();
               $this->code = $ex->getCode();
           }
       }
        $this->out['message'] = $this->message;
        $this->out['code'] = $this->code;
        return $this->setOutput($this->out);
    }
    
    public function logout() {
        $this->autoRender = FALSE;
           try {
                if($this->deleteUserSession()) {
                    $this->message = TRUE;
                    $this->code = 200;
                }
           } catch(Exception $ex) {
               $this->message = $ex->getMessage();
               $this->code = $ex->getCode();
           }
    
        $this->out['message'] = $this->message;
        $this->out['code'] = $this->code;
        return $this->setOutput($this->out);
    }
}
?>