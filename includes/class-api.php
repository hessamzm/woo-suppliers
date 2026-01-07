<?php
class LP_Rest_API {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    public function register_routes() {
        register_rest_route('lapx/v1', '/suppliers', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_suppliers_list'),
            'permission_callback' => array($this, 'check_permission'),
        ));

        // ۲. روت برای بروزرسانی تامین‌کننده محصول (POST) - قبلا نوشتیم
        register_rest_route('lapx/v1', '/update-supplier', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_product_supplier'),
            'permission_callback' => array($this, 'check_permission'),
        ));
    }
    // تابع بازگشتی برای نمایش لیست
    public function get_suppliers_list() {
        $suppliers = LP_Helper::get_suppliers();

        // تبدیل فرمت برای خروجی JSON تمیزتر
        $response = array();
        foreach ($suppliers as $id => $name) {
            $response[] = array(
                'id'   => $id,
                'name' => $name
            );
        }

        return rest_ensure_response($response);
    }

    public function check_permission() {
        /**
         * ووکامرس به طور خودکار Consumer Key/Secret را در هدر بررسی می‌کند.
         * اگر کلیدها صحیح باشند، وردپرس کاربر متناظر با آن کلید را "لاگین" فرض می‌کند.
         * حالا چک می‌کنیم که آیا این کاربر (که از طریق API وصل شده) اجازه ویرایش محصول دارد یا خیر.
         */

        // ۱. بررسی اینکه آیا ووکامرس کاربر را شناسایی کرده است
        if ( ! current_user_can( 'edit_products' ) ) {
            return new WP_Error(
                'rest_forbidden',
                'خطای دسترسی: شما اجازه ویرایش محصولات را ندارید یا کلیدهای API نامعتبر هستند.',
                array( 'status' => rest_authorization_required_code() )
            );
        }

        return true;
    }

    public function update_product_supplier($request) {
        // ... بقیه کد (مشابه قبل) ...
        $product_id  = $request->get_param('product_id');
        $supplier_id = $request->get_param('supplier_id');

        $product = wc_get_product($product_id);
        if (!$product) {
            return new WP_Error('not_found', 'محصول یافت نشد', array('status' => 404));
        }

        update_post_meta($product_id, '_supplier_id', $supplier_id);

        // تولید SKU جدید
        $new_sku = LP_Helper::generate_sku($product_id, $supplier_id);
        $product->set_sku($new_sku);
        $product->save();

        return array(
            'success' => true,
            'new_sku' => $new_sku
        );
    }
}