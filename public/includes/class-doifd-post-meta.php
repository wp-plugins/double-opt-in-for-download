<?php

class DoifdPostMeta {

    public function __construct() {

        add_action('load-post.php', array($this, 'smashing_post_meta_boxes_setup'));
        add_action('load-post-new.php', array($this, 'smashing_post_meta_boxes_setup'));
        
    }

    /* Meta box setup function. */

    public function smashing_post_meta_boxes_setup() {

        /* Add meta boxes on the 'add_meta_boxes' hook. */
        add_action('add_meta_boxes', array($this, 'smashing_add_post_meta_boxes'));
        
        add_action( 'save_post', array( $this, 'smashing_save_post_class_meta' ), 10, 2 );
    }

    public function smashing_add_post_meta_boxes() {

        add_meta_box(
                'smashing-post-class', // Unique ID
                esc_html__('Post Class', 'example'), // Title
                array( $this, 'smashing_post_class_meta_box' ), // Callback function
                'doifd_download', // Admin page (or post type)
                'side', // Context
                'default'         // Priority
        );
    }

    /* Display the post meta box. */

    public function smashing_post_class_meta_box($object, $box) {
        ?>

        <?php wp_nonce_field(basename(__FILE__), 'smashing_post_class_nonce'); ?>

        <p>
            <label for="smashing-post-class"><?php _e("Add a custom CSS class, which will be applied to WordPress' post class.", 'example'); ?></label>
            <br />
            <input class="widefat" type="text" name="smashing-post-class" id="smashing-post-class" value="<?php echo esc_attr(get_post_meta($object->ID, 'smashing_post_class', true)); ?>" size="30" />
        </p>
    <?php
    }
    
    /* Save the meta box's post metadata. */
function smashing_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['smashing_post_class_nonce'] ) || !wp_verify_nonce( $_POST['smashing_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['smashing-post-class'] ) ? sanitize_html_class( $_POST['smashing-post-class'] ) : '' );

  /* Get the meta key. */
  $meta_key = 'smashing_post_class';

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
