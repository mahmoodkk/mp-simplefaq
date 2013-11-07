<?php
/*
Plugin Name: MP Simple Faq
Plugin URL: http://wordpress.com/mp-simplefaq
Description: A simple FAQ plugin
Version: 1.0
Author: Mahmood Rehman
Author URI: http://mahmood-rehman.com
*/

/*
 * Register mp_simplefaq
 *
 */
function mp_simplefaq_setup_post_types() {

	$faq_labels =  apply_filters( 'mp_simplefaq_labels', array(
		'name'                => 'FAQs',
		'singular_name'       => 'FAQ',
		'add_new'             => __('Add New', 'mp_simplefaq'),
		'add_new_item'        => __('Add New FAQ', 'mp_simplefaq'),
		'edit_item'           => __('Edit FAQ', 'mp_simplefaq'),
		'new_item'            => __('New FAQ', 'mp_simplefaq'),
		'all_items'           => __('All FAQs', 'mp_simplefaq'),
		'view_item'           => __('View FAQ', 'mp_simplefaq'),
		'search_items'        => __('Search FAQs', 'mp_simplefaq'),
		'not_found'           => __('No FAQs found', 'mp_simplefaq'),
		'not_found_in_trash'  => __('No FAQs found in Trash', 'mp_simplefaq'),
		'parent_item_colon'   => '',
		'menu_name'           => __('FAQs', 'mp_simplefaq'),
		'exclude_from_search' => true
	) );


	$faq_args = array(
		'labels' 			=> $faq_labels,
		'public' 			=> true,
		'publicly_queryable'=> true,
		'show_ui' 			=> true,
		'show_in_menu' 		=> true,
		'query_var' 		=> true,
		'capability_type' 	=> 'post',
		'has_archive' 		=> false,
		'hierarchical' 		=> false,
		'supports' 			=> apply_filters('mp_simplefaq_supports', array( 'title', 'editor' ) ),
	);
	register_post_type( 'mp_simplefaq', apply_filters( 'mp_simplefaq_post_type_args', $faq_args ) );

}

add_action('init', 'mp_simplefaq_setup_post_types');
add_action('init', 'mp_simplefaq_setup_post_types');


/*
 * Add [mp_simplefaq limit="-1"] shortcode
 *
 */
function mp_simplefaq_shortcode( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
		"limit" => ''
	), $atts));
	
	// Define limit
	if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '-1';
	}
	
	ob_start();

	// Create the Query
	$post_type 		= 'mp_simplefaq';
	$orderby 		= 'menu_order';
	$order 			= 'ASC';
				
	$query = new WP_Query( array ( 
								'post_type'      => $post_type,
								'posts_per_page' => $posts_per_page,
								'orderby'        => $orderby, 
								'order'          => $order,
								'no_found_rows'  => 1
								) 
						);
	
	//Get post type count
	$post_count = $query->post_count;
	$i = 1;
	
	// Displays FAQ info
	if( $post_count > 0) :
	
		// Loop
		while ($query->have_posts()) : $query->the_post();
		?>
		
		<h3 class="mp_simplefaq_title"><a href="#" onclick="mp_simplefaq_toggle('mp_simplefaq_<?php echo get_the_ID(); ?>');"><?php the_title(); ?></a></h3>
		<p id="mp_simplefaq_<?php echo get_the_ID(); ?>" style="display: none;"><?php echo get_the_content(); ?></p>

		<?php
		$i++;
		endwhile;
		
	endif;
	
	// Reset query to prevent conflicts
	wp_reset_query();
	
	?>
	<script type="text/javascript">
	<!--
	    function mp_simplefaq_toggle(id) {
			var e = document.getElementById(id);
			e.style.display = ((e.style.display!='none') ? 'none' : 'block');
		}
	//-->
	</script>
	<?php
	
	return ob_get_clean();

}

add_shortcode("mp_simplefaq", "mp_simplefaq_shortcode");