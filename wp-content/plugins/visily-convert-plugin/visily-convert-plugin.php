<?php
/**
 * Plugin Name:       Visily Convert Plugin
 * Plugin URI:        https://visily.ai/plugins/visily-convert
 * Description:       A powerful WordPress plugin for Visily Convert project. Provides conversion optimization tools, REST API endpoints, and admin management features.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Visily Team
 * Author URI:        https://visily.ai
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       visily-convert-plugin
 * Domain Path:       /languages
 *
 * @package Visily_Convert_Plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ============================================================
// Constants
// ============================================================

define( 'VISILY_CONVERT_PLUGIN_VERSION', '1.0.0' );
define( 'VISILY_CONVERT_PLUGIN_FILE', __FILE__ );
define( 'VISILY_CONVERT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VISILY_CONVERT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VISILY_CONVERT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// ============================================================
// Activation & Deactivation Hooks
// ============================================================

/**
 * Plugin activation callback.
 *
 * Runs when the plugin is activated. Creates database tables,
 * sets default options, and flushes rewrite rules.
 *
 * @return void
 */
function visily_convert_plugin_activate() {
    // Set default plugin options on activation.
    if ( ! get_option( 'visily_convert_plugin_version' ) ) {
        add_option( 'visily_convert_plugin_version', VISILY_CONVERT_PLUGIN_VERSION );
        add_option(
            'visily_convert_plugin_settings',
            array(
                'enable_tracking'  => true,
                'api_endpoint'     => '',
                'conversion_goals' => array(),
            )
        );
    }

    // Flush rewrite rules for custom endpoints.
    flush_rewrite_rules();
}
register_activation_hook( VISILY_CONVERT_PLUGIN_FILE, 'visily_convert_plugin_activate' );

/**
 * Plugin deactivation callback.
 *
 * Runs when the plugin is deactivated. Cleans up temporary data
 * and flushes rewrite rules.
 *
 * @return void
 */
function visily_convert_plugin_deactivate() {
    // Clean up scheduled events.
    $timestamp = wp_next_scheduled( 'visily_convert_plugin_cleanup' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'visily_convert_plugin_cleanup' );
    }

    // Flush rewrite rules.
    flush_rewrite_rules();
}
register_deactivation_hook( VISILY_CONVERT_PLUGIN_FILE, 'visily_convert_plugin_deactivate' );

// ============================================================
// Text Domain
// ============================================================

/**
 * Load plugin textdomain for translations.
 *
 * @return void
 */
function visily_convert_plugin_load_textdomain() {
    load_plugin_textdomain(
        'visily-convert-plugin',
        false,
        dirname( VISILY_CONVERT_PLUGIN_BASENAME ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'visily_convert_plugin_load_textdomain' );

// ============================================================
// Scripts & Styles Enqueuing
// ============================================================

/**
 * Enqueue frontend scripts and styles.
 *
 * @return void
 */
function visily_convert_plugin_enqueue_scripts() {
    // Frontend stylesheet.
    wp_enqueue_style(
        'visily-convert-plugin-frontend',
        VISILY_CONVERT_PLUGIN_URL . 'assets/css/frontend.css',
        array(),
        VISILY_CONVERT_PLUGIN_VERSION
    );

    // Frontend JavaScript.
    wp_enqueue_script(
        'visily-convert-plugin-frontend',
        VISILY_CONVERT_PLUGIN_URL . 'assets/js/frontend.js',
        array( 'jquery' ),
        VISILY_CONVERT_PLUGIN_VERSION,
        true
    );

    // Pass plugin data to JavaScript.
    wp_localize_script(
        'visily-convert-plugin-frontend',
        'visilyConvertPlugin',
        array(
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'restUrl'   => rest_url( 'visily-convert/v1/' ),
            'nonce'     => wp_create_nonce( 'wp_rest' ),
            'siteUrl'   => get_site_url(),
            'version'   => VISILY_CONVERT_PLUGIN_VERSION,
        )
    );
}
add_action( 'wp_enqueue_scripts', 'visily_convert_plugin_enqueue_scripts' );

/**
 * Enqueue admin scripts and styles.
 *
 * @param string $hook_suffix The current admin page hook suffix.
 * @return void
 */
function visily_convert_plugin_enqueue_admin_scripts( $hook_suffix ) {
    // Only load on plugin admin pages.
    if ( strpos( $hook_suffix, 'visily-convert' ) === false ) {
        return;
    }

    // Admin stylesheet.
    wp_enqueue_style(
        'visily-convert-plugin-admin',
        VISILY_CONVERT_PLUGIN_URL . 'assets/css/admin.css',
        array(),
        VISILY_CONVERT_PLUGIN_VERSION
    );

    // Admin JavaScript.
    wp_enqueue_script(
        'visily-convert-plugin-admin',
        VISILY_CONVERT_PLUGIN_URL . 'assets/js/admin.js',
        array( 'jquery', 'wp-api' ),
        VISILY_CONVERT_PLUGIN_VERSION,
        true
    );

    // Pass admin data to JavaScript.
    wp_localize_script(
        'visily-convert-plugin-admin',
        'visilyConvertAdmin',
        array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'visily_convert_admin_nonce' ),
            'strings' => array(
                'saveSuccess' => esc_html__( 'Settings saved successfully.', 'visily-convert-plugin' ),
                'saveError'   => esc_html__( 'Error saving settings. Please try again.', 'visily-convert-plugin' ),
                'confirm'     => esc_html__( 'Are you sure?', 'visily-convert-plugin' ),
            ),
        )
    );
}
add_action( 'admin_enqueue_scripts', 'visily_convert_plugin_enqueue_admin_scripts' );

