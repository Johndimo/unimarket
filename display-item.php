<?php
/**
 * My Account page
 *
 *
 * @version 1
 */
defined( 'ABSPATH' ) || exit;

function getProductId(){
	if (isset($_GET["product_id"])){
	return $_GET["product_id"];
	}
return null;
}

function loadSlides($images){
	$count = 1;
	$html = '<br>';
	foreach($images as $image) {
 		$firstImg = $image;
		  $path = str_replace( $_SERVER['DOCUMENT_ROOT'], $_SERVER['SERVER_NAME']
                                                            , $firstImg );
	$path = 'http://' . $path;
	$html .= '<div class="Slides">
    <div class="numbertext">' . $count . '/' . sizeof($images). '</div>
    <img src="' . $path . '" style="width:100%">
  </div>';
		$count += 1;
	  }
	return $html;
}
function displaySlideShow($product_id){
	$src = "/var/www/html/wp-content/plugins/Unimarket/pictures/vanderbilt/" . $product_id . "-";
	$images = glob("/var/www/html/wp-content/plugins/Unimarket/pictures/vanderbilt/Ioannis.dimotsis@vanderbilt.edu/" . $product_id . "*.{{jpg,jpeg,png}}", GLOB_BRACE);
	
	
  return '<div class="slideshow" style=" margin-left: 30px;width: 500px;position:relative;margin-left: 100px;">' . loadSlides($images) . '
  <a class="prevArr" onclick="plusSlides(-1)">&#10094;</a>
  <a class="nextArr" onclick="plusSlides(1)">&#10095;</a>
  </div>'. displayCSSandJS();
	
}

function displayShortDesc(){
	return '<div style="margin-left: 700px;margin-top: 20px;"><div style="position: absolute; font-weight: bold; margin-top: 20px; font-size: 21px;">' . getField(getProductId(), 'item_name') . '</div>';
}
function displayPrice(){
	return '<div style="position: absolute; margin-top: 100px;"><span style="font-size: 12px; position:relative; top: -4px;">$</span><span style="font-weight: bold; font-size: 18px;">' . getField(getProductId(), 'price') . '</span></div>';
}
function displayDesc(){
	return '<div style="position: absolute; margin-top: 200px; font-size: 17px;">' . getField(getProductId(), 'item_description') . '</div>';
}
function displaySeller(){
	return '<div style="position: absolute;font-size: 16px; bottom:0px;"> uploaded by ' . getField(getProductId(), 'seller'). '</div>';
}
function displayEditButton($product_id){
	$user = wp_get_current_user();
	$email = $user->user_email;
	if(strcasecmp($email, getField($product_id, 'seller')) == 0){
		return '<div style="margin-left: 600px;position: absolute;font-size: 16px; bottom:0px;"><a href="' . esc_url(get_permalink(get_page_by_title('Edit Item'))) . "?product_id=" . $product_id . '"><button>Edit item</button></a></div></div>';
	}else{
		return '</div>'; //closing div
	}
}
function displayCSSandJS(){
	return '<style>
.prevArr, .nextArr {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  margin-top: -22px;
  padding: 16px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

.nextArr {
  right: 0;
  border-radius: 3px 0 0 3px;
}

.prevArr:hover, .nextArr:hover {
  background-color: rgba(0,0,0,0.8);
  color: white !important;
}
.numbertext {
  color: black;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 4%;
}
</style>
  <script>
    var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("Slides");
    if (n > slides.length) {
        slideIndex = 1
    }
    if (n < 1) {
        slideIndex = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slides[slideIndex - 1].style.display = "block";
} </script>';
}
function displayItem(){
	if(isStudentEmail() && null !== getProductId()){
		echo displayShortDesc();
		echo displayPrice();
		echo displayDesc();
		echo displaySeller();
		echo displayEditButton(getProductId());
		echo displaySlideShow(getProductId());
	}else if(isStudentEmail()){
		status_header( 404 );
 	    get_template_part( 404 ); exit();
	}else{
		displayLogInWithUniversityEmailMessage();
	}
}
add_shortcode('displayItem', 'displayItem');

?>