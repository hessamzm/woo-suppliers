<?php
class LP_Product {
    public function __construct() {
        // محصولات ساده
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_supplier_field'));
        add_action('woocommerce_admin_process_product_object', array($this, 'process_product_sku_logic'));

        // محصولات متغیر (Variations)
        // نمایش فیلد در تنظیمات هر متغیر
        add_action('woocommerce_product_after_variable_attributes', array($this, 'add_variation_field'), 10, 3);
        // ذخیره اطلاعات متغیر و تولید SKU
        add_action('woocommerce_save_product_variation', array($this, 'save_variation_data'), 10, 2);
    }

    public function add_supplier_field() {
        $suppliers = LP_Helper::get_suppliers();
        $options = array('' => '--- انتخاب تامین‌کننده ---');
        foreach ($suppliers as $id => $name) { $options[$id] = "($id) $name"; }

        woocommerce_wp_select(array(
            'id' => '_supplier_id',
            'label' => 'تامین‌کننده (محصول از)',
            'options' => $options,
        ));
    }

    public function process_product_sku_logic($product) {
        if (isset($_POST['_supplier_id']) && !empty($_POST['_supplier_id'])) {
            $sid = sanitize_text_field($_POST['_supplier_id']);
            $product->update_meta_data('_supplier_id', $sid);
            $new_sku = LP_Helper::generate_sku($product->get_id(), $sid);
            $product->set_sku($new_sku);
        }
    }

    // --- بخش محصولات متغیر ---

    public function add_variation_field($loop, $variation_data, $variation) {
        $suppliers = LP_Helper::get_suppliers();
        $options = array('' => '--- انتخاب تامین‌کننده ---');
        foreach ($suppliers as $id => $name) { $options[$id] = "($id) $name"; }

        woocommerce_wp_select(array(
            'id' => '_supplier_id[' . $loop . ']',
            'label' => 'تامین‌کننده این متغیر',
            'value' => get_post_meta($variation->ID, '_supplier_id', true),
            'options' => $options,
            'wrapper_class' => 'form-row form-row-full',
        ));
    }

    public function save_variation_data($variation_id, $i) {
        if (isset($_POST['_supplier_id'][$i]) && !empty($_POST['_supplier_id'][$i])) {
            $sid = sanitize_text_field($_POST['_supplier_id'][$i]);

            // ۱. ذخیره شناسه تامین کننده در متای متغیر
            update_post_meta($variation_id, '_supplier_id', $sid);

            // ۲. تولید و ذخیره SKU اختصاصی برای این متغیر
            $sku = LP_Helper::generate_sku($variation_id, $sid);

            // استفاده از CRUD ووکامرس برای اطمینان از ذخیره SKU
            $variation = wc_get_product($variation_id);
            $variation->set_sku($sku);
            $variation->save();
        }
    }
}