<?php
/*

General Functions
Update: 16/11/2021
Author: Gabriel Caroprese

*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Agrego vars para ser aceptadas por Wordpress al buscar y filtrar
function add_query_verif_vars_filter( $vars ){
    $vars[] = "marca";
    $vars[] = "product_cat";
    $vars[] = "marca_automovil";
    $vars[] = "orden";
    $vars[] = "ordendir";
    $vars[] = "verif";
    return $vars;
}
add_filter( 'query_vars', 'add_query_verif_vars_filter' );

//Agrego Repuesto Automotor para seleccionar al editar productos
add_filter( 'product_type_selector', 'ik_woo_repuestos_selector_product_type' );
function ik_woo_repuestos_selector_product_type( $types ){
    $types[ 'repuesto_automotor' ] = __( 'Repuesto Automotor', 'repuesto_automotor' );

    return $types;	
}

//Creo el tab para agregar datos de repuestos automotores
add_filter( 'woocommerce_product_data_tabs', 'ik_woo_repuestos_tab_repuesto_automotor' );
function ik_woo_repuestos_tab_repuesto_automotor( $tabs) {
		
    $tabs['repuesto_automotor'] = array(
      'label'	 => __( 'Datos Repuesto', 'datos_repuesto_automotor' ),
      'target' => 'repuesto_automotor_product_options',
      'class'  => 'show_if_repuesto_automotor',
     );
    return $tabs;
}

//Agrego el contenido del tab de datos de repuestos automotores
add_action( 'woocommerce_product_data_panels', 'ik_woo_repuestos_tab_contenido_repuesto_automotor' );
function ik_woo_repuestos_tab_contenido_repuesto_automotor() {
?>
    <div id="repuesto_automotor_product_options" class="panel woocommerce_options_panel">
        <div class='ik_datos_repuesto_automotor'>
        <?php
				
        woocommerce_wp_text_input(
        	array(
        	  'id' => 'dato_codexplorer_repuesto',
        	  'label' => __( 'Code Explorer', 'datos_repuesto_automotor' ),
        	  'placeholder' => '',
        	  'desc_tip' => 'true',
        	  'description' => __( 'Ingresar el code explorer', 'datos_repuesto_automotor' ),
        	  'type' => 'text'
        	)
        );
        ?>
        </div>
        <div class='ik_datos_repuesto_automotor'>
        <?php
				
        woocommerce_wp_text_input(
        	array(
        	  'id' => 'dato_aplicacion_repuesto',
        	  'label' => __( 'Aplicaci&oacute;n', 'datos_repuesto_automotor' ),
        	  'placeholder' => '',
        	  'desc_tip' => 'true',
        	  'description' => __( 'Ingresar la aplicaci&oacute;n', 'datos_repuesto_automotor' ),
        	  'type' => 'text'
        	)
        );
        ?>
        </div>        
        <div class='ik_datos_repuesto_oem'>
        <?php
				
        woocommerce_wp_text_input(
        	array(
        	  'id' => 'datos_oem_automotor',
        	  'label' => __( 'OEM', 'datos_oem_automotor' ),
        	  'placeholder' => '',
        	  'desc_tip' => 'true',
        	  'description' => __( 'Ingresar OEM', 'datos_repuesto_automotor' ),
        	  'type' => 'text'
        	)
        );
        ?>
        </div>
    </div>
    <?php
}

//Agrego el tab de inventario al editar el repuesto automotor
function ik_woo_repuestos_editar_repuesto_automotor_js() {

    if ('product' != get_post_type()) :
        return;
    endif;
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function () {
            function ik_woo_repuestos_js_chequear_edit_tabs(){
                if (jQuery('#product-type').val() == 'repuesto_automotor'){
                    jQuery('.product_data_tabs.wc-tabs .inventory_options').show();
                    jQuery('.product_data_tabs.wc-tabs .general_options').show();
                    jQuery('#general_product_data .pricing.show_if_simple').show();
                    jQuery('#general_product_data .pricing.show_if_simple').removeClass('hidden');
                }
            }
            ik_woo_repuestos_js_chequear_edit_tabs();
            jQuery('#product-type').on('change', function(){
                ik_woo_repuestos_js_chequear_edit_tabs();
            });
        });
    </script>
    <?php
}

add_action('admin_footer', 'ik_woo_repuestos_editar_repuesto_automotor_js');

//Escondo columnas de marca de repuesto y marca de automotor
function ik_woo_repuestos_editar_repuesto_automotor_css() {

    if ('product' != get_post_type()) :
        return;
    endif;
    ?>
    <style>
	th#taxonomy-marca_automovil, th#taxonomy-marca_repuesto, th.column-taxonomy-marca_automovil, th.column-taxonomy-marca_repuesto, td.column-taxonomy-marca_automovil, td.column-taxonomy-marca_repuesto{
		display: none! important;
	}
    </style>
    <?php
}
add_action('admin_head', 'ik_woo_repuestos_editar_repuesto_automotor_css');


// Guardo los campos de los repuestos de automotor
add_action( 'save_post', 'ik_woo_repuestos_editar_guardar_datos', 1, 2 );
function ik_woo_repuestos_editar_guardar_datos( $post_id, $post ) {
	// Me aseguro que el usuario tenga permisos para guardar
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}
	
	$producto = wc_get_product( $post_id );
	if ($producto != NULL){
	//Guardo solo si el producto es un repuesto automotor
        if ($producto->is_type( 'repuesto_automotor' )):

            $datos_repuestos = array(
                                    'dato_codexplorer_repuesto',
                                    'dato_aplicacion_repuesto',
									'excerpt'//Guardo el excerpt como postmeta para filtrado
                                    );
        
			//Me aseguro que no es una revision para evitar duplicados
			if ( 'revision' === $post->post_type ){
				return;
            }
			
            foreach ( $datos_repuestos as $dato_a_subir ) :
                
                //Si el producto existe o tiene datos cargados
                if (isset($_POST[$dato_a_subir])){
                    if ($_POST[$dato_a_subir] != ''){
                        $valor_dato_repuesto = sanitize_text_field($_POST[$dato_a_subir]);
                        update_post_meta($post_id, $dato_a_subir, $valor_dato_repuesto);
                    }
                } else {
					delete_post_meta($post_id, $dato_a_subir);					
				}
            endforeach;
            
        endif;
    }
}

//Funcion para generar los argumentos de busqueda para listar repuestos
function ik_woo_repuestos_get_listado_repuestos($filtros, $ord = 'cod', $dir='DESC'){

	switch ($ord) {
		case 'cod':
			$ord = 'dato_codexplorer_repuesto';
			break;
		case 'desc':
			$ord = 'excerpt';
			break;
		case 'apl':
			$ord = 'dato_aplicacion_repuesto';
			break;
		case 'oem':
			$ord = 'datos_oem_automotor';
			break;
		case 'precio':
			$ord = '_price';
			break;
	}

	if ($dir != 'DESC'){
		$dir = 'ASC';
	}

    //Inicializo el query por taxonomia
    $tax_query = array(
            array(
                'taxonomy' => 'product_type',
                'field' => 'slug',
                'terms' => 'repuesto_automotor',
            ),
        );
        
    //Creo los argumentos de busqueda

    if (isset($filtros['taxonomy'])){
        foreach ($filtros['taxonomy'] as $taxonomia => $valor_taxonomia){
            if ($valor_taxonomia != '' && $valor_taxonomia != ' '){
                $tax_query['relation'] = 'AND';
                $tax_query[] = array(
    							    'taxonomy' => $taxonomia,
    							    'field' => 'slug',
    							    'terms' => $valor_taxonomia
    							);
            }
        }

    }
    
	$codeexplorerQuery = '';
    if (isset($filtros['meta'])){
		$codexplorer = sanitize_text_field($filtros['meta']);
		$codexplorer = str_replace(" ", "", $codexplorer);
		if ($codexplorer != ''){
			global $wpdb;
			$codeexplorerQuery = " AND `".$wpdb->prefix."postmeta`.`meta_key` LIKE 'dato_codexplorer_repuesto' AND `".$wpdb->prefix."postmeta`.`meta_value` LIKE '%".$codexplorer."%'";        
		}
    }
      
    if (isset($filtros['keyword'])){
        
        $keyword = sanitize_text_field($filtros['keyword']);
		
	} else {
		$keyword = '';
	}
        
	//Si la keyword tiene contenido
	if ($keyword != '' && $keyword != ' '){

		global $wpdb;
		$query_filtro = "SELECT `".$wpdb->prefix."posts`.`ID` FROM `".$wpdb->prefix."posts`,`".$wpdb->prefix."postmeta` 
		WHERE `".$wpdb->prefix."postmeta`.`post_id` = `".$wpdb->prefix."posts`.`ID` 
		AND ((`".$wpdb->prefix."postmeta`.`meta_key` LIKE 'dato_aplicacion_repuesto' 
		AND `".$wpdb->prefix."postmeta`.`meta_value` LIKE '%".$keyword."%')
		OR `".$wpdb->prefix."posts`.`post_title` LIKE '%".$keyword."%' 
		OR `".$wpdb->prefix."posts`.`post_content` LIKE '%".$keyword."%' 
		OR `".$wpdb->prefix."posts`.`post_excerpt`  LIKE '%".$keyword."%')
		".$codeexplorerQuery;
		$filtro_busqueda = $wpdb->get_results($query_filtro);    
    } else {
		
		global $wpdb;
		$query_filtro = "SELECT `".$wpdb->prefix."posts`.`ID` FROM `".$wpdb->prefix."posts`,`".$wpdb->prefix."postmeta` 
		WHERE (`".$wpdb->prefix."postmeta`.`post_id` = `".$wpdb->prefix."posts`.`ID` 
		AND `".$wpdb->prefix."posts`.`post_type` = 'product' 
		AND `".$wpdb->prefix."postmeta`.`meta_key` LIKE 'dato_codexplorer_repuesto') 
		".$codeexplorerQuery;
		$filtro_busqueda = $wpdb->get_results($query_filtro); 		

	}
	
	
	//Convierto un array de los IDs de filtro    
	if (isset($filtro_busqueda[0]->ID)){    
	   
		foreach ($filtro_busqueda as $repuesto_encontrado){
		   
		   $ids_keyword[] = $repuesto_encontrado->ID;
		   
		}
	}
    
    //Creo los argumentos del query de listado de repuestos
	$args = array( 
		'tax_query' => $tax_query,
		'limit' => -1,  
		'meta_key' => $ord,
		'orderby' => 'meta_value',		
		'order' => $dir,  
		'numberposts' => -1,
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => 300
	); 
    
    if (isset($ids_keyword)){
        $args['post__in'] = array_unique($ids_keyword);
    } else {
		return NULL;
	}
	
    $data_lista_repuestos = get_posts( $args );
	
	return $data_lista_repuestos;
}

//Funcion para devolver el link actual
function ik_woo_repuestos_link_actual($parameter = false, $filtro = false){
    
    $link_actual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    if ( isset($_GET['add-to-cart'])) {
        $parametro_add_to_cart = 'add-to-cart='.intval($_GET['add-to-cart']);
        $link_actual = str_replace('&'.$parametro_add_to_cart.'&', "", $link_actual);
        $link_actual = str_replace($parametro_add_to_cart.'&', "?", $link_actual);
        $link_actual = str_replace('&'.$parametro_add_to_cart, "", $link_actual);
        $link_actual = str_replace('?'.$parametro_add_to_cart, "", $link_actual);
    }
    
    //Limpio el parametro
    if ($filtro != false){
        if ( isset($_GET[$filtro]) ){
            $filtro = sanitize_text_field($filtro);
            $parametro_a_limpiar = $filtro.'='.sanitize_text_field($_GET[$filtro]);
            $link_actual = str_replace('&'.$parametro_a_limpiar.'&', "", $link_actual);
            $link_actual = str_replace($parametro_a_limpiar.'&', "?", $link_actual);
            $link_actual = str_replace('&'.$parametro_a_limpiar, "", $link_actual);
            $link_actual = str_replace('?'.$parametro_a_limpiar, "", $link_actual);     
        }
    }
    
    //Si existen otros parametros
    $gets = parse_url($link_actual);
    if(isset($gets['query'])){
        $link_actual .= '&';
    } else {
        if ($parameter == true){
            $link_actual = $link_actual.'?';
        }
    }

    return $link_actual;
}


//Funcion para devolver el listado de marcas de automovil para filtrar productos
function ik_woo_repuestos_listado_seleccion_marcas(){
    $marcas = get_terms([
        'taxonomy' => 'marca_automovil',
        'hide_empty' => false,
    ]);
    
    $link = ik_woo_repuestos_link_actual(true, 'marca_automovil');
    
    if (isset($_GET['marca_automovil'])){
        $seleccionado = sanitize_text_field($_GET['marca_automovil']);
    } else {
        $seleccionado = '-';
    }
    
    $selector_marcas = '<ul>';
    foreach ($marcas as $marca){
        if ($seleccionado == $marca->slug){
            $selected = 'class="para_seleccionado"';
        } else {
            $selected = '';
        }
        $selector_marcas .= '<li '.$selected.'><a href="'.$link.'marca_automovil='.$marca->slug.'">'.$marca->name.'</a></li>';
    }
    $selector_marcas .= '</ul>';
    return $selector_marcas;
}

//Funcion para devolver opciones de select por taxonomy con link para filtrar
function ik_woo_repuestos_select_taxonomy($taxonomy = 'product_cat'){
    $taxonomy = sanitize_text_field($taxonomy);
    
    $taxonomy_options = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);
    
    if ($taxonomy == 'product_cat'){
        $texto_opcion_defecto = 'Todas Las Categor&iacute;as';
        $filtro = 'product_cat';
    } else if ($taxonomy == 'marca_repuesto'){
        $texto_opcion_defecto = 'Todas Las Marcas';
        $filtro = 'marca';
    } else if ($taxonomy == 'marca_automovil'){
        $texto_opcion_defecto = 'Todas Las Marcas de Veh&iacute;culo';
        $filtro = 'marca_automovil';
    } else {
        $texto_opcion_defecto = 'Todos';
        $filtro = 'filtro';
    }
    
    $link = ik_woo_repuestos_link_actual(true, $filtro);
    
    $link_defecto = substr($link, 0, -1);
    
    if (isset($_GET[$filtro])){
        $seleccionado = sanitize_text_field($_GET[$filtro]);
    } else {
        $seleccionado = '-';
    }
    
    $options = '<option class="ik_woo_repuestos_option_defecto" value="" url="'.$link_defecto.'">'.$texto_opcion_defecto.'</option>';
    foreach ($taxonomy_options as $option){
        if ($seleccionado == $option->slug){
            $selected = 'selected';
        } else {
            $selected = '';
        }
        $link_value = $link.$filtro.'='.$option->slug;
        $options .= '<option '.$selected.' value="'.$option->slug.'" url="'.$link_value.'">'.$option->name.'</option>';
    }
    
    return $options;
    
}
?>