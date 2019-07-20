<?php
/*
  Plugin Name: Competition Upload
  Plugin URI: https://gorakgarak.com
  description: Competition File Upload
  Version: 1.0.0
  Author: Rakyun Koh
  Author URI: https://gorakgarak.com
*/



add_action("admin_menu", "get_menu");
add_shortcode('grgr_lb', 'show_leaderboard');
add_shortcode('grgr_upload', 'show_user_uploader');

//처음 설치시 db create
register_activation_hook(__FILE__,'competition_table_install');
register_activation_hook(__FILE__,'leaderboard_table_install');

// Add menu
function get_menu()
{

  add_menu_page("Competition Manager", "Competition Manager", "manage_options", "myplugin", "uploadfile", plugins_url('/customplugin/img/icon.png'));
  add_submenu_page("myplugin","Upload file", "manage_options", "uploadfile", "uploadfile");
}

function uploadfile()
{
  include "uploadfile.php";
  include "competition_list.php";
}

function competition_table_install() {
  global $wpdb;
  global $charset_collate;
  $table_name = $wpdb->prefix . 'gr_competition';
   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `competition_key` text NOT NULL,
    `competition_desc` text NOT NULL,
    `filename` text NOT NULL,
    `metric` varchar(255) NOT NULL,
    `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`)
  )$charset_collate;";
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
}

function leaderboard_table_install() {
  global $wpdb;
  global $charset_collate;
  $table_name = $wpdb->prefix . 'gr_leaderboard';
   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `competition_key` text NOT NULL,
    `filename` varchar(255) NOT NULL,
    `score` DOUBLE NOT NULL,
    `calc_score` DOUBLE NOT NULL,
    `metric` varchar(255) NOT NULL,
    `desc` text NOT NULL,
    `user_id` varchar(255) NOT NULL,
    `user_name` varchar(255) NOT NULL,
    `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`)
  )$charset_collate;";
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
}


function show_leaderboard($atts)
{
  include "leaderboard.php";
}

function show_user_uploader($atts)
{
  include "upload_user.php";
}
