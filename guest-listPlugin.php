<?php

/**
 * Plugin Name:       Guest List
 */

if (!defined('ABSPATH')) {
    exit; //Exit if accessed directly
}

require_once __DIR__.'/admin/Database.php';
require_once __DIR__.'/admin/views/EventView.php';
require_once __DIR__.'/admin/views/RoleView.php';
require_once __DIR__.'/admin/views/GuestView.php';

register_activation_hook(__FILE__, ['Database', 'init']);

add_action('admin_post_submit_role', ['RoleView', 'handlePost']);
add_action('admin_post_submit_guest', ['GuestView', 'handlePost']);
add_action('admin_post_submit_event', ['EventView', 'handlePost']);

add_action('admin_post_delete_role', ['RoleView', 'handleDelete']);
add_action('admin_post_delete_guest', ['GuestView', 'handleDelete']);
add_action('admin_post_delete_event', ['EventView', 'handleDelete']);

add_action('admin_menu', 'guest_list_admin_actions');
add_action( 'admin_enqueue_scripts', 'registerScripts' );


function guest_list_admin_actions(){
    add_menu_page('Guest List', 'Guest List', 'manage_options', __FILE__.'guests', [ 'GuestView','getView']);
    add_menu_page('Events', 'Events', 'manage_options', __FILE__.'events', ['EventView','getView']);
    add_menu_page('Roles', 'Roles', 'manage_options', __FILE__.'roles', ['RoleView','getView']);
}

function registerScripts(){
    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js');
    wp_enqueue_script('angularjs','/../app/plugins/fcGuestList/bower_components/angular/angular.min.js');
    wp_enqueue_script('admin','/../app/plugins/fcGuestList/assets/js/app.js');
}


