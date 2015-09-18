<?php
App::uses('AppModel', 'Model');

class Machine extends AppModel {
	public function addMachine( $machine ) {
		
		$this->create();
		$toSave['Machine'] = array(
           'machinecode' => $machine['machinecode'],
		   'machinestatus' => $machine['machinestatus']
       );
		$this->set($machine);
		$this->create();
		if(!$this->save($toSave)) {
			throw new InternalErrorException('Product cannot be saved');
		}
		$machine_id = $this->getLastInsertId();
		return $this->find('first', array(
			'conditions' => array('Machine.id' => $machine_id)
		));
		
	}
	public function getAllMachine() {
		if($this->find('all')) {
            return $this->find('all');
        } else {
            throw new NotFoundException('No record in the data base or Empty Table');
        }
	}
	public function changeMachineStatus($mcdata) {
		$toSave['Machine'] = array(
            'id' => $mcdata['id'],
            'machinestatus' => $mcdata['machinestatus']
        );
        
        $this->set($toSave);
        if(!$this->save($toSave, true)) {
            throw new InternalErrorException('Question cannot be saved!');
        }
	}
	public function deletemachine($mac_data) {
         if(!$this->delete($mac_data['id'])) {
            throw new InternalErrorException('Machine cannot be deleted!');
        }
    }
	public function getmachineid($mc_code) {
		 return $this->find('first', array(
            'fields' => array(''),
            'conditions' => array(
                'machinecode' => $mc_code
            )
        ));
	}
	
	/*
		function to tell the total, inactive and active machines 
	
	*/
	public function machinesinfo() {
		$machineInfo = array();
		$machineInfo['TotalMachines'] = $this->find('count');
		$machineInfo['ActiveMachines'] = $this->find('count', array(
			'conditions' => array(
			'machinestatus' => 1
			)
		));
		$machineInfo['InActiveMachines'] = $this->find('count', array(
			'conditions' => array(
			'machinestatus' => 0
			)
		));
		return $machineInfo;
	}
	
	
	// public $hasMany = array(
        // 'Machineproduct' => array(
            // 'foreignKey' => 'id'
        // )
    // ); 
} 
	?>