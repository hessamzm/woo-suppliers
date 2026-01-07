<?php
class LP_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
    }

    public function add_menu() {
        add_menu_page('تامین‌کنندگان لاپکس', 'تامین‌کنندگان', 'manage_options', 'lp-suppliers', array($this, 'render_page'), 'dashicons-groups');
    }

    public function render_page() {
        $suppliers = LP_Helper::get_suppliers();

        // منطق افزودن
        if (isset($_POST['lp_add_supplier']) && check_admin_referer('lp_add_action', 'lp_nonce')) {
            $name = sanitize_text_field($_POST['lp_supplier_name']);
            $id = sanitize_text_field($_POST['lp_supplier_id']);
            $suppliers[$id] = $name;
            update_option('lp_suppliers_list', $suppliers);
            echo '<div class="updated"><p>تامین‌کننده با موفقیت اضافه شد.</p></div>';
        }

        // منطق حذف
        if (isset($_GET['del'])) {
            unset($suppliers[$_GET['del']]);
            update_option('lp_suppliers_list', $suppliers);
        }

        include LP_PATH . 'includes/views/admin-page.php'; // جدا کردن HTML از منطق
    }
}