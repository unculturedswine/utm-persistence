<?php
/**
 * Plugin Name: UTM Persistence
 * Plugin URI: https://sqrdmedia.com/plugins
 * Description: Persists UTM and tracking parameters across domains during a session
 * Version: 1.0.0
 * Author: Joel Jenkins
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('UTM_PERSISTENCE_VERSION', '1.0.0');
define('UTM_PERSISTENCE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('UTM_PERSISTENCE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once UTM_PERSISTENCE_PLUGIN_DIR . 'includes/class-utm-persistence-admin.php';

class UTM_Persistence {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('admin_init', array($this, 'register_settings'));
        
        if (is_admin()) {
            new UTM_Persistence_Admin();
        }
    }
    
    public function enqueue_frontend_scripts() {
        $settings = get_option('utm_persistence_settings', $this->get_default_settings());
        
        wp_enqueue_script(
            'utm-persistence',
            UTM_PERSISTENCE_PLUGIN_URL . 'assets/js/utm-persistence.js',
            array(),
            UTM_PERSISTENCE_VERSION,
            true
        );
        
        wp_localize_script('utm-persistence', 'utmPersistenceSettings', array(
            'domains' => $settings['domains'],
            'params' => $settings['params']
        ));
    }
    
    public function register_settings() {
        register_setting('utm_persistence_settings_group', 'utm_persistence_settings');
    }
    
    public function get_default_settings() {
        return array(
            'domains' => array(),
            'params' => array(
                'utm_medium',
                'utm_source',
                'utm_campaign',
                'utm_adgroup',
                'utm_content',
                'utm_term',
                'gclid',
                'fbclid',
                'refer'
            )
        );
    }
    
    public static function activate() {
        $default_settings = self::get_instance()->get_default_settings();
        if (!get_option('utm_persistence_settings')) {
            add_option('utm_persistence_settings', $default_settings);
        }
    }
    
    public static function deactivate() {
        // Cleanup if needed
    }
}

// Initialize the plugin
add_action('plugins_loaded', array('UTM_Persistence', 'get_instance'));

// Activation and deactivation hooks
register_activation_hook(__FILE__, array('UTM_Persistence', 'activate'));
register_deactivation_hook(__FILE__, array('UTM_Persistence', 'deactivate'));