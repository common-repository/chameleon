<?php

	register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'twentytwentyone' ),
		'id'            => 'sidebar-right',
		'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'twentytwentyone' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	) );
	


	add_filter( 'the_content', 'the_content_twentytwentyone_covid19' );
	function the_content_twentytwentyone_covid19( $content ) {
		//pree($content);
		ob_start();
		if ( is_active_sidebar( 'sidebar-right' ) ) :
?>		
			<ul id="sidebar-right">
				<?php dynamic_sidebar( 'sidebar-right' ); ?>
			</ul>
<?php			
		endif;		
		$content = '<div class="entry-content-left">'.$content.'</div><div class="entry-content-right">'.ob_get_contents().'</div>';
		ob_end_clean();
		//pree($content);
		return $content;
	}	