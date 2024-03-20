<?php
/*

Taxonomies Repuestos
Update: 16/11/2021
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

//Agrego el product type repuesto_automotor como taxonomy
function ik_woo_repuestos_crear_taxonomia_repuesto_automotor() {
    // If there is no membersonly product type taxonomy, add it.
    if ( ! get_term_by( 'slug', 'repuesto_automotor', 'product_type' ) ) {
        wp_insert_term( 'repuesto_automotor', 'product_type' );
    }
}

// Registro de la taxonomia Marca
function ik_woo_repuestos_crear_taxonomia_marca_repuesto()  {
$labels = array(
    'name'                       => 'Marca de Repuesto',
    'singular_name'              => 'Marca de Repuesto',
    'menu_name'                  => 'Marca de Repuesto',
    'all_items'                  => 'Todas las marcas',
    'parent_item'                => 'Marca Principal',
    'parent_item_colon'          => 'Marca Principal:',
    'new_item_name'              => 'Nueva Marca',
    'add_new_item'               => 'Agregar Nueva Marca',
    'edit_item'                  => 'Editar Marca',
    'update_item'                => 'Actualizar Marca',
    'separate_items_with_commas' => 'Separar las marcas con comas',
    'search_items'               => 'Buscar Marcas',
    'add_or_remove_items'        => 'Agregar o Eliminar Marcas',
    'choose_from_most_used'      => 'Elegir de las marcas populares',
);
$args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
);
register_taxonomy( 'marca_repuesto', 'product', $args );
}
add_action( 'init', 'ik_woo_repuestos_crear_taxonomia_marca_repuesto', 0 );


// Registro de la taxonomia Marca de automóvil
function ik_woo_repuestos_crear_taxonomia_marca_automovil()  {
$labels = array(
    'name'                       => 'Marca de Automóvil',
    'singular_name'              => 'Marca de Automóvil',
    'menu_name'                  => 'Marca de Automóvil',
    'all_items'                  => 'Todas las marcas',
    'parent_item'                => 'Marca Principal',
    'parent_item_colon'          => 'Marca Principal:',
    'new_item_name'              => 'Nueva Marca',
    'add_new_item'               => 'Agregar Nueva Marca',
    'edit_item'                  => 'Editar Marca',
    'update_item'                => 'Actualizar Marca',
    'separate_items_with_commas' => 'Separar las marcas con comas',
    'search_items'               => 'Buscar Marcas',
    'add_or_remove_items'        => 'Agregar o Eliminar Marcas',
    'choose_from_most_used'      => 'Elegir de las marcas populares',
);
$args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
);
register_taxonomy( 'marca_automovil', 'product', $args );
}
add_action( 'init', 'ik_woo_repuestos_crear_taxonomia_marca_automovil', 0 );


?>