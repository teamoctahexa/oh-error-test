<?php
/**
 * Plugin Name:       Error Log Test Plugin
 * Plugin URI:        https://octahexa.com
 * Description:       A plugin to generate various PHP errors for testing error logging systems.
 * Version:           1.0.0
 * Author:            Team OctaHexa
 * Author URI:        https://octahexa.com
 * License:           GPL2
 * Text Domain:       oh-error-test
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Force error reporting settings
 */
function oh_configure_error_reporting() {
    @ini_set('display_errors', 1);
    @ini_set('log_errors', 1);
    @error_reporting(E_ALL);
}
add_action('init', 'oh_configure_error_reporting', 1);

/**
 * Generate parse/syntax-like errors using eval
 */
function oh_generate_syntax_errors() {
    error_log('[Error Log Test] === Starting Syntax Error Tests ===');
    
    // Attempt 1: Invalid PHP syntax via eval
    try {
        eval('$test = "missing semicolon"'); // Missing semicolon
        eval('if ($test == true { echo "bad"; }'); // Missing closing parenthesis
        eval('<?php <?php double opening tags');
    } catch (ParseError $e) {
        error_log('[Error Log Test] Parse Error: ' . $e->getMessage());
    }
}

/**
 * Generate various PHP errors
 */
function oh_generate_various_errors() {
    error_log('[Error Log Test] === Starting Error Generation ===');
    error_log('[Error Log Test] Timestamp: ' . current_time('mysql'));
    
    // E_NOTICE: Undefined variable
    error_log('[Error Log Test] Triggering E_NOTICE...');
    $result = $undefined_variable_test;
    
    // E_WARNING: Include non-existent file
    error_log('[Error Log Test] Triggering E_WARNING...');
    @include('/path/to/non/existent/file_' . uniqid() . '.php');
    
    // E_USER_ERROR: User-generated error
    error_log('[Error Log Test] Triggering E_USER_ERROR...');
    trigger_error('[Error Log Test] This is a user-generated error!', E_USER_ERROR);
    
    // E_USER_WARNING
    trigger_error('[Error Log Test] This is a user warning!', E_USER_WARNING);
    
    // E_USER_NOTICE
    trigger_error('[Error Log Test] This is a user notice!', E_USER_NOTICE);
}

/**
 * Generate fatal errors (will stop execution)
 */
function oh_generate_fatal_error() {
    error_log('[Error Log Test] === About to trigger FATAL ERROR ===');
    error_log('[Error Log Test] Memory before crash: ' . memory_get_usage() . ' bytes');
    
    // Uncomment one of these to trigger different fatal errors:
    
    // Option 1: Call undefined function
    oh_this_function_does_not_exist();
    
    // Option 2: Call method on non-object
    // $null = null;
    // $null->methodCall();
    
    // Option 3: Type error
    // function oh_requires_array(array $param) { return $param; }
    // oh_requires_array("not an array");
}

/**
 * Test error logging capabilities
 */
function oh_test_error_logging() {
    // Direct error_log calls
    error_log('[Error Log Test] Simple error log message');
    error_log('[Error Log Test] Array data: ' . print_r(['test' => 'data', 'number' => 123], true));
    
    // Test different error log types
    error_log('[Error Log Test] Message to SAPI', 4);
    
    // Custom error handler test
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        error_log("[Error Log Test] Custom Handler - Error [$errno]: $errstr in $errfile on line $errline");
        return false; // Continue with normal error handler
    });
    
    // Trigger an error for custom handler
    strpos(); // Missing required parameters
    
    // Restore error handler
    restore_error_handler();
}

/**
 * Admin menu to trigger errors on demand
 */
function oh_add_error_test_menu() {
    add_management_page(
        'Error Log Test',
        'Error Log Test',
        'manage_options',
        'oh-error-log-test',
        'oh_error_test_page'
    );
}
add_action('admin_menu', 'oh_add_error_test_menu');

/**
 * Admin page for error testing
 */
function oh_error_test_page() {
    ?>
    <div class="wrap">
        <h1>Error Log Test Plugin</h1>
        <p>Click the buttons below to generate different types of errors in your PHP error log.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('oh_error_test', 'oh_error_test_nonce'); ?>
            
            <p>
                <button type="submit" name="oh_test_type" value="syntax" class="button">Generate Syntax-like Errors</button>
                <button type="submit" name="oh_test_type" value="various" class="button">Generate Various Errors</button>
                <button type="submit" name="oh_test_type" value="logging" class="button">Test Error Logging</button>
                <button type="submit" name="oh_test_type" value="fatal" class="button button-primary">Generate Fatal Error (Will Crash!)</button>
            </p>
        </form>
        
        <h3>Error Log Location</h3>
        <p>PHP Error Log: <code><?php echo ini_get('error_log') ?: 'Not configured'; ?></code></p>
        <?php if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG): ?>
            <p>WordPress Debug Log: <code><?php echo WP_CONTENT_DIR . '/debug.log'; ?></code></p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Handle form submissions
 */
function oh_handle_error_test() {
    if (!isset($_POST['oh_error_test_nonce']) || !wp_verify_nonce($_POST['oh_error_test_nonce'], 'oh_error_test')) {
        return;
    }
    
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['oh_test_type'])) {
        switch ($_POST['oh_test_type']) {
            case 'syntax':
                oh_generate_syntax_errors();
                break;
            case 'various':
                oh_generate_various_errors();
                break;
            case 'logging':
                oh_test_error_logging();
                break;
            case 'fatal':
                oh_generate_fatal_error();
                break;
        }
    }
}
add_action('admin_init', 'oh_handle_error_test');

/**
 * Add settings link on plugins page
 */
function oh_add_settings_link($links) {
    $settings_link = '<a href="tools.php?page=oh-error-log-test">Test Errors</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'oh_add_settings_link');

/**
 * Log plugin activation
 */
function oh_error_test_activation() {
    error_log('[Error Log Test] Plugin activated at ' . current_time('mysql'));
    error_log('[Error Log Test] PHP Version: ' . phpversion());
    error_log('[Error Log Test] WordPress Version: ' . get_bloginfo('version'));
}
register_activation_hook(__FILE__, 'oh_error_test_activation');

/**
 * Log plugin deactivation
 */
function oh_error_test_deactivation() {
    error_log('[Error Log Test] Plugin deactivated at ' . current_time('mysql'));
}
register_deactivation_hook(__FILE__, 'oh_error_test_deactivation');
