<?php
/*

Class Repuesto Automotor
Update: 16/11/2021
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
    return;
}

//Doy inicio al product type Repuesto Automotor
add_action( 'init', 'ik_woo_repuestos_crear_product_type' );
function ik_woo_repuestos_crear_product_type() {

    class WC_Repuesto_Automotor extends WC_Product {
    		
        public function __construct( $product ) {
            $this->product_type = 'repuesto_automotor';
            $this->manage_stock = 'yes';
            parent::__construct($product);
        }
        
        //Devuelvo el tipo de producto
        public function get_type() {
            return 'repuesto_automotor';
        }
        
        //Devuelvo el precio
        public function get_price( $context = 'view' ) {
        
            return $this->get_prop( 'price', $context );
        }
        
        //Funcion para devolver cod explorer
        public function get_codigo($producto_id = 0) {
            
            $codexplorer = get_post_meta($producto_id, 'dato_codexplorer_repuesto', true);
            
            if ($codexplorer == NULL || $codexplorer == false || $codexplorer == ''){
                $codexplorer = '-';
            }
        
            return $codexplorer;
        }

        //Funcion para devolver aplicacion del repuesto
        public function get_aplicacion($producto_id = 0) {
            
            $codexplorer = get_post_meta($producto_id, 'dato_aplicacion_repuesto', true);
            
            if ($codexplorer == NULL || $codexplorer == false || $codexplorer == ''){
                $codexplorer = '-';
            }
        
            return $codexplorer;
        }

        //Funcion para devolver aplicacion del repuesto
        public function get_oem($producto_id = 0) {
            
            $oem = get_post_meta($producto_id, 'datos_oem_automotor', true);
            
            if ($oem == NULL || $oem == false || $oem == ''){
                $oem = '-';
            }
        
            return $oem;
        }

        //Funcion para devolver la marca OEM
        public function get_marca($producto_id = 0) {
            $marca = '';
            $terms = get_the_terms( $producto_id, 'marca_repuesto' );
            
            if ( is_wp_error( $terms ) || empty( $terms ) ) {
                return;
            }
         
            
            foreach ( $terms as $term ) {
                $marca = $term->name;
            }
            
            return $marca;
        }

        //Funcion para devolver el tipo de marca de vehiculo
        public function get_marca_uso($producto_id = 0) {
            $marca = '';
            $terms = get_the_terms( $producto_id, 'marca_automovil' );
            
            if ( is_wp_error( $terms ) || empty( $terms ) ) {
                return;
            }
         
            
            foreach ( $terms as $term ) {
                $marca = $term->name;
            }

            return $marca;
        }

        //Funcion para devolver la descrip corta
        public function get_descripcion_listado($producto_id = 0) {
            
            $descripcion = get_the_excerpt($producto_id);
        
            return $descripcion;
        }
        
        //Funcion para devolver num original
        public function get_img($producto_id = 0, $size = 'listado') {
            
            $imagenes = wp_get_attachment_image_src( get_post_thumbnail_id( $producto_id ), 'single-post-thumbnail' );
            
            if (isset($imagenes[0])){
                $imagensrc = $imagenes[0];
            } else {
                $imagensrc = wc_placeholder_img_src();
            }

            if ($size == 'listado'){
                $stylewidth = ' width="20" height="20"';
            } else if ($size == 'thumbnail'){
                $stylewidth = ' width="150"';
            } else {
                $stylewidth = ' width="200"';
            }

            $img_product = '<img src="'.$imagensrc.'" data-id="'.$producto_id.'" '.$stylewidth.'>';
            
            return $img_product;
        }

        //Funcion para devolver num original
        public function get_botonpago($producto_id = 0, $link_actual = '/', $texto = 'Agregar') {
            $texto = sanitize_text_field($texto);
            $link_actual = esc_url_raw($link_actual);
            
            $boton_de_pago = '<a class="button product_type_repuesto_automotor add_to_cart_button ajax_add_to_cart" href="'.$link_actual.'add-to-cart='.$producto_id.'">'.$texto.'</a>';
            
            return $boton_de_pago;
        }
    
    }
    
    //Agrego el tipo de producto repuesto_automotor
    add_filter( 'woocommerce_product_class', 'ik_woo_repuestos_agregar_product_type', 10, 2 );
    function ik_woo_repuestos_agregar_product_type( $classname, $product_type ) {
        if ( $product_type == 'repuesto_automotor' ) { 
            $classname = 'WC_Repuesto_Automotor';
        }
        return $classname;
    }
}
?>