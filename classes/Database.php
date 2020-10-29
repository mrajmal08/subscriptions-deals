<?php


class Database
{

    /** register user insert function */
    protected function insert_user($username, $email, $password)
    {
        global $wpdb;

        return $wpdb->insert("{$wpdb->prefix}users", [
            'user_login' => $username,
            'user_nicename' => $username,
            'user_pass' => $password,
            'user_email' => $email,
        ]);

    }

    /** insert user details function */
    protected function insert_usermeta($id, $data)
    {
        global $wpdb;
        foreach ($data as $key => $value) {
            $result = $wpdb->insert("{$wpdb->prefix}usermeta", [
                'user_id' => $id,
                'meta_key' => $key,
                'meta_value' => $value,
            ]);
        }
        return $result;
    }

    /** insert the subscription deal in the table */
    protected function insert_user_deal($deal_name , $value, $user_id, $random)
    {
        global $wpdb;
        return $wpdb->insert("{$wpdb->prefix}subscribe_deals", [
            'deal_name' => $deal_name,
            'value' => $value,
            'user_id' => $user_id,
            'random_number' => $random,
        ]);
    }
}
