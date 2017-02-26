<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_SearchOverride_Filter")) {
    /**
     * Class WD_ASL_SearchOverride_Filter
     *
     * Handles search override filters
     *
     * @class         WD_ASL_SearchOverride_Filter
     * @version       1.0
     * @package       AjaxSearchPro/Classes/Filters
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_SearchOverride_Filter extends WD_ASL_Filter_Abstract {

        public function handle() {}

        public function override($posts, $wp_query) {

            // Is this a search query
            if (!$wp_query->is_main_query() || !isset($wp_query->query_vars['s']) || !isset($_GET['s'])) {
                return $posts;
            }

            // If get method is used, then the cookies are not present
            if (isset($_GET['p_asl_data']) || isset($_GET['np_asl_data'])) {
                $_p_data = isset($_GET['p_asl_data']) ? $_GET['p_asl_data'] : $_GET['np_asl_data'];
                parse_str(base64_decode($_p_data), $s_data);

                /**
                 * At this point the asl_data cookie should hold the search data, if not, well then this
                 * is just a simple search query.
                 */
            } else if (
                isset($_COOKIE['asl_data'], $_COOKIE['asl_phrase']) &&
                $_COOKIE['asl_phrase'] == $_GET['s']
            ) {
                parse_str($_COOKIE['asl_data'], $s_data);
                $_POST['np_asl_data'] = $_COOKIE['asl_data'];
            } else {
                // Something is not right
                return $posts;
            }

            $_POST['options'] = $s_data;
            $_POST['options']['non_ajax_search'] = true;
            $_POST['aslp'] = $_GET['s'];
            $_POST['asl_get_as_array'] = 1;

            $o = WD_ASL_Search_Handler::getInstance();
            $res = $o->handle( true );

            if ( isset($_GET['paged']) ) {
                $paged = $_GET['paged'];
            } else if ( isset($wp_query->query_vars['paged']) ) {
                $paged = $wp_query->query_vars['paged'];
            } else {
                $paged = 1;
            }

            $paged = $paged <= 0 ? 1 : $paged;

            $posts_per_page = (int)get_option('posts_per_page');

            // Get and convert the results needed
            $n_posts = asl_results_to_wp_obj( $res, ( $paged - 1 ) * $posts_per_page, $posts_per_page );

            $wp_query->found_posts = count($res);
            if (($wp_query->found_posts / $posts_per_page) > 1)
                $wp_query->max_num_pages = ceil($wp_query->found_posts / $posts_per_page);
            else
                $wp_query->max_num_pages = 0;

            return $n_posts;
        }

        public function fixUrls( $url, $post, $leavename ) {
            if (isset($post->asl_guid))
                return $post->asl_guid;
            return $url;
        }

        // ------------------------------------------------------------
        //   ---------------- SINGLETON SPECIFIC --------------------
        // ------------------------------------------------------------
        public static function getInstance() {
            if ( ! ( self::$_instance instanceof self ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }
    }
}