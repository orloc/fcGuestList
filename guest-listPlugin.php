<?php

/**
 * Plugin Name:       Guest List
 */

if (!defined('ABSPATH')) {
    exit; //Exit if accessed directly
}

require_once __DIR__.'/admin/Database.php';
require_once __DIR__.'/admin/Views.php';


add_action('admin_menu', 'guest_list_admin_actions');

register_activation_hook(__FILE__, ['Database', 'initGuestDb']);

function guest_list_admin_actions(){
    add_menu_page('Guest List', 'Guest List', 'manage_options', __FILE__.'guests', [ 'Views','guestListAdmin']);
    add_menu_page('Events', 'Events', 'manage_options', __FILE__.'events', ['Views','eventListAdmin']);
    add_menu_page('Roles', 'Roles', 'manage_options', __FILE__.'roles', ['Views','roleListAdmin']);
}


