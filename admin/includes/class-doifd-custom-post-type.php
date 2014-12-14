<?php

class DoifdCustomPostType {

    public function __construct() {

        add_action('init', array($this, 'create_download_post_type'));
        add_action('init', array($this, 'register_download_taxonomy'));
        add_action('manage_doifd_download_posts_custom_column', array($this, 'download_custom_columns'));

        add_filter("manage_edit-doifd_download_columns", array($this, "download_edit_columns"));
        add_filter('post_updated_messages', array($this, 'doifd_post_updated_messages'));

        $plugin = DOIFD::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
    }

    public function create_download_post_type() {
        $args = array(
            'description' => __('Downloads', $this->plugin_slug),
            'show_ui' => true,
            'show_in_menu' => false,
            'exclude_from_search' => true,
            'labels' => array(
                'name' => __('Downloads', $this->plugin_slug),
                'singular_name' => __('Download', $this->plugin_slug),
                'add_new' => __('Add New Download', $this->plugin_slug),
                'add_new_item' => __('Add New Download', $this->plugin_slug),
                'edit' => __('Edit Download', $this->plugin_slug),
                'edit_item' => __('Edit Download', $this->plugin_slug),
                'new-item' => __('New Download', $this->plugin_slug),
                'view' => __('View Downloads', $this->plugin_slug),
                'view_item' => __('View Download', $this->plugin_slug),
                'search_items' => __('Search Downloads', $this->plugin_slug),
                'not_found' => __('No Downloads Found', $this->plugin_slug),
                'not_found_in_trash' => __('No Downloads Found in Trash', $this->plugin_slug),
                'parent' => __('Parent Download', $this->plugin_slug)
            ),
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => true,
            'supports' => array( 'title', 'author' )
        );
        register_post_type('doifd', $args);
    }

    /* ====================================================
      Register Custom Taxonomies
      ====================================================== */

    function register_download_taxonomy() {

        $args = array(
            'labels' => array(
                'name' => __('Download Categories', $this->plugin_slug),
                'singular_name' => __('Download Categories', $this->plugin_slug),
                'search_items' => __('Search Download Categories', $this->plugin_slug),
                'popular_items' => __('Popular Download Categories', $this->plugin_slug),
                'all_items' => __('All Download Categories', $this->plugin_slug),
                'parent_item' => __('Parent Download Category', $this->plugin_slug),
                'parent_item_colon' => __('Parent Download Category:', $this->plugin_slug),
                'edit_item' => __('Edit Download Category', $this->plugin_slug),
                'update_item' => __('Update Download Category', $this->plugin_slug),
                'add_new_item' => __('Add New Download Category', $this->plugin_slug),
                'new_item_name' => __('New Download Category', $this->plugin_slug),
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'rewrite' => false,
            'public' => true
        );

        register_taxonomy('doifd_download_category', 'doifd', $args
        );
    }

    /* ====================================================
      Edit/Add Table Columns
      ====================================================== */

    public function download_edit_columns($columns) {
        $columns = array(
            'title' => __('Name', $this->plugin_slug),
            'doifd_download_category' => __('Category', $this->plugin_slug),
            'doifd_landing_page' => __('Landing Page', $this->plugin_slug),
            'doifd_download_shortcode' => __('Shortcode', $this->plugin_slug),
            'total_downloads' => __('Downloads', $this->plugin_slug),
            'date' => __('Date', $this->plugin_slug)
        );

        return $columns;
    }

    /* ====================================================
      Get values for custom table fields
      ====================================================== */

    public function download_custom_columns($column) {

        global $post;

        switch ($column) {
            case "doifd_download_shortcode":
                echo 'Shortcode';
                break;
            case 'doifd_landing_page':
                echo 'Landing Page';
                break;
            case 'total_downloads':
                echo 'Total Downloads';
                break;
            case "doifd_download_category":
                echo get_the_term_list($post->ID, 'doifd_download_category', '', ', ', '');
                break;
        }
    }

    public function doifd_post_updated_messages($messages) {

        global $post, $post_ID;

        $messages['doifd_download'] = array(
            0 => '',
            1 => sprintf(__('Download updated. <a href="%s">View product</a>'), esc_url(get_permalink($post_ID))),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __('Download updated.'),
            5 => isset($_GET['revision']) ? sprintf(__('Download restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            6 => sprintf(__('Download published. <a href="%s">View product</a>'), esc_url(get_permalink($post_ID))),
            7 => __('Download saved.'),
            8 => sprintf(__('Download submitted. <a target="_blank" href="%s">Preview product</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
            9 => sprintf(__('Download scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>'), date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
            10 => sprintf(__('Download draft updated. <a target="_blank" href="%s">Preview product</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
        );
        return $messages;
    }


}

new DoifdCustomPostType();
