<?php
/**
 * Error Messages
 *
 *
 * @version 1
 */
defined( 'ABSPATH' ) || exit;
do_action( 'before_edit_account_form' ); 


function displayLogInWithUniversityEmailMessage(){
	if(!isStudentEmail()){
	return '<div style="color: red;"><p>Please log in with your university 
	email to access the local marketplace.</p></div>';
	}
}

?>