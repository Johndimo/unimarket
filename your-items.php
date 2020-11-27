<?php
/**
 * Your Items page
 *
 *
 * @version 1
 */
defined( 'ABSPATH' ) || exit;

function displayYourItems(){
	if(isStudentEmail()){
		getRecentProductsByEmail();
	}else{
   		displayLogInWithUniversityEmailMessage();
   	}
}
add_shortcode('displayYourItems', 'displayYourItems');

function getRecentProductsByEmail(){
	      if (isset($_GET['pageno'])) {
               $pageno = $_GET['pageno'];
           } else {
               $pageno = 1;
           }
	echo '<div><h2 style ="text-align: center; margin-top: 25px;">Your Items</h2></div>';
           $no_of_records_per_page = 10;
   	global $wpdb;
   	  $res = $wpdb->get_var("SELECT COUNT(*) FROM vanderbilt");
           
           $total_pages = ceil($res / $no_of_records_per_page);
   	global $wpdb;
   	$table = getEmailSuffix();
	
	$user = wp_get_current_user(); 
	$email = $user->user_email;
   	$sql = "SELECT item_name, item_description, price, seller, product_id FROM vanderbilt WHERE seller = '" . $email . "' ORDER BY product_id DESC LIMIT 10";
   	$results = $wpdb->get_results($sql);
   	foreach($results as $row){
   		 echo printProductDisplay($row->item_name, $row->price, $row->seller, $row->product_id, true, false);
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



?>