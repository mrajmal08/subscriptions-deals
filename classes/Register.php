<?php

class Register extends Database
{
    /** Registration page function */
    public function create_register_page()
    {
        global $error;
        $inputs = [
            [
                'label' => 'Enter Name',
                'type' => 'text',
                'name' => 'name',
                'placeholder' => 'Enter Full Name'],

            [
                'label' => 'Enter City',
                'type' => 'text',
                'name' => 'city',
                'placeholder' => 'Enter City',
            ],
            [
                'label' => 'Enter Email',
                'type' => 'email',
                'name' => 'email',
                'placeholder' => 'Enter Email',
            ],
            [
                'label' => 'Enter Password',
                'type' => 'password',
                'name' => 'pwd',
                'placeholder' => 'Enter Password',
            ]
        ];

        echo register_form($inputs , $error);

    }

    /** creating the custom Registration page with the activation of plugin */
    public static function registration_page()
    {

        if (!current_user_can('activate_plugins')) return;

        global $wpdb;

        if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'registration-page'", 'ARRAY_A')) {

            $current_user = wp_get_current_user();

            // create post object
            $page = array(
                'post_title' => __('Register'),
                'post_status' => 'publish',
                'post_author' => $current_user->ID,
                'post_type' => 'page',
                'post_content' => '[register-page]',
            );

            /** insert the post into the database */
            wp_insert_post($page);
        }
    }

    /** register the user values */
    public function get_register_data()
    {
        global $wpdb, $error;

        if (isset($_POST['register_form'])) {

            $username = $_POST['name'];
            $city = $_POST['city'];
            $email = $_POST['email'];
            $password = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

            if (empty($username) || empty($password) || empty($email)) {
                $error = 'Required form field is missing';
            } else {
                $exists = email_exists($email);
                if ($exists) {
                    $error = "This email already exist in database";
                } else {
                    $this->insert_user($username, $email, $password);
                    /** getting the last inserted value */
                    $id = $wpdb->insert_id;
                    $code = rand(1234, 4321);

                    $data = [
                        'wp_user_city' => $city,
                        'user_random_code' => $code
                    ];
                    $this->insert_usermeta($id, $data);

                    $user_email = $wpdb->get_results("SELECT user_email from {$wpdb->prefix}users WHERE ID = $id");
                    $arr = get_object_vars($user_email[0]);
                    $to = $arr['user_email'];
                    $subject = 'Login Code';
                    $message = 'Please User this code to login ' . $code;
                    wp_mail($to, $subject, $message);
                    $error = "Please Use 4 digit Code for Login that sent you on your email thanks";
                    wp_redirect(site_url() . '/login/');
                }
            }
        }
    }
}

