// Excerpt voor pagina's
// Extra opties voor een pagina mbt zoekresultaten
add_action( 'init', 'my_add_excerpts_to_pages' );
	function my_add_excerpts_to_pages() {
    	add_post_type_support( 'page', 'excerpt' );
}


// verwijder versie nummer in code
 remove_action('wp_head', 'wp_generator');
 
 function shortcode_breadcrumb( $atts ){
  blade_grve_print_breadcrumbs();
}
add_shortcode( 'breadcrumb', 'shortcode_breadcrumb' );

add_action('wp_footer', 'rth_add_typekit');
function rth_add_typekit() { ?>
<script src="https://use.typekit.net/XXX.js"></script>
<script>try{Typekit.load({ async: true });}catch(e){}</script>
<?php }



// TopMenu SelectBox
class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth){
		$indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
	}
	function end_lvl(&$output, $depth){
		$indent = str_repeat("\t", $depth); // don't output children closing tag
	}
	/**
	* Start the element output.
	*
	* @param  string $output Passed by reference. Used to append additional content.
	* @param  object $item   Menu item data object.
	* @param  int $depth     Depth of menu item. May be used for padding.
	* @param  array $args    Additional strings.
	* @return void
	*/
	function start_el(&$output, $item, $depth, $args) {
		
		
 		$url = '#' !== $item->url ? $item->url : '';
		
		$top_page_url = get_permalink( end( get_ancestors( get_the_ID(), 'page' ) ) );	


// DEBUG
// echo $top_page_url . "<small><strong> = [Toppage] | </strong>";
// echo $url . "<strong> = [URL]</strong><br></small>";
	
		if($url == $top_page_url) {
			$selected = "selected";
				} else {
			$selected = "";
		}
			
 		$output .= '<option value="' . $url . '"  ' .$selected. '>' . $item->title;
	}	
	
	function end_el(&$output, $item, $depth){
		$output .= "</option>\n"; // replace closing </li> with the option tag
	}
}


function be_mobile_menu() {
	wp_nav_menu( array(
		'theme_location' => 'grve_top_right_nav',
		'walker'         => new Walker_Nav_Menu_Dropdown(),
		'items_wrap'     => '<div class="mobile-doelgroep-menu"><form><select name="doelgroep" class="minimal" onchange="if (this.value) window.location.href=this.value">%3$s</select></form></div>',
	) );	
}
add_action( 'doelgroep_nav_footer_responsive', 'be_mobile_menu' );



// Last seen - Cookie
//
function wpb_lastvisit_the_title ( $title, $id ) {

if ( !in_the_loop() || is_singular() || get_post_type( $id ) == 'page' ) return $title;

// if no cookie then just return the title 

if ( !isset($_COOKIE['lastvisit']) ||  $_COOKIE['lastvisit'] == '' ) return $title;
$lastvisit = $_COOKIE['lastvisit'];
$publish_date = get_post_time( 'U', true, $id );
if ($publish_date > $lastvisit) $title .= '<span class="new-article">New</span>';
return $title;
 
}

add_filter( 'cookie', 'wpb_lastvisit_the_title', 10, 2);
 
// Set the lastvisit cookie 

function wpb_lastvisit_set_cookie() {

if ( is_admin() ) return;
$current = current_time( 'timestamp', 1);
setcookie( 'lastvisit', $current, time()+60+60*24*7, COOKIEPATH, COOKIE_DOMAIN );
}

add_action( 'init', 'wpb_lastvisit_set_cookie' );




////////////////////////////////////////////////////////////////////////
// Plaats m2 enkel achter de tegelprijs obv categorie
// RtH 28-4-2017

function rth_mpc_prijs( $price ) {

	if ( ! class_exists( 'WC_Price_Calculator_Product' ) ) {
		return;
	}

	global $product;
	$measurement = WC_Price_Calculator_Product::calculator_enabled( $product );
	
	if ( $measurement ) {
		
$textafter = '<span class="m2"> /m<sup>2</sup></span> '; //tekst achter prijs
return $price . '' . $textafter . '';

	}

else {
$textafter = ''; //tekst achter prijs
return $price . '' . $textafter . '';
}

}
add_filter( 'woocommerce_get_price_html', 'rth_mpc_prijs');
///////////////////////////////////////////////////////////////////////////////////////////////


