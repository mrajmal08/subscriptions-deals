<?php


class Login extends Database
{

    /** Login page function */
    public function create_login_page()
    {
        echo login_form();
    }

    /** creating the custom Registration page with the activation of plugin */
    public static function login_page()
    {

        if (!current_user_can('activate_plugins')) return;

        global $wpdb;

        if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'login'", 'ARRAY_A')) {

            $current_user = wp_get_current_user();

            // create post object
            $page = array(
                'post_title' => __('Login'),
                'post_status' => 'publish',
                'post_author' => $current_user->ID,
                'post_type' => 'page',
                'post_content' => '[login-page]',
            );

            /** insert the post into the database */
            wp_insert_post($page);
        }
    }

    /** Login with 4 digit code */
    public function login_with_code()
    {
        global $wpdb , $error;

        if (isset($_POST['login_form'])) {

            $code = $_POST['code'];
            $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}usermeta WHERE meta_value = $code");
            if ($result) {
                $arr = get_object_vars($result[0]);
                $user_code = $arr['meta_value'];
                $user_id = $arr['user_id'];
                if ($user_id) {

                    /** change the status of user after fist time login with code */
                    $wpdb->delete("{$wpdb->prefix}usermeta", array('user_id' => $user_id, 'meta_value' => $user_code));

                    /** redirect to home after login */
                    wp_redirect(site_url() . '/wp-login.php');
                }
            } else {
                $error = "your code has expired, Please register again";
            }
        }
    }

}