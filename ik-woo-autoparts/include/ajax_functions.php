<?php
/*

Ajax Functions
Update: 16/11/2021
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

//Ajax para cargar datos del repuesto seleccionado
add_action('wp_ajax_nopriv_ik_woo_repuestos_listado_ajax_dato_repuesto', 'ik_woo_repuestos_listado_ajax_dato_repuesto');
add_action( 'wp_ajax_ik_woo_repuestos_listado_ajax_dato_repuesto', 'ik_woo_repuestos_listado_ajax_dato_repuesto');
function ik_woo_repuestos_listado_ajax_dato_repuesto(){
    
    //Respuesta por defecto
    $datos_repuesto = 'Error';
    
    if (isset($_POST['producto_id'])){
        $producto_id = absint($_POST['producto_id']);
        $producto = wc_get_product( $producto_id );
        
        if ($producto != NULL){
        	$repuesto = new WC_Repuesto_Automotor($producto);
        	
        	$linkActual = ik_woo_repuestos_link_actual(true);
        	
        	//Chequeo estado del stock
        	if (wc_get_low_stock_amount( $producto ) <= $producto->get_stock_quantity()){
        	    $stock = '<div class="stock_status_critico stock_status">Stock Disponible</div>';  
        	} else {
        	    $stock = '<div class="stock_status_disponible stock_status">Stock Disponible</div>';  
        	}
        	 
        	global  $woocommerce;
        	$tipoMoneda = get_woocommerce_currency_symbol();
        	$precio_lista = wc_price( $producto->get_regular_price(), array( 'currency' => $tipoMoneda ));
        	$precio = wc_price( $producto->get_price(), array( 'currency' => $tipoMoneda ));
    
            $datos_repuesto = '
            <div class="ik_woo_repuestos_automotor_contenido">
                <div class="ik_woo_repuestos_automotor_actual" repuesto_id="'.$producto_id.'">
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_img">'.$repuesto->get_img($producto_id, 'medium').'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_codexplorer"><b>C&oacute;digo:</b> '.$repuesto->get_codigo($producto_id).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_precio_lista"><b>Precio de Lista:</b> '.$precio_lista.'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_precio"><b>Precio:</b> '.$precio.'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_stock">'.$stock.'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_aplicacion"><b>Aplicaci&oacute;n:</b> '.$repuesto->get_aplicacion($producto_id).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_marca"><b>Marca:</b> '.$repuesto->get_marca($producto_id).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_oem"><b>OEM:</b> '.$repuesto->get_oem($producto_id).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_descripcion">'.$repuesto->get_descripcion_listado($producto_id).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_boton">'.$repuesto->get_botonpago($producto_id, $linkActual, 'Agregar al Carrito').'</div>
                </div>
            </div>';
        }
        
    }
    echo json_encode( $datos_repuesto );
    wp_die();         
}
?>