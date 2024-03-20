<?php
/*

Shortcode
Update: 18/11/2021
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

//Funcion para crear una tabla con el listado de los repuestos automotores
function ik_woo_repuestos_listado_productos(){

    if (isset($_GET['marca'])){
        $filtro_buscar['taxonomy']['marca_repuesto'] = sanitize_text_field($_GET['marca']);
    }
    if (isset($_GET['product_cat'])){
        $filtro_buscar['taxonomy']['product_cat']= sanitize_text_field($_GET['product_cat']);
    }
    if (isset($_GET['marca_automovil'])){
        $filtro_buscar['taxonomy']['marca_automovil'] = sanitize_text_field($_GET['marca_automovil']);
    }
    if (isset($_GET['codexplorer'])){
        $filtro_buscar['meta'] = sanitize_text_field($_GET['codexplorer']);
    }
    if (isset($_GET['keyword_repuestos'])){
        $filtro_buscar['keyword'] = sanitize_text_field($_GET['keyword_repuestos']);
    }
    
    if (isset($filtro_buscar)) {
        $filtrado = $filtro_buscar;
    } else {
        $filtrado = NULL;
    }
	// Veo datos de ordenamiento	
	$orden = 'cod';	
	if (isset($_GET["orden"])){		
		if ($_GET["orden"] == 'oem'){			
			$orden = 'oem';		
		} else if ($_GET["orden"] == 'desc'){
			$orden = 'desc';		
		} else if ($_GET["orden"] == 'apl'){			
			$orden = 'apl';		
		} else if ($_GET["orden"] == 'precio'){			
			$orden = 'precio';		
		}	
	}	
	
	// I get the value for order of listing	
	$ordenDir = 'DESC';	
	$orderdir = 'desc';	
	$ordenClass = 'orden desc';	
	
	if (isset($_GET["ordendir"])){
		if ($_GET["ordendir"] != 'desc'){
			$ordenDir = 'ASC';			
			$ordendir = 'asc';			
			$ordenClass = 'orden '.$ordendir;		
		}
	}		
	
	$vacioclass = '';
	$codClass = $vacioclass;
	$descClass = $vacioclass;
	$aplClass = $vacioclass;
	$oemClass = $vacioclass;
	$precioClass = $vacioclass;	
	
	if ($orden !== 'cod'){
		if ($orden == 'desc'){			
			$descClass = $ordenClass;		
		}else if ($orden == 'apl'){			
			$aplClass = $ordenClass;	
		} else if ($orden == 'oem'){
			$oemClass = $ordenClass;	
		} else if ($orden == 'precio'){			
			$precioClass = $ordenClass;		
		}	
	} else {
		$codClass = $ordenClass;
	}
	
    $productos_repuestos = ik_woo_repuestos_get_listado_repuestos($filtrado, $orden, $ordenDir);

    $linkActual = ik_woo_repuestos_link_actual(true);

    $url_explode = explode('?', $_SERVER['REQUEST_URI'], 2);
    $link_sin_parametros = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'] . $url_explode[0];;
    
    //Si hubo busquedas
    if (isset($_GET['codexplorer'])){
        $valor_search_codigo = sanitize_text_field($_GET['codexplorer']);
    } else {
        $valor_search_codigo = '';
    }    
    if (isset($_GET['keyword_repuestos'])){
        $valor_search_keyword = sanitize_text_field($_GET['keyword_repuestos']);
    } else {
        $valor_search_keyword = '';
    }
    
    $listado_repuestos = '
    <style>
    #ik_woo_repuestos_listado{
        max-width: 79%;
        min-width: 995px;
        float: left;
        margin-right: 1%;
        font-size: 13.5px;
        display: grid;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_titulos {
        display: inline-block;
        background: #f1f1f1;
        padding-right: 20px;
        margin-bottom: 9px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_titulo, #ik_woo_repuestos_listado .ik_woo_repuestos_listado_dato{
        float: left;
        padding: 5px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_titulos div{
        color: #333;
        font-weight: 500;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_codexplorer{
        width: 65px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_dato{
        cursor: pointer;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_aplicacion{
        width: 230px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_oem{
        width: 110px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_descripcion{
        width: 280px;
        overflow: hidden;
        height: 35px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_precio{
        width: 85px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_img{
        width: 65px;
        text-align: center;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_boton{
        width: 150px;
        display: grid;
    }
     #ik_woo_repuestos_listado .ik_woo_repuestos_listado_boton.ik_woo_repuestos_listado_dato span{
        background: #333;
        text-align: center;
        padding: 4px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_datos .ik_woo_repuestos_listado_dato.ik_woo_repuestos_listado_boton{
        position: relative;
        top: -4px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_contenido_datos{
        max-height: 800px;
        overflow-y: auto;
        display: block;
        margin-top: -9px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_datos{
        border-bottom: 1px solid #ccc;
        display: inline-block;
        margin-top: -9px;
        padding: 14px 0 7px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_datos:first-child{
        margin-top: 0px! important;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_seleccionado {
        background: #3b5998;
        color: #fff;
    }
    #ik_woo_repuestos_filtro .ik_woo_repuestos_filtrar_select_link {
        padding: 7.4px 15px;
        margin: 3.5px;
    }
    #ik_woo_repuestos_filtro .ik_woo_repuestos_filtro_marcas_automoviles ul {
        list-style: none;
        margin: 12px 0;
        display: inline-block;
    }
    #ik_woo_repuestos_filtro .ik_woo_repuestos_filtro_marcas_automoviles ul li::marker  {
        display: none;
    }
    #ik_woo_repuestos_filtro .ik_woo_repuestos_filtro_marcas_automoviles ul li {
        list-style: none;
        float: left;
        padding: 5px 12px;
        background: #444;
        margin: 2px;
    }       
    #ik_woo_repuestos_filtro .ik_woo_repuestos_filtro_marcas_automoviles ul li.para_seleccionado {
        background: red;
    }
    #ik_woo_repuestos_filtro .ik_woo_repuestos_filtro_marcas_automoviles ul li a{
        color: #fff;
    }
    #ik_woo_repuestos_buscar .search-field {
        padding: 7px 12px;
        line-height: 1;
    }
    .ik_woo_repuestos_loading_img {
        width: 300px;
        max-width: 20%;
        margin: 0 auto;
    }
     .ik_woo_repuestos_loading_img img{
        text-align: center;
        max-width: 50px;
        margin: 20px auto;
        display: block;
    }
    #ik_woo_repuestos_detalle_producto_actual{
        float: left;
        max-width: 20%;
        display: none;
        position: relative;
        z-index: 99999999;
    }
	#ik_woo_repuestos_listado .ik_woo_repuestos_listado_titulo .orden:after {
    content: \' \2191\';
	}
	#ik_woo_repuestos_listado .ik_woo_repuestos_listado_titulo .orden.desc:after {
    content: \' \2193\';
	}
    /* Para Divi CSS */
    .et_pb_section_0 {
        padding: 0! important;
    }
    .container, .et_pb_row, .et_pb_slider .et_pb_container, .et_pb_fullwidth_section .et_pb_title_container, .et_pb_fullwidth_section .et_pb_title_featured_container, .et_pb_fullwidth_header:not(.et_pb_fullscreen) .et_pb_fullwidth_header_container {
        max-width: 1650px! important;
    }
    .et_pb_row {
        width: 85%! important;
    }
    /*
    Fin de CSS para Divi
    */
    #ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_automotor_contenido{
        margin: 0 auto;
        border: 1px solid;
        padding: 20px;
    }
    #ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_listado_img{
        text-align: center;
        margin-bottom: 25px;
    }
    #ik_woo_repuestos_detalle_producto_actual .stock_status_critico{
        background: red;
    }
    #ik_woo_repuestos_detalle_producto_actual .stock_status_disponible{
        background: green;
    }
    #ik_woo_repuestos_detalle_producto_actual .stock_status {
        color: #fff;
        padding: 13px;
        text-align: center;
        margin: 7px 0;
    }
    #ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_listado_descripcion{
        margin: 7px 0 15px;
    }
    #ik_woo_repuestos_detalle_producto_actual .add_to_cart_button{
        text-align: center;
        margin: 0 auto;
        display: block;
        background: #333;
        color: #fff;
        padding: 12px;
    }
    #ik_woo_repuestos_filtro, #ik_woo_repuestos_buscar{
        width: 100%;
        display: inline-block;
        margin: 0 2px;
    }
    #ik_woo_repuestos_filtro .ik_woo_repuestos_filtro_marcas_automoviles, #ik_woo_repuestos_filtro .ik_woo_repuestos_filtro_select, #ik_woo_repuestos_buscar .ik_woo_repuestos_buscar_codigo, #ik_woo_repuestos_buscar .ik_woo_repuestos_buscar_general, #ik_woo_repuestos_buscar .ik_woo_repuestos_buscar_btn {
        float: left;
    }
     #ik_woo_repuestos_buscar .ik_woo_repuestos_buscar_btn {
        margin-left: 5px;
    }
    #ik_woo_repuestos_filtro .ik_woo_repuestos_filtro_select, #ik_woo_repuestos_buscar .ik_woo_repuestos_buscar_codigo, #ik_woo_repuestos_buscar .ik_woo_repuestos_buscar_general {
        margin: 10px;
    }
    #ik_woo_repuestos_buscar label span, #ik_woo_repuestos_buscar .search-submit{ 
        display: block; 
    }
    #ik_woo_repuestos_buscar button.search-submit, #ik_woo_repuestos_buscar a.search-submit {
        background: #333;
        color: #fff;
        float: left;
        margin: 3px 7px;
        padding: 6.5px 17px! important;
        font-size: 16px;
        height: 38px;
    }
    #ik_woo_repuestos_listado .ik_woo_repuestos_listado_datos .ik_woo_repuestos_listado_dato.ik_woo_repuestos_listado_boton a{
        color: #fff;
    }
    .ik_woo_repuestos_nada_encontrado{
        margin: 13px;
        background: #f1f1f1;
        padding: 20px;
    }
    .ik_woo_repuestos_filtro_select select{
        width: 186px;
        margin-right: 12px! important;
    }
    .ik_woo_repuestos-close{
        z-index: 9999999;
    }
    .ik_woo_repuestos-close span{
        display: none;
        background: url('.get_site_url().'/wp-content/plugins/ik-woo-repuestos/img/close-icono.png);
        background-repeat: no-repeat;
        width: 45px;
        background-size: cover;
        cursor: pointer;
        box-shadow: none;
        height: 44px;
        z-index: 999999999;
        position: absolute;
        right: -23px;
        margin-top: -40px;
    }		.ik_woo_repuestos_listado_titulo:not(.ik_woo_repuestos_listado_img){		cursor: pointer;	}	.ik_woo_repuestos_listado_titulo:not(.ik_woo_repuestos_listado_img):hover{		text-decoration: underline;	}
    @media (min-width: 1100px){
        #ik_woo_repuestos_buscar .ik_woo_repuestos_buscar_btn {
            margin-top: 30px;
        }
    }
    @media (max-width: 1520px){
        #ik_woo_repuestos_listado {
            max-width: 66%;
            min-width: 830px;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_img, #ik_woo_repuestos_listado .ik_woo_repuestos_listado_oem {
            display: none;
        }
    }
     @media (max-width: 1250px){
        #ik_woo_repuestos_listado, #ik_woo_repuestos_listado .ik_woo_repuestos_listado_datos {
            max-width: 100%! important;
            width: 100%! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_codexplorer {
            width: 7%! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_descripcion{
            width: 27%! important;
            height: auto! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_aplicacion{
            width: 26%! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_oem{
            width: 10%! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_precio{
            width: 12%! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_img{
            width: 8%! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_boton{
            width: 10%! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_img, #ik_woo_repuestos_listado .ik_woo_repuestos_listado_oem {
            display: block! important;
        }
        #ik_woo_repuestos_detalle_producto_actual {
            margin: 0 auto;
            border: 1px solid;
            padding: 20px;
            min-width: 300px;
            position: fixed;
            top: 370px;
            transform: translate(-50%, -50%);
            left: 50%;
            background: #fff;
        }        
       #ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_automotor_contenido {
            height: 400px;
            overflow-y: auto;
            border: 0! important;
        }
        #ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_automotor_contenido img {
            height: 100px;
            width: auto! important;
        }
        #ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos-close span{
            display: block! important;
        }
    }
    @media (max-width: 920px){
        #ik_woo_repuestos_listado {
            max-width: 100%! important;
            min-width: 295px! important;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_codexplorer {
            min-width: 42px;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_descripcion {
            min-width: 85px;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_precio {
            min-width: 88px;
        }
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_img, #ik_woo_repuestos_listado .ik_woo_repuestos_listado_oem {
            display: none! important;
        }
    }
    @media (max-width: 560px){
        #ik_woo_repuestos_listado .ik_woo_repuestos_listado_aplicacion {
            display: none! important;
        }
        #ik_woo_repuestos_buscar .ik_woo_repuestos_buscar_btn {
            margin-bottom: 25px;
        }
    }
    </style>
    <div id="ik_woo_repuestos_filtro">
        <form role="search" method="get" action="'.$linkActual.'">         
            <div class="ik_woo_repuestos_filtro_select">
                <select name="marca" class="ik_woo_repuestos_filtrar_select_link" id="ik_woo_repuestos_filtrar_marcas">
                '.ik_woo_repuestos_select_taxonomy('marca_repuesto', $linkActual).'
                </select>
                <select name="product_cat" class="ik_woo_repuestos_filtrar_select_link" id="ik_woo_repuestos_filtrar_cats">
                '.ik_woo_repuestos_select_taxonomy('product_cat', $linkActual).'
                </select>
            </div>
            <div id="ik_woo_repuestos_buscar">
                <div class="ik_woo_repuestos_buscar_codigo">
                	<label>
                	    <span>Buscar por C&oacute;digo</span>
                		<input type="search" class="search-field" placeholder="Ingresar C&oacute;digo" value="'.$valor_search_codigo.'" name="codexplorer">
                	</label>
                </div>            
                <div class="ik_woo_repuestos_buscar_general">
                	<label>
                	    <span>B&uacute;squeda General</span>
                		<input type="search" class="search-field" placeholder="Ingresar b&uacute;squeda" value="'.$valor_search_keyword.'" name="keyword_repuestos">
                	</label>
                </div>            
                <div class="ik_woo_repuestos_buscar_btn">
                    <button type="submit" class="search-submit"><span class="search-form-button">Buscar</span></button>
                    <a href="'.$link_sin_parametros.'" class="search-submit"><span class="search-form-button">Limpiar B&uacute;squeda</span></a>
                </div>
            </div>
        </form>
    </div>';
    if ($productos_repuestos != NULL){
        $listado_repuestos .= '
        <div id="ik_woo_repuestos_wrapper">
            <div id="ik_woo_repuestos_listado">
                <div class="ik_woo_repuestos_listado_titulos">
                    <div class="ik_woo_repuestos_listado_titulo ik_woo_repuestos_listado_codexplorer"><span class="'.$codClass.'" orden="cod">C&oacute;d.</span></div>
                    <div class="ik_woo_repuestos_listado_titulo ik_woo_repuestos_listado_descripcion"><span class="'.$descClass.'" orden="desc">Descripci&oacute;n</span></div>
                    <div class="ik_woo_repuestos_listado_titulo ik_woo_repuestos_listado_aplicacion"><span class="'.$aplClass.'" orden="apl">Aplicaci&oacute;n</span></div>
                    <div class="ik_woo_repuestos_listado_titulo ik_woo_repuestos_listado_oem"><span class="'.$oemClass.'" orden="oem">OEM</span></div>
                    <div class="ik_woo_repuestos_listado_titulo ik_woo_repuestos_listado_titulo ik_woo_repuestos_listado_precio"><span class="'.$precioClass.'" orden="precio">Precio</span></div>
                    <div class="ik_woo_repuestos_listado_titulo ik_woo_repuestos_listado_titulo ik_woo_repuestos_listado_img"><span class="columna_base">Imagen</span></div>
                    <div class="ik_woo_repuestos_listado_boton"><span class="columna_base"></span></div>
                </div>
                <div class="ik_woo_repuestos_listado_contenido_datos">';
        foreach ($productos_repuestos as $repuesto_automotor){
        	global  $woocommerce;
        	$producto = wc_get_product( $repuesto_automotor->ID );
        	$repuesto = new WC_Repuesto_Automotor($producto);
        	
        	$tipoMoneda = get_woocommerce_currency_symbol();
        	$precio = wc_price( $producto->get_price(), array( 'currency' => $tipoMoneda ));
        
            $listado_repuestos .= '
                <div class="ik_woo_repuestos_listado_datos" repuesto_id="'.$repuesto_automotor->ID.'">
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_codexplorer">'.$repuesto->get_codigo($repuesto_automotor->ID).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_descripcion">'.$repuesto->get_descripcion_listado($repuesto_automotor->ID).'</div>
                                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_aplicacion">'.$repuesto->get_aplicacion($repuesto_automotor->ID).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_oem">'.$repuesto->get_oem($repuesto_automotor->ID).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_precio">'.$precio.'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_img">'.$repuesto->get_img($repuesto_automotor->ID).'</div>
                    <div class="ik_woo_repuestos_listado_dato ik_woo_repuestos_listado_boton"><span>'.$repuesto->get_botonpago($repuesto_automotor->ID, $linkActual).'</span></div>
                </div>';
        }
        
        //Cierro el modulo del listado, cargo scripts y agrego el modulo de producto actual
        $listado_repuestos .= '</div>
                    </div>
                    <div id="ik_woo_repuestos_detalle_producto_actual">
                        <div class="ik_woo_repuestos-close"><span></span></div>
                    </div>';
    } else {
        $listado_repuestos .= '<div id="ik_woo_repuestos_listado" class="ik_woo_repuestos_nada_encontrado">No hay repuestos automotores disponibles.</div>';   
    }
            
    $listado_repuestos .= '</div><script>
        jQuery(document).ready(function () {
            jQuery("#ik_woo_repuestos_listado").on("click", ".ik_woo_repuestos_listado_dato:not(.ik_woo_repuestos_listado_boton)", function(){
                var producto_id = jQuery(this).parent().attr("repuesto_id");
                jQuery(".ik_woo_repuestos_listado_seleccionado").removeClass("ik_woo_repuestos_listado_seleccionado");
                jQuery(this).parent().addClass("ik_woo_repuestos_listado_seleccionado");
                jQuery("#ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_automotor_contenido").remove();
                jQuery("#ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_loading_img").remove();
                jQuery("#ik_woo_repuestos_detalle_producto_actual").fadeIn(500);
                jQuery(\'<div class="ik_woo_repuestos_loading_img"><img src="'.get_site_url().'/wp-content/plugins/ik-woo-repuestos/img/loading.gif" alt="cargando repuesto seleccionado" /></div>\').appendTo("#ik_woo_repuestos_detalle_producto_actual");
                    
                var data = {
                    action: "ik_woo_repuestos_listado_ajax_dato_repuesto",
                    "post_type": "post",
                    "producto_id": producto_id,
                };  
            
                jQuery.post( "'.admin_url('admin-ajax.php').'", data, function(response) {
                    if (response != "Error"){
                        var data_one_line = JSON.stringify(response);
                        var data = JSON.parse(data_one_line);
                        jQuery("#ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_automotor_contenido").remove();
                        jQuery("#ik_woo_repuestos_detalle_producto_actual .ik_woo_repuestos_loading_img").remove();                        
                        jQuery("#ik_woo_repuestos_detalle_producto_actual").append(data);
                        jQuery(".woocommerce-Price-currencySymbol").each(function() {
                            if (jQuery(this).text() == ""){
                                jQuery(this).text("$");
                            }
                        });
                    }
                }, "json");   
            });
            
            jQuery("#ik_woo_repuestos_filtro").on("change", ".ik_woo_repuestos_filtrar_select_link", function(){
                var valselect = jQuery(this).val();
                if (valselect == "" || valselect == " " || valselect == undefined){
                    var urlredirect = jQuery(this).find(".ik_woo_repuestos_option_defecto").attr("url");
                } else {
                    var urlredirect = jQuery(this).find("option[value="+valselect+"]").attr("url");
                }
                if (urlredirect != undefined){
                    window.location.href = urlredirect;
                }
            });
            
            jQuery(".woocommerce-Price-currencySymbol").each(function() {
                if (jQuery(this).text() == ""){
                    jQuery(this).text("$");
                }
            });

            jQuery("#ik_woo_repuestos_detalle_producto_actual").on("click", ".ik_woo_repuestos-close", function(){
                jQuery("#ik_woo_repuestos_detalle_producto_actual").fadeOut(500);
            });						
			
			jQuery("#ik_woo_repuestos_listado").on("click", ".ik_woo_repuestos_listado_titulo span", function(){				
			
				var orden = jQuery(this).attr("orden");								
				var url_parcial = location.protocol + "//" + location.host + location.pathname;				
				var url_full = window.location.href;				var url = new URL(url_full);				
				var marca_index = (url.searchParams.get("marca") !== null) ? "&marca="+url.searchParams.get("marca") :"";				
				var product_cat_index = (url.searchParams.get("product_cat") !== null) ? "&product_cat="+url.searchParams.get("product_cat") :"";
				var marca_automovil_index = (url.searchParams.get("marca_automovil") !== null) ? "&marca_automovil="+url.searchParams.get("marca_automovil") :"";				
				var codexplorer_index = (url.searchParams.get("codexplorer") !== null) ? "&codexplorer="+url.searchParams.get("codexplorer") :"";
				var keyword_repuestos_index = (url.searchParams.get("keyword_repuestos") !== null) ? "&keyword_repuestos="+url.searchParams.get("keyword_repuestos") :"";							
				var urlactual = url_parcial + marca_index + product_cat_index + marca_automovil_index + codexplorer_index + keyword_repuestos_index;								
				
				if (orden != undefined){					
					if (jQuery(this).hasClass("asc")){						
						var direc = "desc";					
					} else {	
						var direc = "asc";					
					}
					
					if (orden == "cod"){						
						var orden_url = urlactual+"&orden=cod&ordendir="+direc;	
						orden_url = orden_url.replace("/&","/?");	
						window.location.href = orden_url;					
					} else if (orden == "desc"){		
						var orden_url = urlactual+"&orden=desc&ordendir="+direc;						
						orden_url = orden_url.replace("/&","/?");						
						window.location.href = orden_url;					
					} else if (orden == "apl"){	
						var orden_url = urlactual+"&orden=apl&ordendir="+direc;						
						orden_url = orden_url.replace("/&","/?");						
						window.location.href = orden_url;					
					} else if (orden == "oem"){						
						var orden_url = urlactual+"&orden=oem&ordendir="+direc;						
						orden_url = orden_url.replace("/&","/?");						
						window.location.href = orden_url;					
					} else if (orden == "precio"){						
						var orden_url = urlactual+"&orden=precio&ordendir="+direc;						
						orden_url = orden_url.replace("/&","/?");						
						window.location.href = orden_url;					
					}
				}
			});
        });
        </script>'; 
        
    return $listado_repuestos;
}
add_shortcode('listado_repuestos', 'ik_woo_repuestos_listado_productos');