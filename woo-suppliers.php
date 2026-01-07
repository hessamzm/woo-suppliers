<?php
/**
 * Plugin Name: Lapx Supplier Manager & SKU Generator
 * Description: سیستم ماژولار مدیریت تامین‌کنندگان و تولید هوشمند SKU برای لاپکس.
 * Version: 1.1.0
 * Author: hessamzm
 */

if (!defined('ABSPATH')) exit;

// تعریف ثابت‌ها برای دسترسی آسان در آینده
define('LP_VERSION', '1.0.0');
define('LP_PATH', plugin_dir_path(__FILE__));

// بارگذاری ماژول‌ها
require_once LP_PATH . 'includes/class-helpers.php';
require_once LP_PATH . 'includes/class-admin.php';
require_once LP_PATH . 'includes/class-product.php';

// راه‌اندازی ماژول‌ها
add_action('plugins_loaded', function() {
    new LP_Admin();
    new LP_Product();
});