       <?php 
            ini_set('upload_max_filesize', '200M');
ini_set('post_max_size', '180M');
    extract($_POST);
if(isset($_FILES['file'])){
      $errors= array();
      $file_name = $_FILES['file']['name'];
      $file_size = $_FILES['file']['size'];
      $file_tmp = $_FILES['file']['tmp_name'];
      $file_type = $_FILES['file']['type'];
      $value=explode('.',$_FILES['file']['name']);
      $file_ext=strtolower(end($value));
      
      $expensions= array("bsp");
    $name=pathinfo($file_name,PATHINFO_FILENAME);
      
      if(in_array($file_ext,$expensions)=== false){
             echo "<script>
     spop({
	template: 'Error! Only .bsp files < 200Mb allowed',
	group: 'submit-satus',
    position  : 'top-right',
	style: 'error',
	autoclose: 2000
        });</script>";
           $errors[]="extension not allowed, please choose a .bsp file.";
      }
      
      if($file_size > 1024*200*1024) {
             echo "<script>
     spop({
	template: 'Error! Only .bsp < 200Mb allowed',
	group: 'submit-satus',
    position  : 'top-right',
	style: 'error',
	autoclose: 2000
        });</script>";
       $errors[]='File size must be less than 200 MB';
      }
      
      if(empty($errors)==true) {
          
    move_uploaded_file($file_tmp,"lava/tf/maps/".$file_name); 
	  echo "<script>
     spop({
	template: 'Success! File uploaded',
	group: 'submit-satus',
    position  : 'top-right',
	style: 'success',
	autoclose: 2000
        });</script>";
          
   }
	  
}
		?>