<?php

class Subscribe extends Database
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'add_theme_templates'));

    }

    /** Subscription package page */
    function create_subscription_page()
    {
        global $message;

        $basic = [
            [
                'type' => 'text',
                'value' => 'basic',
                'name' => 'deal',
            ],
            [

                'type' => 'text',
                'value' => '0.0',
                'name' => 'value',
            ],
            [

                'type' => 'text',
                'value' => 'One CV',
                'name' => 'cv',
            ]
        ];
        $limited = [
            [
                'type' => 'text',
                'value' => 'limited',
                'name' => 'deal',
            ],
            [

                'type' => 'text',
                'value' => '3.0',
                'name' => 'value',
            ],
            [

                'type' => 'text',
                'value' => 'Three CV',
                'name' => 'cv',
            ]
        ];
        $unlimited = [
            [
                'type' => 'text',
                'value' => 'unlimited',
                'name' => 'deal',
            ],
            [

                'type' => 'text',
                'value' => '5.0',
                'name' => 'value',
            ],
            [

                'type' => 'text',
                'value' => 'Five CV',
                'name' => 'cv',
            ]
        ];

        echo subscribe_form($basic, $limited, $unlimited, $message);

    }

    /** creating the custom Subscription page with the activation of plugin */
    public static function subscription_page()
    {

        if (!current_user_can('activate_plugins')) return;

        global $wpdb;

        if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'subscription-page'", 'ARRAY_A')) {

            $current_user = wp_get_current_user();

            // create post object
            $page = array(
                'post_title' => __('Subscription Page'),
                'post_status' => 'publish',
                'post_author' => $current_user->ID,
                'post_type' => 'page',
                'post_content' => '[subscription-page]',
            );

            /** insert the post into the database */
            wp_insert_post($page);
        }
    }

    /** Add Templated css file  */
    function add_theme_templates()
    {
        if (is_page('Subscription Page')) {
            wp_enqueue_style('style', substr(plugin_dir_url(__FILE__), 0, strrpos(plugin_dir_url(__FILE__), "/", -2)) . "/" . 'assets/style.css');

        }

    }

    public function get_basic_value()
    {
        global $wpdb, $message;
        $user_id = get_current_user_id();
        if (isset($_POST['order_now'])) {
            $deal_name = $_POST['deal'];
            $value = $_POST['value'];


            $get_deal_name = $wpdb->get_var("SELECT deal_name FROM {$wpdb->prefix}subscribe_deals WHERE user_id = $user_id");

            if (empty($get_deal_name) && $get_deal_name != 'limited' && $get_deal_name != 'unlimited') {
                $wpdb->insert("{$wpdb->prefix}subscribe_deals", [
                    'deal_name' => $deal_name,
                    'value' => $value,
                    'user_id' => $user_id,
                    'random_number' => 1
                ]);
                $message = "you have successfully subscribe the " . $deal_name . " deal";
            } else {
                $message = "You have already subscribe the deal, Sorry";
            }
        }
    }

    /** function to save the data of the user after subscribe the deal */
    public function get_subscribe_data()
    {

        global $wpdb, $message;
        $user_id = get_current_user_id();

        /** checking if value comes from PaYPal or form */
        if (isset($_GET['amt'])) {
            $amount = $_GET['amt'];
            $token = $_GET['token'];
            $tx = $_GET['tx'];
            $status = $_GET['st'];
            $currency = $_GET['cc'];
            $cm = $_GET['cm'];
            $item_number = $_GET['item_number'];
            $signature = $_GET['sig'];

            $get_transaction_id = $wpdb->get_var("SELECT tx FROM {$wpdb->prefix}paypal_detail WHERE tx = '$tx' AND  user_id = $user_id");

            switch ($amount) {
                case '0.0':
                    $value = $amount;
                    $deal_name = 'basic';
                    $random = 1;
                    break;
                case '3.0':
                    $value = $amount;
                    $deal_name = 'limited';
                    $random = 3;
                    break;
                case '5.0';
                    $value = $amount;
                    $deal_name = 'unlimited';
                    $random = 5;
                    break;
                default;
                    break;
            }

            $get_number = $wpdb->get_var("SELECT random_number FROM {$wpdb->prefix}subscribe_deals WHERE user_id = $user_id");

            $random_number = $random + $get_number;

            if (empty($get_transaction_id)) {
                $wpdb->insert("{$wpdb->prefix}paypal_detail", [
                    'token' => $token,
                    'tx' => $tx,
                    'status' => $status,
                    'currency' => $currency,
                    'cm' => $cm,
                    'item_number' => $item_number,
                    'signature' => $signature,
                    'user_id' => $user_id
                ]);

                if (empty($get_number)) {

                    $this->insert_user_deal($deal_name, $value, $user_id, $random);
                    $message = "Congrats, you have subscribe the " . $deal_name . " deal successfully";

                } else {
                    $wpdb->update("{$wpdb->prefix}subscribe_deals", array('deal_name' => $deal_name, 'random_number' => $random_number), array('user_id' => $user_id));
                    $message = "Congrats, you have subscribe the " . $deal_name . " deal successfully";

                }
            } else {
                $message = "Sorry you can not subscribe the deal right now";
            }
        }
    }
}



