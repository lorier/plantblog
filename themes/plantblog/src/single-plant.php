<?php

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );


genesis();