<?php
   /**
    * Marketplace page
    *
    *
    * @version 1
    */
   defined( 'ABSPATH' ) || exit;
   include('add-product.php');
   
   function printTopBar(){
   	if(isStudentEmail()){
   	echo printSearchBar();
   	echo printAddProductButton();
   	echo printSideBar();
	if(isset($_GET["searchButton"]) && !empty($_GET["searchtext"])) {
		echo "<div id='searchedProduct'>";
		echo searchProducts();
		echo "</div>";
	}else{
		echo "<div id='recentProducts'>";
		getRecentProducts(basename(get_permalink()));
		echo "</div>";
	}

   	}else{
   		displayLogInWithUniversityEmailMessage();
   	}
   }
   add_shortcode('printAddProductButton', 'printTopBar');
   
   function printSearchBar(){
   	return '<div style="display: inline-block; margin-left: 500px;"><form action="" method="GET">
   <input id="search" name="searchtext" type="text" placeholder="Search here" style="width: 300px;">  
   <button type="submit" name="searchButton">
       <i class="fas fa-search"></i>
   </button>
   </form></div>
   <script>
function myFunction(e) {
     document.getElementById("search").addEventListener("keypress", function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
			document.getElementById("searchButton").click();
        }
    });
</script>';
   }
   
   function printAddProductButton(){
	   $slug = basename(get_permalink());
   	return '<div style="display: inline-block; margin-left: 300px;"><a href="' . esc_url(get_permalink(get_page_by_title('Add Item'))) . "?page=" . $slug . '"><button>Add product</button></a></div>	<hr style="background-color:#ffca04; position: relative;margin-left:350px;">
   ';
   	
   }
   
   function printSideBar(){

   	return '<div style="margin-left: 25px;height: 100%;width: 300px; position: absolute;"><div style="font-size: 21px;">Categories: 
	</div><form><br><input type="radio" id="books" name="categories" value="Books" style=""> Books
	<br>
	<input type="radio" name="categories" value="Bikes" style=""> Bikes
	<br>
	<input type="radio" name="categories" value="Room Related" style=""> Room Related
	<br>
	</form>
	<br> <br>
	<div style="font-size: 21px;"> Price: </div>
	<form><br><input type="radio" name="price" value="0-50" style=""> $ 0 - 50
	<br>
	<input type="radio" name="price" value="50-100" style=""> $ 50 - 100
	<br>
	<input type="radio" name="price" value="100+" style=""> $ 100+
	<br>
	</form></div>
	<script>var myRadios = document.getElementsByName(\'categories\');
var setCheck;
var x = 0;
for(x = 0; x < myRadios.length; x++){

    myRadios[x].onclick = function(){
        if(setCheck != this){
             setCheck = this;
        }else{
            this.checked = false;
            setCheck = null;
    }
    };

}</script>
	';
   }
   
   function printProductDisplay($shortDesc, $price, $seller, $product_id, $edit_button, $buyer_buttons){
   	$src = "/var/www/html/wp-content/plugins/Unimarket/pictures/vanderbilt/" . $product_id . "-";
   	$images = glob("/var/www/html/wp-content/plugins/Unimarket/pictures/vanderbilt/Ioannis.dimotsis@vanderbilt.edu/" . $product_id . "*.{{jpg,jpeg,png}}", GLOB_BRACE);
   	
   	  foreach($images as $image) {
    		$firstImg = $image;
   		  break;
   	  }
   	$path = str_replace( $_SERVER['DOCUMENT_ROOT'], $_SERVER['SERVER_NAME']
                                                               , $firstImg );
   	$path = 'http://' . $path;
   	$html = '<div style="margin-left: 350px;margin-top: 20px;">
   	<div style="position: relative; width: 300px; height: 300px; margin-left: 30px; top: 100px;"><a href="' . esc_url(get_permalink(get_page_by_title('display item'))) . "?product_id=" . $product_id . '"><img src="'. $path . '" style="max-width:100%;max-height:100%;display: block;position: absolute; bottom: 0;"/></a></div>
   	<div style="position: relative; top: -120px; margin-left: 400px; font-weight: bold; font-size: 21px;"><a href="' . esc_url(get_permalink(get_page_by_title('display item'))) . "?product_id=" . $product_id . '">' . $shortDesc . '</a></div>
   	<div style=" margin-left: 400px; position: relative; top: -120px;"><span style="font-size: 12px; position:relative; top: -3px;">$</span><span style="font-weight: bold; font-size: 19px;">' . $price . '</span></div>';
   	
	if($buyer_buttons){
	$html .= '<div style=" margin-left: 400px; style="font-size: 16px;"> uploaded by ' . $seller . '</div>
	<div style="margin-left: 700px;position:relative; top: -30px;"><button>Buy</button></div>
	<div style="margin-left: 780px;position:relative; top: -90px;"><button>Add to Watchlist</button></div>';
   }else{
	 $html .= '<div style=" margin-left: 400px; style="font-size: 16px;"> uploaded by you</div>';
   }
	   
	if($edit_button){
	  $html .= '<div style="margin-left: 940px;position: relative;font-size: 16px;top: -150px;"><a href="' . esc_url(get_permalink(get_page_by_title('Edit Item'))) . "?product_id=" . $product_id . '"><button>Edit item</button></a></div>';
	}

   	$html .= '<br>
   	<hr style="background-color:#ffca04; position: relative;">
   	</div>
   	<br>';
	   return $html;
   }
   
   function getField($product_id, $field){
       global $wpdb;
       $sql = "SELECT {$field} FROM vanderbilt WHERE product_id = {$product_id}";
       $result = $wpdb->get_var($sql);
   	return $result;
   }

   function getRecentProducts($page){
	      if (isset($_GET['pageno'])) {
               $pageno = $_GET['pageno'];
           } else {
               $pageno = 1;
           }
           $no_of_records_per_page = 10;
   	global $wpdb;
   	  $res = $wpdb->get_var("SELECT COUNT(*) FROM vanderbilt");
           
           $total_pages = ceil($res / $no_of_records_per_page);
   	global $wpdb;
   	$table = getEmailSuffix();
   	$sql = "SELECT item_name, item_description, price, seller, product_id FROM vanderbilt WHERE $page = 1 ORDER BY 				product_id DESC LIMIT 10";
   	$results = $wpdb->get_results($sql);
   	foreach($results as $row){
   		 echo printProductDisplay($row->item_name, $row->price, $row->seller, $row->product_id, false, true);
   	}
        $html = '<ul class="pagination">
           <li><a href="?pageno=1">First</a></li>
           <li class="';
   	    if($pageno <= 1){ $html .= "disabled"; } 
   	     $html .= '"><a href="';
   	     if($pageno <= 1){ $html .= "#"; } else { $html .= "?pageno=".($pageno - 1); } 
   	     $html .= '">Prev</a>
           </li>
           <li class="';
   		if($pageno >= $total_pages){ $html .= "disabled"; } 
   	    $html .= '">
               <a href="';
   			if($pageno >= $total_pages){ $html .=  "#"; } else { $html .= "?pageno=".($pageno + 1); } 
   	$html .= '">Next</a>
           </li>
           <li><a href="?pageno=';
   		$html .= $total_pages; 
   		$html .= '">Last</a></li>
       </ul>';
   		echo $html;
   }

	function searchProducts(){
		global $wpdb;
		    $search = "'%" . strip_tags( $_GET["searchtext"] ) . "%'";
	    	$sql = "SELECT * FROM vanderbilt WHERE item_name LIKE {$search} OR item_description LIKE {$search}";
        	$results = $wpdb->get_results($wpdb->prepare($sql));
		foreach($results as $row){
   		echo printProductDisplay($row->item_name, $row->price, $row->seller, $row->product_id, false, true);
   	}
		
	}
	
   
   ?>