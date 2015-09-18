<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public function beforeFilter() {
       parent::beforeFilter();
    }
       
    
     public $components = array(
        'RequestHandler',
        'Session',
    );
    
    protected function setOutput($body){
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		
		header('Cache-Control: no-store, private, no-cache, must-revalidate');                  // HTTP/1.1
        header('Cache-Control: pre-check=0, post-check=0, max-age=0, max-stale = 0', false);    // HTTP/1.1
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');                                       // Date in the past  
        header('Expires: 0', false); 
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
        header('Pragma: no-cache');
		
		
        $this->response->body(json_encode($body));
        $this->response->type('json');
        return $this->response;
    }
    
    public function setUserSession( $user ) {
        $this->Session->write('User.userdetails', $user);
    }
    
    public function readUserSession() {
        return $this->Session->read('User.userdetails');
    }
    public function deleteUserSession() {
        return $this->Session->delete('User');
    }
}