// ============================================================
// Admin Menu
// ============================================================

/**
 * Register admin menu pages.
 *
 * @return void
 */
function visily_convert_plugin_admin_menu() {
    // Main menu page.
    add_menu_page(
        esc_html__( 'Visily Convert', 'visily-convert-plugin' ),
        esc_html__( 'Visily Convert', 'visily-convert-plugin' ),
        'manage_options',
        'visily-convert',
        'visily_convert_plugin_admin_page',
        'dashicons-chart-line',
        30
    );

    // Settings submenu.
    add_submenu_page(
        'visily-convert',
        esc_html__( 'Settings', 'visily-convert-plugin' ),
        esc_html__( 'Settings', 'visily-convert-plugin' ),
        'manage_options',
        'visily-convert-settings',
        'visily_convert_plugin_settings_page'
    );

    // Reports submenu.
    add_submenu_page(
        'visily-convert',
        esc_html__( 'Reports', 'visily-convert-plugin' ),
        esc_html__( 'Reports', 'visily-convert-plugin' ),
        'manage_options',
        'visily-convert-reports',
        'visily_convert_plugin_reports_page'
    );
}
add_action( 'admin_menu', 'visily_convert_plugin_admin_menu' );

// ============================================================
// Admin Page Callbacks
// ============================================================

/**
 * Render the main admin dashboard page.
 *
 * @return void
 */
