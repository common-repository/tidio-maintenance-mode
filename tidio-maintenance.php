<?php

/**
 * Plugin Name: Tidio Maintenance
 * Plugin URI: http://tidioelements.com/
 * Description: Tidio Maintenance
 * Version: 1.1.4
 * Author: tytus-tytus
 * Author URI: http://tidioelements.com/
 * License: GPL2
 */
class TidioMaintenance {

    private $pageId = '';
    private $plugin_path = '';

    public function __construct() {

        $this->plugin_path = plugin_dir_path(__FILE__);

        add_action('admin_menu', array($this, 'addAdminMenuLink'));
        add_action('admin_enqueue_scripts', array($this, 'addAdminStyles'));

        add_action("wp_ajax_tidio_maintenance_save_settings", array($this, "ajaxPageSaveSettings"));
        add_action("wp_ajax_tidio_maintenance_load_settings", array($this, "ajaxPageLoadSettings"));

        add_action('admin_footer', array($this, 'adminFooter'));

        //

        $options = get_option('tidio_maintenance_settings');
        if (isset($options['mode_on']) && $options['mode_on'] == 1) {
            add_action('template_include', array($this, 'addMaintenanceMode'));
        }
        if (isset($_GET['tidio-maintenance-csv'])) {
            $emails = get_option('tidio_maintenance_emails', array());
            $this->downloadCSV($emails);
        }
    }

    public static function getDefaultData() {
        $default = array(
            'mode_on' => 0,
            'title' => 'Maintenance mode is ON',
            'header' => '<strong>Maintenance</strong> mode is <strong>ON</strong>',
            'header-sub' => 'Website will be available soon',
            'logo-color' => '#2F4D5E',
            'header-color' => '#2F4D5E',
            'newsletter-color' => '#2F4D5E',
            'footer-color' => '#2F4D5E',
            'password_on' => 0,
            'background' => "",
            'custom-background' => '',
            'countdown_' => 0,
            'countdown' => date('Y-m-d', strtotime('+2days')),
            'countdown_time' => '00:00',
            'social_on' => 0,
            'facebook' => 'https://www.facebook.com/TidioElements',
            'newsletter_on' => 0,
            'google_analytics_code' => ''
        );
        return $default;
    }

    public static function getBackgroundColors() {
        return array(
            //1
            array(
                '#2F4D5E', //logo
                '#2F4D5E', //header
                '#fff', //newsletter
                '#fff', //footer
            ),
            //2
            array(
                '#fff',
                '#fff',
                '#fff',
                '#fff',
            ),
            //3
            array(
                '#2F4D5E',
                '#2F4D5E',
                '#fff',
                '#fff',
            ),
            //4
            array(
                '#fff',
                '#fff',
                '#fff',
                '#2F4D5E',
            ),
            //5
            array(
                '#2F4D5E',
                '#fff',
                '#fff',
                '#fff',
            ),
            //6
            array(
                '#2F4D5E',
                '#fff',
                '#2F4D5E',
                '#2F4D5E',
            ),
            //7
            array(
                '#2F4D5E',
                '#2F4D5E',
                '#2F4D5E',
                '#fff',
            ),
            //8
            array(
                '#2F4D5E',
                '#2F4D5E',
                '#fff',
                '#2F4D5E',
            ),
            //9
            array(
                '#2F4D5E',
                '#2F4D5E',
                '#fff',
                '#fff',
            ),
            //10
            array(
                '#fff',
                '#fff',
                '#fff',
                '#fff',
            ),
        );
    }

    public function addMaintenanceMode() {

        if (isset($_COOKIE['logged-mode-off'])) {
            return;
        }
        include plugin_dir_path(__FILE__) . 'view/index.php';
        exit;
    }

    // Menu Positions

    public function addAdminMenuLink() {
        $optionPage = add_menu_page(
                'Maintenance', 'Maintenance', 'manage_options', 'tidio-maintenance', array($this, 'addAdminPage'), plugins_url('/media/img/icon.png', __FILE__)
        );
        $this->pageId = $optionPage;
    }

    private function downloadCSV($data) {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=emails.csv');

// create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

// output the column headings
        fputcsv($output, $data);
        exit;
    }

    public function addAdminPage() {
        $dir = $this->plugin_path;

        $emails = get_option('tidio_maintenance_emails', array());
        $emails_count = count($emails);

        $settings = $this->getDefaultData();
        $settings = array_merge($settings, get_option('tidio_maintenance_settings', array()));

        $this->register();
        include $dir . 'options.php';
    }

    public function addAdminStyles($hook) {
        if ($this->pageId != $hook)
            return;

        wp_register_style('minicolors', plugins_url('media/css/jquery.minicolors.css', __FILE__));
        wp_enqueue_style('minicolors');
        wp_register_style('tidio-maintenance-css', plugins_url('media/css/app-options.css', __FILE__));
        wp_enqueue_style('tidio-maintenance-css');
        wp_enqueue_script('minicolors', plugin_dir_url(__FILE__) . 'media/js/jquery.minicolors.min.js', array('jquery'), '2.1', true);
        wp_enqueue_script('app-script', plugin_dir_url(__FILE__) . 'media/js/app.js', array(), '1.0', true);

        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style('thickbox');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
        }
    }

    public function adminFooter() {
        
    }

    public static function getBackgrounds() {
        $files = array();
        for ($i = 1; $i <= 10; $i++) {
            array_push($files, plugin_dir_url(__FILE__) . 'media/img/bgs/b' . $i . '_thumb.jpg');
        }
        return $files;
    }

    public function ajaxPageSaveSettings() {

        $saveData = $_POST;
        update_option('tidio_maintenance_settings', $saveData);

        return $this->response(true, 'SAVED');
    }

    public function ajaxPageLoadSettings() {
        $option = get_option('tidio_maintenance_settings', FALSE);
        if ($option === FALSE) {
            $option = $this->getDefaultData();
        }
        echo json_encode($option);
        exit;
    }

    private function response($status = false, $value = null) {

        echo json_encode(array(
            'status' => $status,
            'value' => $value
        ));

        exit;
    }

    public static function register() {

        $tidio_key = get_option('tidio_maintenance_key', FALSE);

        if ($tidio_key !== FALSE) {
            return;
        }

        $url = 'http://www.tidioelements.com/apiExternalPlugin/registerPlugin?' . http_build_query(array(
                    'siteUrl' => site_url(),
                    'pluginType' => 'maintenance',
                    '_ip' => $_SERVER['REMOTE_ADDR']
        ));

        //

        $key = '-1';

        //

        $content = self::getContent($url);


        if ($content) {

            $content = json_decode($content, true);

            if (isset($content['value'])) {

                $key = '1';
            }
        }

        update_option('tidio_maintenance_key', $key);
    }

    private static function getContent($url) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

}

$tidioMaintenance = new TidioMaintenance();