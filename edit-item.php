<?php
/**
 * Edit Item page
 *
 *  TO BE FIXED: INSERTS PRODUCT INSTEAD OF CHANGING IT.
 * @version 1
 */
defined( 'ABSPATH' ) || exit;

function displayEditProductForm(){
	$user = wp_get_current_user();
	echo '<div><h2 style ="text-align: center; margin-top: 25px; margin-bottom: 25px;">Edit Item</h2></div><div style="margin: 0 auto; width:500px;"><form action="" method="post" id="productForm" enctype="multipart/form-data">
  Short Description:<font color="red"> *</font><br>
  <input type="text" name="shortDescription" placeholder="Provide a short description of the item" style="width: 600px;" maxlength = "120" value="' . getField(getProductId(), 'item_name') . '"required>
  <br>
  Description:<font color="red"> *</font><br>
  <textarea name="Description" form="productForm" placeholder="Enter complete item description" required>' . getField(getProductId(), 'item_description') . '</textarea>
  <br>
  Price:<font color="red"> *</font><br>
  <input type="number" step="0.01" name="price" value="' . getField(getProductId(), 'price') . '" placeholder="e.g. 12.00" style="width:100px;" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 46))"required>  <i class="fas fa-dollar-sign"
  style="font-size: 17px;"></i>
    <br><br>
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
  <input type="submit" name="submit" value="Save Changes">
  </form></div>';
}

function displayEditItem(){
	if(isStudentEmail() && null !== getProductId()){
		displayEditProductForm();
			if(isset($_POST['submit'])){
				insertProduct();
			}

	}else{
		status_header( 404 );
 	    get_template_part( 404 ); exit();
	}
}
add_shortcode('displayEditProductForm', 'displayEditItem');


?>