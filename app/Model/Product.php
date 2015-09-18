<?php
App::uses('AppModel', 'Model');

class Product extends AppModel {
//     public $validate = array(
//        'Product_Author' => array(
//            'notEmpty' => array(
//                'rule'     => 'notEmpty',
//                'required' => true,
//                'message'  => 'Product Author Is Not Defined'
//            )
//        ),
//        'Product_Brand' => array(
//            'notEmpty' => array(
//                'rule'     => 'notEmpty',
//                'required' => true,
//                'message'  => 'Product Brand Is Not Defined'
//            )
//        )
//    );
     

    public function addProduct( $product_data ) {
         //pr($product_data);
//        $toSave['Product'] = array(
//            'Product_Author' => $product_data['Product_Author'],
//            'Product_Brand' => $product_data['Product_Author'],
//            'Product_Type' => $product_data['Product_Author'],
//            'Product_Description' => $product_data['Product_Author'],
//            'Product_Current_Cost' => $product_data['Product_Author'],
//            'Product_Currency' => $product_data['Product_Author'],
//            'Product_Weight' => $product_data['Product_Author'],
//            'Product_Qty' => $product_data['Product_Author'],
//            'Product_Logo' => $product_data['Product_Author'],
//            'Product_Current_CostPerWeight_Per_Qty' => $product_data['Product_Author'],
//            'Product_Package_QtyxWeight_Per_Qty' => $product_data['Product_Author'],
//            'Product_Discounted_CostPerWeight_Per_No' => $product_data['Product_Author'],
//            'Product_Barcode' => $product_data['Product_Author'],
//            'Product_Barcode_No' => $product_data['Product_Author'],
//            'Product_Availability' => $product_data['Product_Author'],
//            'Product_Image' => $product_data['Product_Author'],
//            'Product_Specials_Per_Offer' => $product_data['Product_Author'],
//            'Product_Previous_Cost' => $product_data['Product_Author'],
//            'Product_Status' => 1,
//            'Product_Previous_Cost' => $product_data['Product_Author']
//        );
            $this->set($product_data);
            if(!$this->validates()){
                $errors = $this->validationErrors;
                foreach($errors as $error):
                    throw new BadRequestException($error[0]);
                endforeach;
            }
            
            $this->create();
            if(!$this->save($product_data)) {
                throw new InternalErrorException('Product cannot be saved');
            }
    }
    
    public function editProduct( $product_data) {
			$this->set($product_data);
            if(!$this->validates()){
                $errors = $this->validationErrors;
                foreach($errors as $error):
                    throw new BadRequestException($error[0]);
                endforeach;
            }
            if(!$this->save($product_data)) {
                throw new InternalErrorException('Product cannot be updated');
            }

    }
    
    public function deleteProduct($request_data) {
			if(!$this->delete($request_data['productId'])) {
				throw new InternalErrorException('Product cannot be deleted!');
			}
    }
    
    public function getProduct( $id ) {
        if( $id ) {
                return $this->findByid($id);
        } else {
            throw new NotFoundException('Product Id not found');
        }
    }
    
    public function getAllProduct() {
        if($this->find('all')) {
            return $this->find('all');
        } else {
            throw new NotFoundException('No record in the data base or Empty Table');
        }
    }
	
	public function uploadcsv( $csv ) {
        foreach ($csv as $k=>$product):
            if($k == 0){
                continue;
            } 
            $toSave['Product'] = array(
				'Template_Title' => $product[0],
				'Template_Layout' => $product[1],
				'Cost' => $product[2],
				'Qty' => $product[3],
				'WAS' => $product[4],
				'Cost_Per_Weight' => $product[5],
				'Saving' => $product[6],
				'Product_Detail' => $product[7],
				'Single_Price_Details' => $product[12],
				'Bar_Code' => $product[13],
				'Bar_Code_No' => $product[14],
				'Ref' => $product[15],
				'Special_Number_Code' => $product[16],
				'Special_Alphabet_Code' => $product[17],
				'Details' => $product[18],
				'Tagging' => $product[8],
				'Special_Icon' => $product[9],
				'Tagging_MSA' => $product[10],
				'Status' => 1,
				'Ticker' => 'Please enter the ticker text',
				'Video' => $product[11],
				'Specials_Durations' => $product[19],
				'Product_Image' => $product[21],
				'Date' => date('Y-M-d: H:i:s')
            ); 
            $this->create();
            if(!$this->save($toSave)) {
                throw new InternalErrorException('Product cannot be saved');
            } 
        endforeach; 
		return true; 
	}
	
	public function productTableInfo(){
		$productInfo = array();
		$productInfo['TotalProducts'] = $this->find('count');
		$productInfo['ActiveProducts'] = $this->find('count', array(
			'conditions' => array(
			'Status' => 1
			)
		));
		$productInfo['InActiveProducts'] = $this->find('count', array(
			'conditions' => array(
			'Status' => 0
			)
		));
		return $productInfo;
	}
	
	public function searchproduct($searchprod) {
		return $this->find('all', array('conditions'=>array('Product_Detail LIKE'=>"%$searchprod%")));
	}
}
?>