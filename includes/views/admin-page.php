<div class="wrap">
    <h1>مدیریت تامین‌کنندگان</h1>
    <div class="card" style="max-width: 400px;">
        <form method="post">
            <?php wp_nonce_field('lp_add_action', 'lp_nonce'); ?>
            <p><label>نام: <input type="text" name="lp_supplier_name" class="regular-text" required></label></p>
            <p><label>آیدی عددی: <input type="number" name="lp_supplier_id" class="small-text" required></label></p>
            <p><input type="submit" name="lp_add_supplier" class="button button-primary" value="افزودن"></p>
        </form>
    </div>

    <table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">
        <thead>
        <tr>
            <th style="width: 80px;">آیدی (ID)</th>
            <th>نام تامین‌کننده</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($suppliers as $id => $name): ?>
            <tr>
                <td><code><?php echo esc_html($id); ?></code></td>
                <td><strong><?php echo esc_html($name); ?></strong></td>
                <td><a href="?page=lp-suppliers&del=<?php echo $id; ?>" class="button-link-delete" onclick="return confirm('حذف شود؟')">حذف</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>