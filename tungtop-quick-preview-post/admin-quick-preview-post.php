<?php
/**
 * Plugin Name: Admin Quick Preview Post
 * Plugin URI: https://tungtop.com/
 * Description: Admin Quick Preview Post
 * Version: 1.2
 * Author: TungTop
 * Author URI: tungtop.com
 * License: GPLv2
 */

register_activation_hook( __FILE__, 'aqpp_insert_activation' );
function aqpp_insert_activation() {}

register_deactivation_hook(__FILE__, 'aqpp_delete_activation');
function aqpp_delete_activation(){}

if ( ! function_exists( 'aqpp_add_preview_action' ) ) {
    function aqpp_add_preview_action($actions, $post) {
        $preview_link = get_preview_post_link($post);
        $actions['quick_preview'] = "<a href='javascript:void(0)' data-preview_link='".$preview_link."' class='preview_link_action'>Quick Preview</a>";
        return $actions;
    }
    add_filter('post_row_actions', 'aqpp_add_preview_action', 10, 2);
}

// Add Google Tag code which is supposed to be placed after opening body tag.

if ( ! function_exists( 'aqpp_add_modal_iframe' ) ) {
    function aqpp_add_modal_iframe() {
        // check right post management position to add modal html
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (strpos($actual_link, 'wp-admin/edit.php') !== false || strpos($actual_link, 'wp-admin/post.php') !== false) { ?>
            <div class="aqpp_modal" id="aqpp_modal_preview">
                <label class="aqpp_modal__bg" for="modal-1"></label>
                <div class="aqpp_modal__inner">
                <label class="aqpp_modal__close" for="modal-1"></label>
                <iframe src="" width="0" height="0" style="margin-top: 20px;"></iframe>
                </div>
            </div>
        <?php }
    }
    add_action( 'admin_footer', 'aqpp_add_modal_iframe' );
}

if ( ! function_exists( 'aqpp_add_custom_preview_button_edit_post_page' ) ) {
    function aqpp_add_custom_preview_button_edit_post_page($post)
    {
        $preview_link = get_preview_post_link($post);
        ?>
            <script type="text/javascript">
                jQuery(document).ready( function($)
                {   
                    var previewLink = "<?php echo $preview_link; ?>";
                    var aElement = jQuery(jQuery("#preview-action .preview")[0]).clone();
                    jQuery(jQuery("#preview-action .preview")[0]).hide();
                    
                    aElement.attr("href", "javascript:void(0)");
                    aElement.attr("data-preview_link", previewLink);
                    aElement.addClass("preview_link_action");
                    aElement.text("Quick Preview");
                    aElement.removeAttr("target");
                    jQuery(jQuery("#preview-action")[0]).append(aElement);
                });
            </script>
        <?php    
    }
}

if ( function_exists( 'aqpp_add_custom_preview_button_edit_post_page' ) ) {
    add_action( 'post_submitbox_misc_actions', 'aqpp_add_custom_preview_button_edit_post_page' );
}

if ( ! function_exists( 'aqpp_add_style' ) ) {
    function aqpp_add_style(){
        wp_enqueue_script(array('jquery','jquery-ui-core','jquery-ui-dialog' ));

        wp_register_style( 'aqpp-model-preview', plugins_url( 'css/', __FILE__ ) . 'style.css', array(), time());
        wp_enqueue_style( 'aqpp-model-preview');

        wp_register_script( 'aqpp-my-js', plugins_url( 'js/', __FILE__ ) . 'my.js', array(), '1.0', true );
        wp_enqueue_script( 'aqpp-my-js' );
    }
    add_action('admin_init', 'aqpp_add_style');
}

