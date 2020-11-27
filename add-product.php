<?php
/**
 * Add Product Page
 *
 *
 * @version 1
 */
defined( 'ABSPATH' ) || exit;
do_action( 'before_edit_account_form' ); 

function getPageName(){
	if (isset($_GET["page"])){
	return $_GET["page"];
	}
return 'marketplace';  //TEST THIS
}

function createUserFileAndReturnPath($target_dir){
		$user = wp_get_current_user();
	    $email = $user->user_email;
	    $new_target_dir = $target_dir . $email;

	if (!file_exists($new_target_dir)) {
		mkdir($new_target_dir);
	}
	
	return $new_target_dir . "/";
}

function checkImg(){
	if (!empty(array_filter($_FILES['imgUpload']['name']))) {
        foreach($_FILES['imgUpload']['name'] as $key => $val) {
			$uploadOk = 1;
			$target_file = basename($_FILES["imgUpload"]["name"][$key]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["imgUpload"]["tmp_name"][$key]);
            if ($check !== false) {
                // echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["imgUpload"]["size"][$key] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, JPEG & PNG files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
				return false;
                // if everything is ok, try to upload file
            }
		}
	}
	return true;
}

function uploadImg($product_id) {
    if (!empty(array_filter($_FILES['imgUpload']['name']))) {
        foreach($_FILES['imgUpload']['name'] as $key => $val) {
            $target_dir = "/var/www/html/wp-content/plugins/Unimarket/pictures/".getEmailSuffix().
            "/";
            $target_dir = createUserFileAndReturnPath($target_dir);
            $target_file = $target_dir.$product_id.
            //"-".time().
            "-".basename($_FILES["imgUpload"]["name"][$key]);

            while (file_exists($target_file)) {
                $target_file = $target_dir.$product_id.
                "-".time().
                "-".basename($_FILES["imgUpload"]["name"][$key]);
            }
			
 
                if (move_uploaded_file($_FILES["imgUpload"]["tmp_name"][$key], $target_file)) {
                    // echo "The file ". basename( $_FILES["imgUpload"]["name"]). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }



function displayAddProductForm(){
	$user = wp_get_current_user();
	echo '<div style="margin: 0 auto; width:500px;"><form action="" method="post" id="productForm" enctype="multipart/form-data">
  Short Description:<font color="red"> *</font><br>
  <input type="text" name="shortDescription" placeholder="Provide a short description of the item" style="width: 600px;" maxlength = "120" required>
  <br>
  Description:<font color="red"> *</font><br>
  <textarea name="Description" form="productForm" placeholder="Enter complete item description" required></textarea>
  <br>';
	if(getPageName() == 'marketplace'){
  	echo 'Price:<font color="red"> *</font><br>
  		<input type="number" step="0.01" name="price" placeholder="e.g. 12.00" style="width:100px;" onkeypress="return 			((event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 46))"required>  <i class="fas fa-dollar-sign"
  		style="font-size: 17px;"></i>';
	}else{
		echo 'What do you want in exchange:<font color="red"> *</font><br>
  <textarea name="tradeOffers" form="productForm" placeholder="Enter items that you wish to receive from the trade" required></textarea>';
	}
  echo '<br><br>
  Select one or more applicable categories: <font color="red"> * </font><br>
  <select id="categories" name="categories" multiple style="width: 200px;">
  <option value="Kissam">Kissam</option>
  <option value="Rand">Rand</option>
  <option value="Commons">Commons</option>
  <option value="Ebi">Ebi</option>
  </select>
  <br><br>
  Select the best date and time for meet up:<font color="red"> *</font><br> 
  <input type="date" class="datePicker" placeholder="MM/DD/YY H:M" style="width:180px;" required>
  <br>
  Select your preferred meet up place:<font color="red"> *</font><br>  
  <select id="places" name="places" multiple style="width: 200px;">
  <option value="Kissam">Kissam</option>
  <option value="Rand">Rand</option>
  <option value="Commons">Commons</option>
  <option value="Ebi">Ebi</option>
  </select>
  <br><br>
  Select image to upload:<font color="red"> *</font><br>
  <input type="file" name="imgUpload[]" id="imgUpload" multiple="multiple" required="required">
  <br><br>
  <input type="checkbox" name="addToOtherPage" value="checkToAddToOtherPage" onclick="handleClick()">
	<script>
	
		function handleClick(){
		var x = document.getElementById("label").htmlFor;
		
		if(x === "addToTradeplace"){
			

		}else{

	
		}
	}	
		</script>';
	  if(getPageName() == 'marketplace'){
 	    echo '<label id="label" for="addToTradeplace"> Are you willing to put this product in the tradeplace as well?</label>';
	  }else{
		echo '<label id="label" for="addToMarketplace"> Are you willing to put this product in the marketplace as well?</label>';
	  }
 echo '<br><br>
  <input type="submit" name="submit" value="Add Item">
  </form></div>';
}

function displayAddProduct(){
	if(isStudentEmail()){
	displayAddProductForm();
	if(isset($_POST["submit"])) {
		if(checkImg()){
		$product_id = insertProduct();
		uploadImg($product_id);
		}
			
	}
	}
}
add_shortcode('displayAddProductForm', 'displayAddProduct');

function insertProduct(){
	global $wpdb;
	if(isset($_POST['submit'])){
		$title = $_POST['shortDescription'];
		$description = $_POST['Description'];
		$price = $_POST['price'];
		$tradeOffers = $_POST['tradeOffers'];
		$table = getEmailSuffix();
		$user = wp_get_current_user();
		$email = $user->user_email;
		$addToMarketplace = false;
		$addToTradeplace = false;
		
		if(isset($_POST['addToOtherPage'])){
			$addToTradeplace = true;
			$addToMarketplace = true;
		}else{
			if(getPageName() == 'marketplace'){
				$addToMarketplace = true;
			}else{
				$addToTradeplace = true;
			}
		}
		
		$sql = $wpdb->prepare(
		"INSERT INTO $table
		(item_name, item_description, price, seller, marketplace, tradeplace, trade_offers)
		VALUES ('%s','%s','%f','%s','%d','%d','%s')", $title, $description, $price, $email, $addToMarketplace, $addToTradeplace, $tradeOffers);
		$wpdb->query($sql);
		
		return $wpdb->insert_id;
	}
	
}


?>