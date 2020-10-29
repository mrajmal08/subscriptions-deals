<?php

class Resume
{

    /** Upload CV Page for */
    function post_your_cv_page()
    {
        echo resume_form();
    }

    /** creating the custom CV page with the activation of plugin */
    public static function create_page_upload_cv()
    {

        if (!current_user_can('activate_plugins')) return;

        global $wpdb;

        if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'upload-cv'", 'ARRAY_A')) {

            $current_user = wp_get_current_user();

            // create post object
            $page = array(
                'post_title' => __('Upload Cv'),
                'post_status' => 'publish',
                'post_author' => $current_user->ID,
                'post_type' => 'page',
                'post_content' => '[upload-cv]',
            );

            /** insert the post into the database */
            wp_insert_post($page);
        }
    }

    /** upload cv */
    public function upload_cv()
    {
        global $wpdb , $error;

        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        if (isset($_POST['submit_resume'])) {

            $user_id = get_current_user_id();

            /** checking the user deal in subscribe table */
            $get_deal = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}subscribe_deals where user_id = $user_id");

            $deal_id = $get_deal[0]->id;
            $my_count = $get_deal[0]->random_number;
            $deal_name = $get_deal[0]->deal_name;

            if ($deal_name) {
                if (!function_exists('wp_handle_upload')) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                }
                $upload_overrides = array('test_form' => false);
                $files = $_FILES['resume'];

                $uploaded_count = count($files['name']);

                $total = $my_count - $uploaded_count;

                if ($total < 0) {
                    $error = "Sorry you have only $my_count resume to upload ";
                } else {

                    foreach ($files['name'] as $key => $value) {
                        if ($files['name'][$key]) {
                            $file = array(
                                'name' => $files['name'][$key],
                                'type' => $files['type'][$key],
                                'tmp_name' => $files['tmp_name'][$key],
                                'error' => $files['error'][$key],
                                'size' => $files['size'][$key]
                            );
                            $data_moved = wp_handle_upload($file, $upload_overrides);
                            $file_url = $data_moved['url'];

                            $resume_inserted = $wpdb->insert("{$wpdb->prefix}resume_review", [
                                'resume' => $file['name'],
                                'deal_id' => $deal_id,
                                'user_id' => $user_id,
                                'file_url' => $file_url
                            ]);

                            /** updating te subscribe table after counting the resume upload */
                            if ($resume_inserted) {
                                $wpdb->update("{$wpdb->prefix}subscribe_deals", array('random_number' => $total), array('user_id' => $user_id));
                            }

                        }
                    }
                }
            }
        }
    }
}