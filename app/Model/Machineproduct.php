<?php
App::uses('AppModel', 'Model');

class Machineproduct extends AppModel {
	// public $validate = array(
        // 'product_id' => array(
                // 'rule'    => 'isUnique',
                // 'message' => 'Email already registered'
            // ) 
    // );
	
	public function addmcprodassignment( $mcprodata ) {
		$machine_info = $this->find('first', array(
			'conditions'=> array('product_id'=>$mcprodata['productid'])
		));
		if(!empty($machine_info)){
			$message = 'This Product already have a machine'; 
			throw new InternalErrorException($message);
		} else {
			$toSave['Machineproduct'] = array(
				'machine_id' => $mcprodata['machineid'],
				'product_id' => $mcprodata['productid']
			);
			// if(!$this->validates()){
				// $errors = $this->validationErrors;
				// foreach( $errors as $val ):
					// throw new BadRequestException($val[0]);
				// endforeach;
			// }
			$this->create();
			if(!$this->save($toSave)) {
				throw new InternalErrorException('Product cannot be saved');
			}
		}
	}
	
	public function updatemcprodassignment( $mcprodata ) {
		$this->updateAll(
            array(
                'machine_id' => $mcprodata['machineid']
            ),
            array('product_id' =>$mcprodata['productid'])
        );
	}
	public function mcprodassignment() {
		if($this->find('all')) {
            return $this->find('all');
        } else {
            throw new NotFoundException('No record in the data base or Empty Table');
        }
	}
	public function getproductinfobymcid($machine_id) {
		//return $this->find('all');
		return $this->find('first', array(
			'conditions' => array('Machineproduct.machine_id' => $machine_id)
		));
	}
	public function deletemcprodassignment($request_data) {
		$this->deleteAll(
		array(
			"Machineproduct.machine_id" => $request_data['machineid'],
			"Machineproduct.product_id" => $request_data['productid']
			)
		);
	}
	
	// Associations
  public $belongsTo =  array('Machine','Product');
}
?>