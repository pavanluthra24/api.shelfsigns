<?php
App::uses('Controller', 'Controller');

class MachineproductsController extends AppController {
	   
    public $uses = array('User', 'Product', 'Machine', 'Machineproduct'); 
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
				$product_data = $this->Machineproduct->mcprodassignment();
				$this->out['AllMcProducts'] = $product_data;
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
	
	public function add() {
		$this->autoRender = FALSE;
		$dataSource = $this->Machineproduct->getDataSource();
		$dataSource->begin();
		if($this->request->is('POST')) {
			 try{
				$request_data = $this->request->input('json_decode', TRUE);
				$this->Machineproduct->addmcprodassignment( $request_data );
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
	
	public function update() {
		// header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		$this->autoRender = FALSE;
		$dataSource = $this->Machineproduct->getDataSource();
		$dataSource->begin();
		if($this->request->is('POST')) { 
			 try{
				$request_data = $this->request->input('json_decode', TRUE);
				$this->Machineproduct->updatemcprodassignment( $request_data );
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
	
	public function deletemcproassignment() {
		// header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		$this->autoRender = FALSE;
		$dataSource = $this->Machineproduct->getDataSource();
		$dataSource->begin();
		if($this->request->is('POST')) { 
			 try{
				$request_data = $this->request->input('json_decode', TRUE);
				$this->Machineproduct->deletemcprodassignment( $request_data );
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
		Function that send the response to the andriod team
	*/
	public function myproduct() {
		// header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		$this->autoRender = FALSE;
		$request_data = $this->request->data;
		$dataSource = $this->Machineproduct->getDataSource();
		$dataSource->begin();
		if($this->request->is('POST')) {
			 try{
				$machine_info = $this->Machine->getmachineid( $request_data['machineid'] );
				$machine_id = $machine_info['Machine']['id'];
				$product_data = $this->Machineproduct->getproductinfobymcid($machine_id);
				if($product_data['Product']['Cost'] != ''){
					$cost = $product_data['Product']['Cost'];
					$string = str_replace('$', '', $cost); 
					if(strpos($string,".") != ''){
						$prodcost = explode(".",$string);
						$dolar = $prodcost[0];
						$cent = $prodcost[1];
					} else {
						$dolar = $string;
						$cent = 00;
					}
				}
				$this->out['MyProduct'] = $product_data['Product'];	
				$this->out['MyProduct']['dolar'] = $dolar;	
				$this->out['MyProduct']['cent'] = $cent;			
				$this->message = TRUE;
				$this->code = 200;
			 } catch(Exception $ex) {
				$dataSource->rollback();
				$this->message = $ex->getMessage();
				$this->code = $ex->getCode();
			 }
		}
		$data = json_encode($this->out);
		return $data; 
		
	}
}
?>