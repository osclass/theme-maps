<?php

if (theme_map_is_fineuploader()) {
    if (!OC_ADMIN) {
        osc_enqueue_style('fine-uploader-css', osc_assets_url('js/fineuploader/fineuploader.css'));
    }
    osc_enqueue_script('jquery-fineuploader');
}

function theme_map_is_fineuploader() {
    return Scripts::newInstance()->registered['jquery-fineuploader'] && method_exists('ItemForm', 'ajax_photos');
} 
if (function_exists('osc_admin_menu_appearance')) {
    osc_admin_menu_appearance(__('Header logo', 'theme_map'), osc_admin_render_theme_url('oc-content/themes/theme_map/admin/header.php'), 'header_theme_map');
    osc_admin_menu_appearance(__('Theme settings', 'theme_map'), osc_admin_render_theme_url('oc-content/themes/theme_map/admin/settings.php'), 'settings_theme_map');
} else {

    function theme_map_admin_menu() {
        echo '<h3><a href="#">' . __('Brasil theme', 'theme_map') . '</a></h3>
            <ul>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/theme_map/admin/header.php') . '">&raquo; ' . __('Header logo', 'theme_map') . '</a></li>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/theme_map/admin/settings.php') . '">&raquo; ' . __('Theme settings', 'theme_map') . '</a></li>

            </ul>';
    }

    osc_add_hook('admin_menu', 'theme_map_admin_menu');
}

?>