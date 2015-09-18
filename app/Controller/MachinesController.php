<?php
App::uses('Controller', 'Controller');

class MachinesController extends AppController {
	
    public $uses = array('User', 'Product', 'Machine'); 
    public $components = array('RequestHandler');
    public $out = array();
    /**
     * Using Get method
     */
    
    public function index() {
		// header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		$this->autoRender = false;
		if($this->request->is('GET')) {
			try {
				$total_machines = $this->Machine->getAllMachine();
				$this->out['AllMachines'] = $total_machines; 
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
	
	 public function addmachine() {   
		$this->autoRender = FALSE;
		$dataSource = $this->Machine->getDataSource();
		$dataSource->begin();
        if($this->request->is('POST')) {
             try{
				$request_data = $this->request->input('json_decode', TRUE);
				$machine_data = $this->Machine->addMachine( $request_data );
				$this->out['MachineData'] = $machine_data;
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
	
	 public function changemcstatus() { 
	 // header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		$this->autoRender = FALSE;
		$dataSource = $this->Machine->getDataSource();
		$dataSource->begin();
        if($this->request->is('POST')) {
             try{
				$request_data = $this->request->input('json_decode', TRUE);
				$this->Machine->changeMachineStatus( $request_data );
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
	
	public function deletemachine() { 
		// header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		$this->autoRender = FALSE;
		$dataSource = $this->Machine->getDataSource();
		$dataSource->begin();
        if($this->request->is('POST')) {
             try{
				$request_data = $this->request->input('json_decode', TRUE);
				$this->Machine->deletemachine( $request_data );
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
	/*
		function to tell the total, inactive and active machines 
	
	*/
	public function getmachinesinfo() {
	// header("Access-Control-Allow-Credentials: true");
	// header("Access-Control-Allow-Origin: *");
	// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
	// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
	$this->autoRender = FALSE;
	if($this->request->is('GET')) {
			try{
				$machines = $this->Machine->machinesinfo();
				$this->out['Machineinfo'] = $machines;
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
}
?>