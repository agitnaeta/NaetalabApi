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
            $this->allPost();
            $this->allPage();
        }

        private function allPage(){
            add_action( 'rest_api_init', function () {
                register_rest_route( $this->namespace, 'page/all', array(
                    'methods' => 'GET',
                    'callback' => 'get_all_page',
                ) );
            } );
        }
        private function  allPost(){
            add_action( 'rest_api_init', function () {
                register_rest_route( $this->namespace, 'post/all', array(
                    'methods' => 'GET',
                    'callback' => 'get_all_post',
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


    function get_all_post(){
        // WP_Query arguments
        $args = array(
          'post_type'              => array( 'post' ),
          'post_status'            => array( 'publish' ),
        );

        // The Query
        $query = (new WP_Query( $args ))->posts;
        array_map(function ($data){
            $data->image = get_the_post_thumbnail_url($data->ID,'thumbnail');
            return $data ;
        },$query);
        return  new WP_REST_Response($query,200);
    }

    function get_all_page(){
        global  $wpdb;
        // WP_Query arguments
        $args = array(
          'post_type'              => array( 'page' ),
          'post_status'            => array( 'publish' ),
        );

        // The Query
        $query = (new WP_Query( $args ))->posts;
        array_map(function ($data){
            $data->image = get_the_post_thumbnail_url($data->ID,'thumbnail');
            return $data ;
        },$query);
        return  new WP_REST_Response($query,200);
    }
}