function visily_convert_plugin_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'visily-convert-plugin' ) );
    }
    ?>
    <div class="wrap visily-convert-admin">
        <h1 class="wp-heading-inline">
            <?php esc_html_e( 'Visily Convert Dashboard', 'visily-convert-plugin' ); ?>
        </h1>
        <hr class="wp-header-end">

        <div class="visily-convert-dashboard">
            <div class="visily-convert-card">
                <h2><?php esc_html_e( 'Overview', 'visily-convert-plugin' ); ?></h2>
                <p>
                    <?php esc_html_e( 'Welcome to Visily Convert Plugin. Use the menu to configure your conversion tracking settings.', 'visily-convert-plugin' ); ?>
                </p>
                <p>
                    <strong><?php esc_html_e( 'Plugin Version:', 'visily-convert-plugin' ); ?></strong>
                    <?php echo esc_html( VISILY_CONVERT_PLUGIN_VERSION ); ?>
                </p>
                <p>
                    <strong><?php esc_html_e( 'REST API Namespace:', 'visily-convert-plugin' ); ?></strong>
                    <code>visily-convert/v1</code>
                </p>
            </div>

            <div class="visily-convert-card">
                <h2><?php esc_html_e( 'Quick Links', 'visily-convert-plugin' ); ?></h2>
                <ul>
                    <li>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=visily-convert-settings' ) ); ?>">
                            <?php esc_html_e( 'Plugin Settings', 'visily-convert-plugin' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=visily-convert-reports' ) ); ?>">
                            <?php esc_html_e( 'View Reports', 'visily-convert-plugin' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( rest_url( 'visily-convert/v1/' ) ); ?>" target="_blank">
                            <?php esc_html_e( 'REST API Endpoint', 'visily-convert-plugin' ); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render the settings admin page.
 *
 * @return void
 */
function visily_convert_plugin_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'visily-convert-plugin' ) );
    }

    // Handle form submission.
    if ( isset( $_POST['visily_convert_settings_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['visily_convert_settings_nonce'] ) ), 'visily_convert_save_settings' ) ) {

        $settings = array(
            'enable_tracking' => isset( $_POST['enable_tracking'] ) ? (bool) $_POST['enable_tracking'] : false,
            'api_endpoint'    => isset( $_POST['api_endpoint'] ) ? esc_url_raw( wp_unslash( $_POST['api_endpoint'] ) ) : '',
        );

        update_option( 'visily_convert_plugin_settings', $settings );

        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'visily-convert-plugin' ) . '</p></div>';
    }

    $settings = get_option(
        'visily_convert_plugin_settings',
        array(
            'enable_tracking' => true,
            'api_endpoint'    => '',
        )
    );
    ?>
    <div class="wrap visily-convert-admin">
        <h1><?php esc_html_e( 'Visily Convert Settings', 'visily-convert-plugin' ); ?></h1>

        <form method="post" action="">
            <?php wp_nonce_field( 'visily_convert_save_settings', 'visily_convert_settings_nonce' ); ?>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="enable_tracking">
                            <?php esc_html_e( 'Enable Tracking', 'visily-convert-plugin' ); ?>
                        </label>
                    </th>
                    <td>
                        <input
                            type="checkbox"
                            id="enable_tracking"
                            name="enable_tracking"
                            value="1"
                            <?php checked( ! empty( $settings['enable_tracking'] ) ); ?>
                        >
                        <label for="enable_tracking">
                            <?php esc_html_e( 'Enable conversion tracking on the frontend', 'visily-convert-plugin' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="api_endpoint">
                            <?php esc_html_e( 'API Endpoint', 'visily-convert-plugin' ); ?>
                        </label>
                    </th>
                    <td>
                        <input
                            type="url"
                            id="api_endpoint"
                            name="api_endpoint"
                            class="regular-text"
                            value="<?php echo esc_attr( $settings['api_endpoint'] ); ?>"
                            placeholder="https://api.visily.ai/convert"
                        >
                        <p class="description">
                            <?php esc_html_e( 'The external API endpoint for Visily Convert integration.', 'visily-convert-plugin' ); ?>
                        </p>
                    </td>
                </tr>
            </table>

            <?php submit_button( esc_html__( 'Save Settings', 'visily-convert-plugin' ) ); ?>
        </form>
    </div>
    <?php
}

/**
 * Render the reports admin page.
 *
 * @return void
 */
function visily_convert_plugin_reports_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'visily-convert-plugin' ) );
    }
    ?>
    <div class="wrap visily-convert-admin">
        <h1><?php esc_html_e( 'Visily Convert Reports', 'visily-convert-plugin' ); ?></h1>
        <p>
            <?php esc_html_e( 'Conversion reports will be displayed here once tracking data is available.', 'visily-convert-plugin' ); ?>
        </p>
    </div>
    <?php
}

// ============================================================
// REST API
// ============================================================

/**
 * Register REST API routes.
 *
 * @return void
 */
