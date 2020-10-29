<?php


class Tables
{
    /** creating the database table for Subscription detail */
    public static function subscribe_info_tables(){

        global $table_prefix;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tblname = 'subscribe_deals';
        $wp_track_table = $table_prefix . "$tblname ";

        $sql_query_to_create_table = "CREATE TABLE IF NOT EXISTS ". $wp_track_table ."(
         id INT(11) NOT NULL AUTO_INCREMENT,
         deal_name VARCHAR(100) NOT NULL,
         value VARCHAR(11) NOT NULL,
         user_id INT(100) NOT NULL,
         random_number VARCHAR(50) NOT NULL,
         PRIMARY KEY (id)
        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1"; /** sql query to create table */
        dbDelta($sql_query_to_create_table);

    }

    /** creating the database table for Resume detail */
    public static function resume_info_tables(){
        global $table_prefix;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tblname = 'resume_review';
        $wp_track_table = $table_prefix . "$tblname ";

        $sql_query_to_create_table = "CREATE TABLE IF NOT EXISTS ". $wp_track_table ."(
         resume_id INT(11) NOT NULL AUTO_INCREMENT,
         resume VARCHAR(500) NOT NULL,
         file_url VARCHAR(150) NOT NULL,
         deal_id VARCHAR(11) NOT NULL,
         user_id INT(11) NOT NULL,
         admin_review VARCHAR(500) NOT NULL,
         PRIMARY KEY (id)
        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1"; /** sql query to create table */
        dbDelta($sql_query_to_create_table);

    }

    /** creating the database table for PaYPal  detail */
    public static function paypal_info_tables(){
        global $table_prefix;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $tblname = 'paypal_detail';
        $wp_track_table = $table_prefix . "$tblname ";

        $sql_query_to_create_table = "CREATE TABLE IF NOT EXISTS ". $wp_track_table ."(
         pay_id INT(11) NOT NULL AUTO_INCREMENT,
         token VARCHAR(500) NOT NULL,
         tx VARCHAR(150) NOT NULL,
         status VARCHAR(100) NOT NULL,
         currency VARCHAR(50) NOT NULL,
         cm VARCHAR(100) NOT NULL,
         item_number VARCHAR(100) NOT NULL,
         signature VARCHAR(500) NOT NULL,
         user_id INT(11) NOT NULL,
         PRIMARY KEY (id)
        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1"; /** sql query to create table */
        dbDelta($sql_query_to_create_table);

    }
}