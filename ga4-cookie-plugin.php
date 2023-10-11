<?php
/**
 * Plugin Name: GA4 Cookie Consent
 * Description: Add Google Analytics 4 (GA4) code with cookie consent modal.
 * Version: 1.2
 * Author: chatGPT
 */

// Register and enqueue scripts and styles
function ga4_cookie_enqueue_scripts() {
    // Add your GA4 tracking code here
    $tracking_code = get_option('ga4_cookie_tracking_code');
    if (!empty($tracking_code)) {
        wp_enqueue_script('ga4-script', 'https://www.googletagmanager.com/gtag/js?id=' . $tracking_code, array(), null, true);
    }

    // Add your modal JavaScript and CSS files
    wp_enqueue_script('cookie-modal-script', plugin_dir_url(__FILE__) . 'assets/cookie-modal.js', array('jquery'), '1.0', true);
    wp_enqueue_style('cookie-modal-style', plugin_dir_url(__FILE__) . 'assets/cookie-modal.css');
}
add_action('wp_enqueue_scripts', 'ga4_cookie_enqueue_scripts');

// Display the cookie consent modal
function ga4_cookie_consent_modal() {
    include(plugin_dir_path(__FILE__) . 'cookie-modal.php');
}
add_action('wp_footer', 'ga4_cookie_consent_modal');

// Handle cookie consent form submission
function ga4_cookie_handle_consent() {
    if (isset($_POST['ga4_cookie_consent']) && $_POST['ga4_cookie_consent'] === 'allow') {
        // Set a cookie to remember user consent
        setcookie('ga4_cookie_consent', 'allow', time() + 365 * 24 * 3600, '/');
    }
}
add_action('init', 'ga4_cookie_handle_consent');

// Add a menu item to the admin sidebar
function ga4_cookie_add_menu_item() {
    add_menu_page(
        'GA4 Tracking ID',
        'GA4 Tracking ID',
        'manage_options',
        'ga4-tracking-id-settings',
        'ga4_cookie_render_settings_page'
    );
}
add_action('admin_menu', 'ga4_cookie_add_menu_item');

// Create the settings page
function ga4_cookie_render_settings_page() {
    // Check if the user has the necessary permissions
    if (!current_user_can('manage_options')) {
        return;
    }

    // Get the current tracking code from the plugin settings
    $current_tracking_code = get_option('ga4_cookie_tracking_code');

    // Save the new tracking code if the form is submitted
    if (isset($_POST['update_tracking_code'])) {
        $new_tracking_code = sanitize_text_field($_POST['tracking_code']);
        update_option('ga4_cookie_tracking_code', $new_tracking_code);
        $current_tracking_code = $new_tracking_code;
    }
    
    // Display the settings form
    ?>
    <div class="wrap">
        <h2>GA4 Tracking ID Settings</h2>
        <form method="post">
            <label for="tracking_code">GA4 Tracking ID:</label>
            <input type="text" id="tracking_code" name="tracking_code" value="<?php echo esc_attr($current_tracking_code); ?>">
            <p class="description">Enter your GA4 Tracking ID (e.g., G-XXXXXXXXXX).</p>
            <input type="submit" name="update_tracking_code" class="button-primary" value="Save Changes">
        </form>
    </div>
    <?php
}
?>
