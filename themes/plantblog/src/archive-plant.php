<?php
/**
 * Kickstart Pro
 *
 * @author  Lean Themes
 * @license GPL-2.0+
 * @link    http://demo.leanthemes.co/kickstart/
 */
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');

}
genesis();