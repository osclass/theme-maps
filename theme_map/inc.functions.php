<?php

osc_add_hook('init_admin', 'theme_theme_map_actions_admin');
osc_add_hook('init_admin', 'theme_theme_map_regions_map_admin');
if (function_exists('osc_admin_menu_appearance')) {
    osc_admin_menu_appearance(__('Header logo', 'theme_map'), osc_admin_render_theme_url('oc-content/themes/theme_map/admin/header.php'), 'header_theme_map');
    osc_admin_menu_appearance(__('Theme settings', 'theme_map'), osc_admin_render_theme_url('oc-content/themes/theme_map/admin/settings.php'), 'settings_theme_map');
    osc_admin_menu_appearance(__('Map settings', 'theme_map'), osc_admin_render_theme_url('oc-content/themes/theme_map/admin/map_settings.php'), 'map_settings_theme_map');
} else {

    function theme_map_admin_menu() {
        echo '<h3><a href="#">' . __('Brasil theme', 'theme_map') . '</a></h3>
            <ul>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/theme_map/admin/header.php') . '">&raquo; ' . __('Header logo', 'theme_map') . '</a></li>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/theme_map/admin/settings.php') . '">&raquo; ' . __('Theme settings', 'theme_map') . '</a></li>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/theme_map/admin/map_settings.php') . '">&raquo; ' . __('Map settings', 'theme_map') . '</a></li>

            </ul>';
    }

    osc_add_hook('admin_menu', 'theme_map_admin_menu');
}

function theme_theme_map_regions_map_admin() {
    $regions = json_decode(osc_get_preference('region_maps', 'theme_map'), true);
    switch (Params::getParam('action_specific')) {
        case('edit_region_map'):
            $regions[Params::getParam('target-id')] = Params::getParam('region');
            osc_set_preference('region_maps', json_encode($regions), 'theme_map');
            osc_add_flash_ok_message(__('Region saved correctly', 'theme_map'), 'admin');
            header('Location: ' . osc_admin_render_theme_url('oc-content/themes/theme_map/admin/map_settings.php'));
            exit;
            break;
    }
}

function map_region_url($region_id) {
    $regionData = Region::newInstance()->findByPrimaryKey($region_id);
    if( function_exists('osc_subdomain_type') ) {
        if(osc_subdomain_type()=='region' || osc_subdomain_type()=='category') {
            return osc_update_search_url(array('sRegion' => $regionData['s_name']));
        } else {
            $url    = osc_base_url();
            if(osc_subdomain_type()!='') {
                $aParts = explode('.', $url);
                unset($aParts[0]);
                $url = implode('.', $aParts);
            }

            if (osc_get_preference('seo_url_search_prefix') != '') {
                $url .= osc_get_preference('seo_url_search_prefix') . '/';
            }
            $url .= osc_sanitizeString($regionData['s_name']) . '-r' . $regionData['pk_i_id'];
            return $url;
        }
    } else {
        return osc_search_url(array('sRegion' => $regionData['s_name']));
    }
}

function theme_theme_map_admin_regions_message() {
    $regions = json_decode(osc_get_preference('region_maps', 'theme_map'), true);
    if (count($regions) < _theme_maps_n_regions) {
        echo '</div><div class="flashmessage flashmessage-error" style="display:block">' . sprintf(__('Wait! There are unassigned map areas in the map. <a href="%s">Click here</a> to assign regions to the map.', 'theme_map'), osc_admin_render_theme_url('oc-content/themes/theme_map/admin/map_settings.php')) . '<a class="btn ico btn-mini ico-close">x</a>';
    }
}

osc_add_hook('admin_page_header', 'theme_theme_map_admin_regions_message', 10);
?>