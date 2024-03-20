<?php
/*
Plugin Name: IK Woo Repuestos
Description: Convierte Woocommerce en un gestionador de repuestos automotores
Version: 1.2.5
Author: Gabriel Caroprese / Dood Agency
Requires at least: 5.3
Requires PHP: 7.2
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$ik_woorepuestos_dir = dirname( __FILE__ );
$ik_woorepuestos_public_dir = plugin_dir_url(__FILE__ );

//Constantes de nav de directorios del plugin
define( 'IK_WOOREPUESTOS_PLUGIN_DIR', $ik_woorepuestos_dir);
define( 'IK_WOOREPUESTOS_PLUGIN_DIR_PUBLIC', $ik_woorepuestos_public_dir);

//if Woocommerce active
if (class_exists('woocommerce')) {
	require_once($ik_woorepuestos_dir . '/include/taxonomies.php');
	require_once($ik_woorepuestos_dir . '/include/class_repuesto_automotor.php');
	require_once($ik_woorepuestos_dir . '/include/general_functions.php');
	require_once($ik_woorepuestos_dir . '/include/ajax_functions.php');
	require_once($ik_woorepuestos_dir . '/include/shortcode.php');
	register_activation_hook( __FILE__, 'ik_woo_repuestos_crear_taxonomia_repuesto_automotor');
}

?>