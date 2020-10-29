<?php

/*
 * Plugin Name: Subscribe User
 * Plugin URI: http://localhost/subscription
 * Description: Subscription of user and CV writing
 * Version: 1.0
 * Author: Ajmal
 * Author URI: http://localhost/subscription
 */

function create_custom_pages()
{

    require_once 'autoload.php';
    new Register();
    new Subscribe();
    new Resume();
    new Tables();
    new Login();

    Login::login_page();
    Tables::subscribe_info_tables();
    Tables::resume_info_tables();
    Subscribe::subscription_page();

    Resume::create_page_upload_cv();
    Register::registration_page();

}

register_activation_hook(__FILE__, 'create_custom_pages');

add_action('init', 'public_init');
add_action('admin_init', 'admin_hook');

function admin_hook()
{
    $admin = new Admin();
    $admin->admin_page_code();
}

function public_init()
{
    require_once 'functions.php';
    require_once 'autoload.php';
    $reg = new Register();
    $log = new Login();
    $sub = new Subscribe();
    $res = new Resume();
    $admin = new Admin();


    add_shortcode('register-page', array($reg, 'create_register_page'));
    add_shortcode('login-page', array($log, 'create_login_page'));

    add_shortcode('subscription-page', array($sub, 'create_subscription_page'));
    add_action('wp_enqueue_scripts', array($sub, 'add_theme_templates'));

    add_shortcode('upload-cv', array($res, 'post_your_cv_page'));
    add_action('admin_menu', array($admin, 'my_menu_pages'));

    add_filter('authenticate', 'check_login', 10, 3);

}

add_action('wp', 'main_action');
function main_action(){


    $reg = new Register();
    $log = new Login();
    $sub = new Subscribe();
    $res = new Resume();

    $reg->get_register_data();
    $log->login_with_code();
    $sub->get_subscribe_data();
    $sub->get_basic_value();
    $res->upload_cv();

}

/** change login credentials */
function check_login($user, $username, $pass)
{
    global $wpdb;
    if (isset($_POST['log']) && trim($_POST['log']) != "") {

        $u_name = $_POST['log'];
        $pwd = $_POST['pwd'];

        $UsermetaData = $wpdb->get_results("SELECT * FROM  {$wpdb->prefix}users inner join                     {$wpdb->prefix}usermeta on {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id  WHERE                  {$wpdb->prefix}users.user_login = '$u_name' AND {$wpdb->prefix}usermeta.meta_key =                           'user_random_code'");

        if ($UsermetaData) {

            remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);

            return false;
        } else {
            return $user;
        }
    }
}


