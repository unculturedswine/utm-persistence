<?php
/**
 * Admin functionality for UTM Persistence plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class UTM_Persistence_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_utm_persistence_save_settings', array($this, 'ajax_save_settings'));
    }
    
    public function add_admin_menu() {
        add_options_page(
            'UTM Persistence Settings',
            'UTM Persistence',
            'manage_options',
            'utm-persistence',
            array($this, 'display_admin_page')
        );
    }
    
    public function enqueue_admin_scripts($hook) {
        if ('settings_page_utm-persistence' !== $hook) {
            return;
        }
        
        wp_enqueue_script(
            'utm-persistence-admin',
            UTM_PERSISTENCE_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            UTM_PERSISTENCE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'utm-persistence-admin',
            UTM_PERSISTENCE_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            UTM_PERSISTENCE_VERSION
        );
        
        wp_localize_script('utm-persistence-admin', 'utmPersistenceAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('utm_persistence_admin_nonce')
        ));
    }
    
    public function display_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $settings = get_option('utm_persistence_settings', UTM_Persistence::get_instance()->get_default_settings());
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" id="utm-persistence-settings-form">
                <?php wp_nonce_field('utm_persistence_save_settings', 'utm_persistence_nonce'); ?>
                
                <h2>Domains to Decorate</h2>
                <p class="description">Enter domains (without https:// or trailing slash) that should have UTM parameters appended to their links.</p>
                
                <div id="utm-domains-wrapper">
                    <div class="utm-domains-list">
                        <?php 
                        if (!empty($settings['domains'])) {
                            foreach ($settings['domains'] as $domain) {
                                ?>
                                <div class="utm-domain-row">
                                    <input type="text" name="utm_domains[]" value="<?php echo esc_attr($domain); ?>" class="regular-text" placeholder="example.com" />
                                    <button type="button" class="button utm-remove-row">Remove</button>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="utm-domain-row">
                                <input type="text" name="utm_domains[]" value="" class="regular-text" placeholder="example.com" />
                                <button type="button" class="button utm-remove-row">Remove</button>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <button type="button" class="button utm-add-domain">Add Domain</button>
                </div>
                
                <h2>Query Parameters to Track</h2>
                <p class="description">Specify which query parameters should be persisted across page loads.</p>
                
                <div id="utm-params-wrapper">
                    <div class="utm-params-list">
                        <?php 
                        if (!empty($settings['params'])) {
                            foreach ($settings['params'] as $param) {
                                ?>
                                <div class="utm-param-row">
                                    <input type="text" name="utm_params[]" value="<?php echo esc_attr($param); ?>" class="regular-text" placeholder="utm_source" />
                                    <button type="button" class="button utm-remove-row">Remove</button>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <button type="button" class="button utm-add-param">Add Parameter</button>
                </div>
                
                <p class="submit">
                    <button type="submit" class="button button-primary">Save Settings</button>
                </p>
            </form>
            
            <div id="utm-settings-notice" class="notice" style="display: none;">
                <p></p>
            </div>
        </div>
        <?php
    }
    
    public function ajax_save_settings() {
        if (!check_ajax_referer('utm_persistence_admin_nonce', 'nonce', false)) {
            wp_die('Security check failed');
        }
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $domains = isset($_POST['domains']) ? array_map('sanitize_text_field', $_POST['domains']) : array();
        $params = isset($_POST['params']) ? array_map('sanitize_text_field', $_POST['params']) : array();
        
        // Remove empty values
        $domains = array_filter($domains);
        $params = array_filter($params);
        
        // Remove duplicates
        $domains = array_unique($domains);
        $params = array_unique($params);
        
        $settings = array(
            'domains' => array_values($domains),
            'params' => array_values($params)
        );
        
        update_option('utm_persistence_settings', $settings);
        
        wp_send_json_success(array(
            'message' => 'Settings saved successfully!'
        ));
    }
}