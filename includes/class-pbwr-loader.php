<?php

require_once PBWR_PATH . 'public/class-pbwr-public.php';
require_once PBWR_PATH . 'admin/class-pbwr-admin.php';


function pbwr_run_public() {
    new PBWR_Public(PBWR_PATH, PBWR_URL);
}
add_action('plugins_loaded', 'pbwr_run_public');

function pbwr_run_admin() {
    if (is_admin()) {
        new PBWR_Admin(PBWR_PATH, PBWR_URL);
    }
}
add_action('plugins_loaded', 'pbwr_run_admin');
