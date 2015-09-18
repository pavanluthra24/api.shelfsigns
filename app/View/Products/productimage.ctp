<?php  
echo $this->Form->create('Products', array(
                'type' => 'post',
                'enctype' => 'multipart/form-data'
            ));
?>
<b>Product Image</b> 
<input type="file" name="csvfiles" id="csvfile"> 
<br />
<input type="submit" value="readcsv">
<?php echo $this->Form->end(); ?>