<?php
class LP_Helper {
    // دریافت لیست تامین‌کنندگان
    public static function get_suppliers() {
        return get_option('lp_suppliers_list', array());
    }

    // تولید SKU هوشمند
    public static function generate_sku($product_id, $supplier_id) {
        $terms = wp_get_post_terms($product_id, 'product_cat');
        $main_cat_id = 0;

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                if ($term->parent == 0) {
                    $main_cat_id = $term->term_id;
                    break;
                }
            }
            if ($main_cat_id == 0) $main_cat_id = $terms[0]->term_id;
        }

        $random_part = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return $supplier_id . '-' . $main_cat_id . '-' . $random_part;
    }
}