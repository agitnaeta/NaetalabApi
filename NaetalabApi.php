<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: Naetalab API
Plugin URI: https://naetalab.com/
Description: Api to open your article and some product to public
Version: 1.1.1
Author: Agit Naeta
Author URI: https://agitnaeta.com
License: GNU 3
Text Domain: Naetalab
*/



if(!class_exists('NaetalabApi')){
    Class NaetalabApi {
        protected $wpdb;
        protected  $namespace;
        function __construct(){
            global $wpdb;
            $this->wpdb= $wpdb;
            $this->namespace =  "naetalab-api";
            $this->_register_api();
        }
        function activate(){
            $this->_register_api();
        }
        private function _register_api(){
            $this->_all_article();
            $this->_all_product();
        }

        private function _all_product(){
            add_action( 'rest_api_init', function () {
                register_rest_route( $this->namespace, 'product/all', array(
                    'methods' => 'GET',
                    'callback' => 'get_all_product',
                ) );
            } );
        }
        private function  _all_article(){
            add_action( 'rest_api_init', function () {
                register_rest_route( $this->namespace, 'article/all', array(
                    'methods' => 'GET',
                    'callback' => 'get_all_article',
                ) );
            } );
        }
        function deactivate(){
    
        }
    }

    $NaetalabApi= new NaetalabApi;
    //Activate
    register_activation_hook( __FILE__, array($NaetalabApi,'activate'));

    // Deactivated
    register_activation_hook( __FILE__, array($NaetalabApi,'deactivated'));


    function get_all_article(){
        global  $wpdb;
        $query = "Select * from {$wpdb->prefix}posts where post_type='post' and post_status='publish'";
        $result = $wpdb->get_results($query,
            OBJECT);
        return new WP_REST_Response( $result, 200 );
    }

    function get_all_product(){
        global  $wpdb;
        $query = "Select * from {$wpdb->prefix}posts where post_type='product' and post_status='publish'";
        $result = $wpdb->get_results($query,
            OBJECT);
        return new WP_REST_Response( $result, 200 );
    }
}
