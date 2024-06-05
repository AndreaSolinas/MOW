<?php

namespace Plugin\Admin;

class AdminPanel {
    public function __construct(String $title, String $roleCapability, String $slug, String $icon_url = 'dashicons-admin-generic', int $position = null ) {
        $this->createAdminPanel($title, $roleCapability, $slug, $icon_url, $position);
    }

    private function createAdminPanel(String $title, String $roleCapability, String $slug, String $icon_url = 'dashicons-admin-generic', int $position = null ): void
    {
        add_action('admin_menu',
        function () use ($title, $roleCapability, $slug, $icon_url, $position){
            add_menu_page(
                $title,
                $title,
                $roleCapability,
                $slug,
                [$this, 'createPage'],
                $icon_url,
                $position
            );
        }
        ,10);
    }

    public function createPage(): void
    {
        wp_enqueue_script ( 'jquery-js', 'https://code.jquery.com/jquery-3.5.1.slim.min.js', [], false, true );
        wp_enqueue_style ( 'bootstrap',plugin_dir_url( __FILE__ )  . 'templates/assets/vendor/bootstrap/css/bootstrap.min.css' );
        wp_enqueue_script ( 'bootstrap-js', plugin_dir_url( __FILE__ )  . 'templates/assets/vendor/bootstrap/js/bootstrap.min.js', [], false, true );
        include_once realpath(__DIR__) . '/templates/index.php';
    }

}