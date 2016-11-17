<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage AgriFlex3
 */

// Remove search result meta data
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_content', 'genesis_do_post_permalink', 14 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

// Restrict excerpt length
add_filter( 'excerpt_length', 'sp_excerpt_length' );
function sp_excerpt_length( $length ) {
	return 50;
}

// Ensure search results use excerpts
add_filter( 'genesis_pre_get_option_content_archive', 'show_excerpts' );
function show_excerpts() {
	return 'excerpts';
}

// Add search results page title
add_action( 'genesis_before_loop', function() {

  $title = sprintf( '<div class="entry-header"><h1 class="entry-title">Search Results for: %s</h1></div>',
    get_search_query()
  );

  echo $title;

});

// Format search result information
function filter_function_name( $excerpt ) {

	$type = get_post_type();
	if($type == 'people'){
		// People post types don't have an excerpt, so it's built here.
		// Based on single-people.php from AgriLife People plugin.
		$parts = array();

		$title = get_field( 'ag-people-title' );
		if($title)
			$parts[] = 'Title: ' . $title;

		$office = get_field( 'ag-people-office-location' );
		if($office)
			$parts[] = 'Office: ' . $office;

		$email = get_field( 'ag-people-email' );
		if($email)
			$parts[] = 'Email: ' . $email;

		$phone = get_field( 'ag-people-phone' );
		if($phone)
			$parts[] = 'Phone: ' . $phone;

		$resume = get_field( 'ag-people-resume' );
		if($resume)
			$parts[] = 'Resume/CV: ' . $resume;

		$website = get_field( 'ag-people-website' );
		if($website)
			$parts[] = $website;

		$undergrad_items = array();
    if ( get_field( 'ag-people-undergrad' ) ) {
        while ( has_sub_field( 'ag-people-undergrad' ) ) :
            $undergrad_items[] = get_sub_field( 'ag-people-undergrad-degree' );
        endwhile;
    }
    $undergrad = implode(',', $undergrad_items);
		if(!empty($undergrad))
			$parts[] = 'Undergraduate Education: ' . $undergrad;

    $grad_items = array();
    if ( get_field( 'ag-people-graduate' ) ) {
        while ( has_sub_field( 'ag-people-graduate' ) ) :
            $grad_items[] = get_sub_field( 'ag-people-graduate-degree' );
        endwhile;
    }
    $grad = implode(',', $grad_items);
		if(!empty($grad))
			$parts[] = 'Graduate Education: ' . $grad;

    $award_items = array();
    if ( get_field( 'ag-people-awards' ) ) {
        while ( has_sub_field( 'ag-people-awards' ) ) :
            $award_items[] = get_sub_field( 'ag-people-award' );
        endwhile;
    }
    $award = implode(', ', $award_items);
		if(!empty($award))
			$parts[] = 'Awards: ' . $award;

    $taught_items = array();
    if ( get_field( 'ag-people-courses' ) ) {
        while ( has_sub_field( 'ag-people-courses' ) ) :
            $taught_items[] = get_sub_field( 'ag-people-course' );
        endwhile;
    }
    $taught = implode(', ', $taught_items);
		if(!empty($taught))
			$parts[] = 'Courses Taught: ' . $taught;

    $content_items = array();
    while ( has_sub_field( 'ag-people-content' ) ) :
        $layout = get_row_layout();
        switch( $layout ) {
            case 'ag-people-content-header' :
                $content_items[] = get_sub_field( 'header' );
                break;
            case 'ag-people-content-text' :
                $content_items[] = get_sub_field( 'text' );
                break;
            default :
            		break;
        }
    endwhile;
    $content = implode( ', ', $content_items );
		if( !empty($content) )
			$parts[] = $content;

		// Concatenate people data to string
		$excerpt = implode( '; ', $parts );
		// Remove HTML
		$excerpt = wp_strip_all_tags( $excerpt );
		// Combine all people data for excerpt
		$excerpt = preg_replace( '/ ;/', ';', $excerpt );
		// Restrict word count
		$excerpt = wp_trim_words( $excerpt, 50 );

	}

	// Replace special and excess whitespace characters with normal spaces
	$excerpt = str_replace( '&nbsp;', ' ', $excerpt );
	$excerpt = preg_replace( '/[\s\t\r\n\v]+/', ' ', $excerpt );
	$excerpt = preg_replace( '/[^\w\d,.;"\')]?\h\s/', ' ', $excerpt );

	// Ensure read more link is present and uses a class name for the anchor tag
	$hasreadmore = preg_match('/(<span[^>]*class="read-more"[^>]*><a)([^>]*)(>[^<]*<\/a>)<\/span>/', $excerpt, $readmore);
	if( $hasreadmore ){
		$readmoreatts = $readmore[1] . ' class="read-more-link"' . $readmore[2] . $readmore[3];
		$excerpt = str_replace( $readmore[0], $readmoreatts, $excerpt );
	} else {
		$excerpt .= '<span class="read-more"><a class="read-more-link" href="' . get_permalink() . '">Read More →</a></span>';
	}

  return $excerpt;
}
add_filter( 'get_the_excerpt', 'filter_function_name' );

genesis();
