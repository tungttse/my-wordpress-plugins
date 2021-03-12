<?php
/**
 * Plugin Name: Daknetcorp Auto Tag Category
 * Plugin URI: https://tungtop.com/
 * Description: Daknetcorp Auto Tag Category
 * Version: 1.1
 * Author: TungTop
 * Author URI: tungtop.com
 * License: GPLv2
 */
include_once dirname( __FILE__ ) .'/daknetcorp-auto-tag-category-admin.php';
register_activation_hook( __FILE__, 'datc_insert_activation' );
register_deactivation_hook(__FILE__, 'datc_delete_activation');

register_deactivation_hook(__FILE__, 'datc_deactivation_cronjob');
register_activation_hook(__FILE__, 'datc_activation_cronjob');

function datc_insert_activation() {
    include_once( ABSPATH . 'wp-includes/wp-db.php' );
    global $wpdb;

    $tb_terms = $wpdb->prefix . 'terms'; 
    $tb_relationship = $wpdb->prefix . 'term_relationships';
    $tb_term_taxonomy= $wpdb->prefix . 'term_taxonomy';
    $tb_option       = $wpdb->prefix . 'options';

    // Insert category
    $term_id = $wpdb->get_row("SELECT `term_id` FROM `$tb_terms` WHERE `slug` = 'arranged_post_category_daknetcorp'");
    if($term_id->term_id == 0){
        $wpdb->insert(
            $tb_terms,
            array('name' => 'arranged_post_category_daknetcorp', 'slug' => 'arranged_post_category_daknetcorp'),
            array('%s', '%s')
        );

        $term_id_add = $wpdb->get_row("SELECT `term_id` FROM `$tb_terms` WHERE `slug` = 'arranged_post_category_daknetcorp'");
        $wpdb->insert(
            $tb_term_taxonomy,
            array('term_id' => $term_id_add->term_id, 'taxonomy' => 'arranged_postCategory_daknetcorp')
        );
    }

    //// Insert tag
    $term_id = $wpdb->get_row("SELECT `term_id` FROM `$tb_terms` WHERE `slug` = 'arranged_post_tag_daknetcorp'");
    if($term_id->term_id == 0 ){//|| is_null($parent_term_id)
        $wpdb->insert(
            $tb_terms,
            array('name' => 'arranged_post_tag_daknetcorp', 'slug' => 'arranged_post_tag_daknetcorp'),
            array('%s', '%s')
        );

        $term_id_add = $wpdb->get_row("SELECT `term_id` FROM `$tb_terms` WHERE `slug` = 'arranged_post_tag_daknetcorp'");
        $wpdb->insert(
            $tb_term_taxonomy,
            array('term_id' => $term_id_add->term_id, 'taxonomy' => 'arranged_post_tag_daknetcorp')
        );
    }

    // Insert option
    $option_id = $wpdb->get_row("SELECT `option_id` FROM `$tb_option` WHERE `option_name` = 'daknetcorp_auto_tag_category_time_setting'");
    if($option_id->option_id == 0){
        $wpdb->insert(
            $tb_option,
            array('option_name' => 'daknetcorp_auto_tag_category_time_setting', 'option_value' => '{"time":"30","option_time":"minutes","numPost":"3","numCate":"3","limitPost":"50"}'),
            array('%s', '%s')
        );
    }
}

function datc_delete_activation(){
    include_once( ABSPATH . 'wp-includes/wp-db.php' );
    global $wpdb;

    $tb_option       = $wpdb->prefix . 'options';
    $tb_postmeta       = $wpdb->prefix . 'postmeta';

    // delete category and tag
    $query_postmeta   = $wpdb->get_row("DELETE FROM `$tb_postmeta` WHERE `meta_key` = 'daknetcorp_filtered_tag' or `meta_key` = 'daknetcorp_filtered_post' ");
    $query_del_options   = $wpdb->get_row("DELETE FROM `$tb_option` WHERE `option_name` = 'daknetcorp_auto_tag_category_time_setting' ");
    $wpdb->query($query_postmeta);
    $wpdb->query($query_del_options);
}
?>
