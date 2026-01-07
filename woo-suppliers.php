<?php
/**
 * Plugin Name: Supplier Manager & SKU Generator & api rest for Supplier
 * Plugin URI: https://github.com/hessamzm/woo-suppliers
 * Description: سیستم ماژولار مدیریت تامین‌کنندگان و تولید هوشمند SKU برای لاپکس.
 * Version: 1.3
 * Author: hessamzm
 * Author URI: https://github.com/hessamzm
 * Requires PHP: 7.4
 * Requires at least: 5.6
 * WC requires at least: 10.0
 * Tested up to: 6.5
 * Recommended PHP: 8.2
 */

if (!defined('ABSPATH')) exit;

// تعریف ثابت‌ها برای دسترسی آسان در آینده
define('LP_VERSION', '1.0.0');
define('LP_PATH', plugin_dir_path(__FILE__));

// بارگذاری ماژول‌ها
require_once LP_PATH . 'includes/class-helpers.php';
require_once LP_PATH . 'includes/class-admin.php';
require_once LP_PATH . 'includes/class-product.php';
require_once LP_PATH . 'includes/class-api.php';

// راه‌اندازی ماژول‌ها
add_action('plugins_loaded', function() {
    new LP_Admin();
    new LP_Product();
});