<?php

class PBWR_Admin {

    /**
     * Plugin path and URL.
     *
     * @var string
     */
    private $path;
    private $url;

    /**
     * Constructor.
     *
     * @param string $path Full plugin path.
     * @param string $url  Plugin base URL.
     */
    public function __construct($path, $url) {
        $this->path = trailingslashit($path);
        $this->url  = trailingslashit($url);

        $this->init_hooks();
    }

    /**
     * Register admin hooks.
     */
    private function init_hooks() {
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Register the plugin admin menu.
     */
    public function register_menu() {
        // Main menu page
        add_menu_page(
            __('PBWR Dashboard', 'pbwr'),
            __('PBWR', 'pbwr'),
            'manage_options',
            'pbwr-dashboard',
            [$this, 'render_sample_page_1'],
            'dashicons-admin-generic',
            60
        );



    /**
     * Render the admin page.
     */
    public function render_admin_page() {
        echo '<div id="pbwr-admin-root"></div>';
    }

    /**
     * Enqueue admin scripts and styles.
     */
    public function enqueue_assets($hook) {
        // Only load assets on our plugin admin page
        if ($hook !== 'toplevel_page_pbwr-dashboard') {
            return;
        }

        $build_dir   = $this->path . 'admin/build/';
        $script_file = $build_dir . 'index.js';
        $style_file  = $build_dir . 'index.css';

        if (!file_exists($script_file)) {
            return;
        }

        $build_url = $this->url . 'admin/build/';

        $index_dep = include_once PBWR_PATH . 'admin/build/index.asset.php';
        wp_enqueue_script(
            'pbwr-admin-js',
            $build_url . 'index.js',
            $index_dep['dependencies'], 
            $index_dep['version'],
            true
        );

        if (file_exists($style_file)) {
            wp_enqueue_style(
                'pbwr-admin-css',
                $build_url . 'index.css',
                [],
                '1.0'
            );
        }

        wp_localize_script('pbwr-admin-js', 'PBWRAdmin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wp_rest'),
        ]);
    }
}
