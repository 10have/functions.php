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
