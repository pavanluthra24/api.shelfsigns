<?php
App::uses('Controller', 'Controller');

class ProductsController extends AppController {
    
    public $uses = array('User', 'Product'); 
    public $components = array('RequestHandler');
    public $out = array();
    /**
     * Using Get method
     */
    
    public function index() {
		$this->autoRender = false;
        if($this->request->is('GET')) {
            try {
                $product_data = $this->Product->getAllProduct();
                $this->out['AllProducts'] = $product_data; 
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
    
    public function view( $id ) {
        $this->autoRender = FALSE;
        if($this->request->is('GET')) {
            try{
                $json_data = $this->Product->getProduct($id);
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
    public function add() {       
       $this->autoRender = FALSE;
       $dataSource = $this->Product->getDataSource();
       $dataSource->begin();
        if($this->request->is('POST')) {
             try{
                  // $request_data = $this->request->data;
                  $request_data = $this->request->input('json_decode', TRUE);
                  $this->Product->addProduct( $request_data );
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
    
    /**
     * Using POST method
     */
    public function update() {
        $this->autoRender = FALSE;
        $dataSource = $this->Product->getDataSource();
        $dataSource->begin();
        if($this->request->is('POST')) {
           try{
               $request_data = $this->request->input('json_decode', TRUE);
               $data = $this->Product->editProduct( $request_data );
               $dataSource->commit();
               $this->message = TRUE;
               $this->code = 200;// $request_data = $this->request->data;
               
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
    
    /**
     * 
     * @param type $id
     * @return type
     * @request type delete
     */
    
    public function deleteproduct() {
		// header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->autoRender = FALSE;
        $dataSource = $this->Product->getDataSource();
        $dataSource->begin();
        if($this->request->is('POST')) {
           try{
			   $request_data = $this->request->input('json_decode', TRUE);
               $this->Product->deleteProduct( $request_data );
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
	
	public function csvfile() {
		// header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		
		// pr($_FILES);
		// $tmpname = $_FILES['csvfiles']['tmp_name'];
			// $delim = ','; $enc = '"'; $line = "\r\n";
			// foreach( str_getcsv ( file_get_contents( $tmpname ), $line ) as $row ) $csv[] = str_getcsv( $row, $delim, $enc ); // print_r( $csv );
			// echo'<pre>'; print_r( $csv ); 
			// $this->Product->uploadcsv($csv); 
			
		if($_FILES['file']['type']=='text/csv') {
			$tmpname = $_FILES['file']['tmp_name'];
			$delim = ','; $enc = '"'; $line = "\r\n";
			foreach( str_getcsv ( file_get_contents( $tmpname ), $line ) as $row ) $csv[] = str_getcsv( $row, $delim, $enc ); // print_r( $csv );
			if($this->Product->uploadcsv($csv)){
				$this->message = TRUE;
				$this->code = 200;
			} else {
			   $this->message = "csv file not supported";
				$this->code = 400;
			}
			$this->out['message'] = $this->message;
			$this->out['code'] = $this->code;
			return $this->setOutput($this->out);
		} else {
			$this->out['message'] = "File Format Not supported use csv files only";
			$this->out['code'] = 400;
			return $this->setOutput($this->out);
		}
	}
	
	public function productimage() {
		// header("Access-Control-Allow-Credentials: true");
		// header("Access-Control-Allow-Origin: *");
		// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
	 
		$img = $_FILES['file']['name'];
		$img_Location = 'img/';
		$upload = $_FILES['file']['tmp_name'];
		move_uploaded_file($upload, "$img_Location" . $_FILES['file']['name']);
		$this->out['message'] = True;
		$this->out['code'] = 200;
		return $this->setOutput($this->out);
	}
	
	public function search() {
		/*
			<pre>Array
				(
					[search] => search item 
				)
				</pre>
		*/
		$this->autoRender = FALSE;
		$request_data = $this->request->data;
		if($this->request->is('POST')) {
			try{
				$product_data = $this->Product->searchproduct($request_data['search']);
				$this->out['MyProduct'] = $product_data;
			} catch(Exception $ex) {
				$this->message = $ex->getMessage();
				$this->code = $ex->getCode();
			}
		}
		$data = json_encode($this->out);
		return $data; 
	}
	/*
		function calculates the total, inactive and active machines 
	
	*/
	public function producttableinfo(){
		$this->autoRender = FALSE;
		if($this->request->is('GET')) {
			try{
				$product_table_info = $this->Product->productTableInfo();
				$this->out['Product_Table'] = $product_table_info;
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