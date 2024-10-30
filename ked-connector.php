<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * this starts the plugin.
 *
 * @link:       https://www.kosmosesync.com
 * @since             1.0.0
 * @package           Kosmos_Esync_Dashboard_Connector
 *
 * @wordpress-plugin
 * Plugin Name:       Kosmos eSync Dashboard Connector
 * Plugin URI:        https://www.kosmosesync.com
 * Description:       Access to the Kosmos eSync Dashboard via Wordpress Dashboard
 * Version:           1.0.3
 * Requires at least: 4.0
 * Author:            Kosmos Central
 * Author URI:        https://www.kosmoscentral.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kosmos-esync-dashboard-connector
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ked-connector-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ked-connector-deactivator.php';

/** This action is documented in includes/class-ked-connector-activator.php */
register_activation_hook( __FILE__, array( 'Kosmos_Esync_Dashboard_Connector_Activator', 'activate' ) );

/** This action is documented in includes/class-ked-connector-deactivator.php */
register_activation_hook( __FILE__, array( 'Kosmos_Esync_Dashboard_Connector_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ked-connector.php';


/**
 * Create dependency on woocommerce plugin
 */
add_action( 'admin_init', 'child_plugin_has_parent_plugin' );
function child_plugin_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        add_action( 'admin_notices', 'child_plugin_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function child_plugin_notice(){
    ?><div class="error"><p>Attention: Kosmos eSync Connector requires the WooCommerce plugin to be installed and active.</p></div><?php
}

/**
 * Add settings link to plugin list display
 */

function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=ked_connector-plugin">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );

/**
 * Include a link to the plugin settings page in the Dashboard menu
 */

add_action('admin_menu', 'ked_connector_plugin_setup_menu');
 
function ked_connector_plugin_setup_menu(){
        add_menu_page( 'Kosmos eSync Dashboard', 'Kosmos eSync Dashboard', 'manage_options', 'ked_connector-plugin', 'ked_connector_init', plugin_dir_url( __FILE__ ) . 'public/images/kosmos_icon_small.png' );
}

add_action( 'admin_bar_menu', 'link_to_esync', 999 );

function link_to_esync( $wp_admin_bar ) {
	$args = array(
		'id'    => 'ked-connector',
		'title' => 'Kosmos eSync Dashboard',
		'href'  => 'https://www.kosmosesync.com',
		'meta'  => array( 'class' => 'link_to_esync', 'target' => '_blank' )
	);
	$wp_admin_bar->add_node( $args );
}

/**
 * Enqueue css and javascript to the plugin settings page.
 */

function kedc_support() {
    wp_register_style('kedc_support', plugins_url('public/css/ked-connector-public.css',__FILE__ ));
    wp_enqueue_style('kedc_support');
    wp_register_script( 'kedc_support', plugins_url('public/js/ked-connector-public.js',__FILE__ ));
    wp_enqueue_script('kedc_support');
}

add_action( 'admin_init','kedc_support');

/**
 * Plugin settings page content
 */
 
function ked_connector_init(){
		
		//Plugin Header goes HERE
		echo "<img class='kosmos-plugin-logo' src='" . plugins_url( 'public/images/kosmos_logo.png', __FILE__ ) . "' alt='Kosmos eSync Dashboard Connector' title='Kosmos eSync Dashboard Connector'/> <h1>Kosmos eSync Dashboard Connector</h1>"; 
		
		// Tabs
		echo "<ul class='kedc-tabs'>
				<li><a id='kedcintro' href='#kedcintroduction'>Introduction</a></li>
				<li><a href='https://www.kosmosesync.com' target='_blank'>Dashboard</a></li>
				<li><a href='https://help.kosmosesync.com/' target='_blank'>Help Docs</a></li>
				<li><a href='https://www.youtube.com/user/KosmosCentralTV/playlists' target='_blank'>Video Tutorials</a></li>
				<li><a href='https://supportcenter.kosmoscentral.com/support/tickets/new' target='_blank'>Request Support</a></li>
				<li class='emphasize'><a href='https://www.kosmoscentral.com/esync-cloud-pricing' target='_blank'>14 Day Free Trial</a></li>
			</ul>

			<hr />";
		
		//Introduction
		echo "
		<div id='kedcintroduction'>

		<p>Kosmos eSync Dashboard Connector allows you to access the Kosmos eSync Dashboard from inside WordPress. Existing eSync users can run Tasks to sync inventory and orders between applications and manage integration settings.</p>

		<p>Kosmos eSync integrates data between leading point of sale, ERP, marketplace and ecommerce applications.</p>
		
		<h3>Current integrations include:</h3>
		
		<ul>
			<li>- WooCommerce</li>
			<li>- Revel Systems Point of Sale</li>
			<li>- Lightspeed Retail Point of Sale</li>
			<li>- Vend Point of Sale</li>
			<li>- Acumatica ERP</li>
			<li>- Integrate Amazon and eBay using marketplace tools</li>
			<li>- Clover Point of Sale</li> 
			<li>- View a complete list of integrations <a href='https://www.kosmoscentral.com/connections' target='_blank'>here</a></li>
		</ul>

		<h3>Features of Kosmos eSync Dashboard Connector:</h3>
		
		<ul>
			<li>- Quick link to Kosmos eSync Dashboard is added to the top menu of your WordPress admin area</li>
			<li>- Quick link to help documentation</li>
			<li>- Access to video tutorials</li>
			<li>- Access to support center</li>
			<li>- Requires the WooCommerce plugin to be installed</li>
		</ul>


		<h3>Frequently asked questions:</h3>

		<p><strong>What is Kosmos eSync?</strong><br />An integration platform to streamline order and inventory management for businesses that sell both online and through brick and mortar stores.</p>
		<p><strong>How do I make an eSync account?</strong><br />Visit us at <a href='https://www.kosmoscentral.com/esync-cloud-pricing' target='_blank'>kosmoscentral.com</a>, and select the free trial button to register a new account.</p>
		<p><strong>I need help configuring my settings</strong><br />See our <a href='https://help.kosmosesync.com/' target='_blank'>help documentation</a>, or visit the <a href='https://supportcenter.kosmoscentral.com/support/tickets/new' target='_blank'>support center</a> to request assistance.</p>

		</div>
		";
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Kosmos_Esync_Dashboard_Connector() {

	$plugin = new Kosmos_Esync_Dashboard_Connector();
	$plugin->run();

}
run_Kosmos_Esync_Dashboard_Connector();
