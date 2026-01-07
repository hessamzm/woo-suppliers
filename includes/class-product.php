<?php
class LP_Product {
    public function __construct() {
        // فیلد محصول ساده
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_supplier_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_product_data'));

        // فیلد محصول متغیر
        add_action('woocommerce_product_after_variable_attributes', array($this, 'add_variation_field'), 10, 3);
        add_action('woocommerce_save_product_variation', array($this, 'save_variation_data'), 10, 2);
    }

    public function add_supplier_field() {
        $suppliers = LP_Helper::get_suppliers();
        $options = array('' => '--- انتخاب تامین‌کننده ---');
        foreach ($suppliers as $id => $name) { $options[$id] = "($id) $name"; }

        woocommerce_wp_select(array(
            'id' => '_supplier_id',
            'label' => 'تامین‌کننده',
            'options' => $options,
        ));
    }

    public function save_product_data($post_id) {
        if (!isset($_POST['_supplier_id']) || empty($_POST['_supplier_id'])) return;

        $sid = sanitize_text_field($_POST['_supplier_id']);
        update_post_meta($post_id, '_supplier_id', $sid);

        $sku = LP_Helper::generate_sku($post_id, $sid);
        $product = wc_get_product($post_id);
        $product->set_sku($sku);
        $product->save();
    }

    // متدهای مشابه برای Variation ها را اینجا اضافه کنید...
}