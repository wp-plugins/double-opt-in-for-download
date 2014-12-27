<?php

class DoifdPostMeta {

    public function __construct() {

        add_action('load-post.php', array($this, 'doifd_post_meta_boxes_setup') );
        add_action('load-post-new.php', array($this, 'doifd_post_meta_boxes_setup') );
        
        $plugin = DOIFD::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
        
    }

    /* Meta box setup function. */

    public function doifd_post_meta_boxes_setup() {

        /* Add meta boxes on the 'add_meta_boxes' hook. */
        add_action('add_meta_boxes', array( $this, 'doifd_add_post_meta_boxes') );
        
        add_action( 'save_post', array( $this, 'doifd_save_post_class_meta' ), 10, 2 );
    }

    public function doifd_add_post_meta_boxes() {

        add_meta_box(
                'doifd-post-class-name', // Unique ID
                esc_html__('Form Class', $this->plugin_slug ), // Title
                array( $this, 'doifd_post_class_name_meta_box' ), // Callback function
                'doifd', // Admin page (or post type)
                'normal', // Context
                'default' // Priority
        );
        
        add_meta_box(
                'doifd-post-download-file', // Unique ID
                esc_html__('Add your Download File', $this->plugin_slug ), // Title
                array( $this, 'doifd_post_download_file_meta_box' ), // Callback function
                'doifd', // Admin page (or post type)
                'normal', // Context
                'default',// Priority
                'doifd_post_download_file_nonce'
        );
    }

    /* Display the post class name meta box. */

    public function doifd_post_class_name_meta_box($object, $box) {
        ?>

        <?php wp_nonce_field(basename(__FILE__), 'doifd_post_class_name_nonce'); ?>

        <p>
            <label for="doifd-post-class-name"><?php _e("Add a custom CSS class name for your form. ( Example: myClassName )", $this->plugin_slug ); ?></label>
            <br />
            <input class="widefat" type="text" name="doifd-post-class-name" id="doifd-post-class-name" value="<?php echo esc_attr(get_post_meta($object->ID, 'doifd_post_class_name', true)); ?>" size="30" />
        </p>
    <?php
    }
    
     /* Display the post class name meta box. */

    public function doifd_post_download_file_meta_box($object, $box) {
        

        wp_nonce_field(basename(__FILE__), 'doifd_post_download_file_nonce');

        ?>
        
        <div id="doifd_download_file_url">
        <input type="button" class="media-button button" name="custom_image_btn" value="Choose Download" />
        <input type="text" size="40" id="doifd_post_download_file" name="doifd-post-download-file" value="<?php echo esc_attr(get_post_meta($object->ID, 'doifd_post_download_file', true)); ?>" class="large-text" />
        <p><?php __( 'Add your download file.', $this->plugin_slug ) ?></p>
        </div>
        
            <?php
    }
    
    /* Save the meta box's post metadata. */
function doifd_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['doifd_post_class_name_nonce'] ) || !wp_verify_nonce( $_POST['doifd_post_class_name_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['doifd-post-class-name'] ) ? sanitize_html_class( $_POST['doifd-post-class-name'] ) : '' );

  /* Get the meta key. */
  $meta_key = 'doifd_post_class_name';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
  
  $new_meta_value = ( isset( $_POST['doifd-post-download-file'] ) ? esc_url_raw( $_POST['doifd-post-download-file'] ) : '' );

  /* Get the meta key. */
  $meta_key = 'doifd_post_download_file';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );

}



}

new DoifdPostMeta();