function visily_convert_plugin_register_rest_routes() {
    // GET /wp-json/visily-convert/v1/status
    register_rest_route(
        'visily-convert/v1',
        '/status',
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'visily_convert_plugin_rest_status',
            'permission_callback' => '__return_true',
        )
    );

    // GET /wp-json/visily-convert/v1/conversions
    register_rest_route(
        'visily-convert/v1',
        '/conversions',
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'visily_convert_plugin_rest_get_conversions',
            'permission_callback' => 'visily_convert_plugin_rest_permissions',
            'args'                => array(
                'per_page' => array(
                    'default'           => 10,
                    'sanitize_callback' => 'absint',
                    'validate_callback' => function ( $param ) {
                        return is_numeric( $param ) && $param > 0 && $param <= 100;
                    },
                ),
                'page'     => array(
                    'default'           => 1,
                    'sanitize_callback' => 'absint',
                    'validate_callback' => function ( $param ) {
                        return is_numeric( $param ) && $param > 0;
                    },
                ),
            ),
        )
    );

    // POST /wp-json/visily-convert/v1/conversions
    register_rest_route(
        'visily-convert/v1',
        '/conversions',
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'visily_convert_plugin_rest_create_conversion',
            'permission_callback' => 'visily_convert_plugin_rest_permissions',
            'args'                => array(
                'event'   => array(
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field',
                    'validate_callback' => function ( $param ) {
                        return ! empty( $param );
                    },
                ),
                'page_id' => array(
                    'required'          => false,
                    'sanitize_callback' => 'absint',
                ),
                'meta'    => array(
                    'required'          => false,
                    'sanitize_callback' => function ( $param ) {
                        return is_array( $param ) ? array_map( 'sanitize_text_field', $param ) : array();
                    },
                ),
            ),
        )
    );

    // GET /wp-json/visily-convert/v1/settings
    register_rest_route(
        'visily-convert/v1',
        '/settings',
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'visily_convert_plugin_rest_get_settings',
            'permission_callback' => 'visily_convert_plugin_rest_admin_permissions',
        )
    );
}
add_action( 'rest_api_init', 'visily_convert_plugin_register_rest_routes' );

/**
 * Permission callback for authenticated REST endpoints.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return bool|WP_Error
 */
function visily_convert_plugin_rest_permissions( $request ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error(
            'rest_forbidden',
            esc_html__( 'Authentication required.', 'visily-convert-plugin' ),
            array( 'status' => 401 )
        );
    }
    return true;
}

/**
 * Permission callback for admin-only REST endpoints.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return bool|WP_Error
 */
function visily_convert_plugin_rest_admin_permissions( $request ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return new WP_Error(
            'rest_forbidden',
            esc_html__( 'Administrator access required.', 'visily-convert-plugin' ),
            array( 'status' => 403 )
        );
    }
    return true;
}

/**
 * REST API callback: Return plugin status.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response
 */
function visily_convert_plugin_rest_status( $request ) {
    $data = array(
        'status'  => 'active',
        'version' => VISILY_CONVERT_PLUGIN_VERSION,
        'name'    => esc_html__( 'Visily Convert Plugin', 'visily-convert-plugin' ),
        'site'    => get_site_url(),
    );

    return rest_ensure_response( $data );
}

/**
 * REST API callback: Get conversions list.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response
 */
function visily_convert_plugin_rest_get_conversions( $request ) {
    $per_page = $request->get_param( 'per_page' );
    $page     = $request->get_param( 'page' );

    // Placeholder — integrate with actual data source as needed.
    $data = array(
        'conversions' => array(),
        'total'       => 0,
        'per_page'    => $per_page,
        'page'        => $page,
    );

    return rest_ensure_response( $data );
}

/**
 * REST API callback: Record a conversion event.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response|WP_Error
 */
function visily_convert_plugin_rest_create_conversion( $request ) {
    $event   = $request->get_param( 'event' );
    $page_id = $request->get_param( 'page_id' );
    $meta    = $request->get_param( 'meta' );

    if ( empty( $event ) ) {
        return new WP_Error(
            'missing_event',
            esc_html__( 'Event parameter is required.', 'visily-convert-plugin' ),
            array( 'status' => 400 )
        );
    }

    // Placeholder — store or process conversion event as needed.
    $conversion = array(
        'id'         => wp_generate_uuid4(),
        'event'      => $event,
        'page_id'    => $page_id,
        'meta'       => $meta,
        'created_at' => gmdate( 'c' ),
        'user_id'    => get_current_user_id(),
    );

    return rest_ensure_response(
        array(
            'success'    => true,
            'conversion' => $conversion,
        )
    );
}

/**
 * REST API callback: Get plugin settings.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response
 */
function visily_convert_plugin_rest_get_settings( $request ) {
    $settings = get_option(
        'visily_convert_plugin_settings',
        array(
            'enable_tracking' => true,
            'api_endpoint'    => '',
        )
    );

    return rest_ensure_response( $settings );
}
