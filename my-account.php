<?php
/**
 * My Account page
 *
 *
 * @version 1
 */
defined( 'ABSPATH' ) || exit;

function displayMyAccForm($displayName, $firstName, $lastName){
	$user = wp_get_current_user();
	if(!is_user_logged_in()){
		redirectToLogin();
	}else{
		echo '<div style="width: 500px;margin: 0 auto;"><form action="" method="post">
  Display name:<br>
  <input type="text" name="displayname" value="' . esc_html($displayName) . '">
  <br>
  First name:<br>
  <input type="text" name="firstname" value="' . esc_html($firstName) . '">
  <br>
  Last name:<br>
  <input type="text" name="lastname" value="' . esc_html($lastName) . '">
  <br>
  Email:<br>
  <input type="text" name="email" value="' . esc_html($user->user_email) . '" style ="color:Gray;" readonly>
  ' . displayLogInWithUniversityEmailMessage() . 
  '<br><br>
  <input type="submit" name="submit" value="Submit">
  </form></div> ';
	}
}

function displayMyAcc(){
	if(isset($_POST["submit"])) {
		$displayName =  $_POST['displayname'];
		$firstName = $_POST['firstname'];
		$lastName = $_POST['lastname'];
		$args = array(
                'ID'         =>  get_current_user_id(),
				'first_name' => $firstName,
                'display_name' => $displayName,
			    'last_name' => $lastName
            );            
        wp_update_user( $args );
		displayMyAccForm($displayName, $firstName, $lastName);
														 
	}else{
		$user = wp_get_current_user();
		displayMyAccForm($user->display_name, $user->first_name, $user->last_name);
	}
}
add_shortcode( 'displayMyAccountForm', 'displayMyAcc');

function redirectToLogin() {
    wp_redirect('http://35.236.220.19/wp-admin', 301); //FIX THIS LATER
	exit;
}
	
?>