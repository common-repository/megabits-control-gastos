<?php
// Sub-página en el CPT Gastos para mostrar las facturas
add_action( 'admin_menu', 'mgb_custom_page_gastos_facturas');
function mgb_custom_page_gastos_facturas() {
add_submenu_page( '/edit.php?post_type=gastos', __( 'Facturas', 'mgb-control-gastos' ), __( 'Facturas', 'mgb-control-gastos' ), 'manage_options', 'gastos-facturas', 'mgb_gastos_facturas_callback');
}
function mgb_gastos_facturas_callback() {
	global $post;
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'meta_value',
		'meta_key'         => 'gasto_fecha_factura',
		'order'            => 'DESC',
		'post_type'        => 'gastos',
		'post_status'      => 'publish',
	);
	// Comprueba de que años hay gastos para el listado de años disponibles
	$gastos = get_posts( $args );
	$year_array = array();
	foreach ( $gastos as $gasto ) {
		$fecha_fra = get_field('gasto_fecha_factura', $gasto->ID);
		$fecha_fra = substr($fecha_fra, -4);
		if (!in_array($fecha_fra, $year_array)) $year_array[] = $fecha_fra;
	}
	sort($year_array, SORT_NUMERIC);

	if ( (isset( $_POST['submit'])) || (isset( $_POST['descarga'])) || (isset( $_POST['post_page'])) || (isset( $_POST['aplicar_per_page'])) ) {
		$select_provee = sanitize_text_field($_POST['select_provee']);
		$select_catg   = sanitize_text_field($_POST['select_catg']);
		$select_mes    = (int) sanitize_text_field($_POST['select_mes']);
		$select_trim   = (int) sanitize_text_field($_POST['select_trim']);
		$select_year   = (int) sanitize_text_field($_POST['select_year']);


		if ( ($select_year != 0) && ($select_mes == 0) && ($select_trim == 0) ) {
			$year_inicio = $select_year*10000 + 101;
			$year_fin = $select_year*10000 + 1231;
			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => 'gastos',
				'post_status'      => 'publish',
				'orderby'          => 'meta_value',
				'meta_key'         => 'gasto_fecha_factura',
				'order'            => 'DESC',
				'meta_query' => array(
					'relation' => 'AND',
				    array(
				    	'key' => 'gasto_fecha_factura',
				    	'value' => $year_inicio,
				     	'compare' => '>=',
				    ),
				    array(
				    	'key' => 'gasto_fecha_factura',
				    	'value' => $year_fin,
				     	'compare' => '<=',
				    )
				),
			);
		}
		if ( ($select_mes != 0) && ($select_year != 0) ) {
			$mes_year = $select_year*10000 + $select_mes*100;
			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => 'gastos',
				'post_status'      => 'publish',
				'orderby'          => 'meta_value',
				'meta_key'         => 'gasto_fecha_factura',
				'order'            => 'DESC',
				'meta_query' => array(
					'relation' => 'AND',
				    array(
				    	'key' => 'gasto_fecha_factura',
				    	'value' => $mes_year + 1,
				     	'compare' => '>=',
				    ),
				    array(
				    	'key' => 'gasto_fecha_factura',
				    	'value' => $mes_year + 31,
				     	'compare' => '<=',
				    )
				),
			);
		}
		if ( ($select_trim != 0) && ($select_year != 0) ) {
			if ($select_trim == 1) {
				$mes_trim_0 = 1;
				$mes_trim_1 = 3;
			} elseif ($select_trim == 2) {
				$mes_trim_0 = 4;
				$mes_trim_1 = 6;
			} elseif ($select_trim == 3) {
				$mes_trim_0 = 7;
				$mes_trim_1 = 9;
			} elseif ($select_trim == 4) {
				$mes_trim_0 = 10;
				$mes_trim_1 = 12;
			}
			$trim_year_inicial = $select_year*10000 + $mes_trim_0*100 + 1;
			$trim_year_final = $select_year*10000 + $mes_trim_1*100 + 31;
			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => 'gastos',
				'post_status'      => 'publish',
				'orderby'          => 'meta_value',
				'meta_key'         => 'gasto_fecha_factura',
				'order'            => 'DESC',
				'meta_query' => array(
					'relation' => 'AND',
				    array(
				    	'key' => 'gasto_fecha_factura',
				    	'value' => $trim_year_inicial,
				     	'compare' => '>=',
				    ),
				    array(
				    	'key' => 'gasto_fecha_factura',
				    	'value' => $trim_year_final,
				     	'compare' => '<=',
				    )
				),
			);
		}
		if ($select_provee != '0') {
			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => 'gastos',
				'post_status'      => 'publish',
				'orderby'          => 'meta_value',
				'meta_key'         => 'gasto_fecha_factura',
				'order'            => 'DESC',
				'tax_query' => array(
				    array(
				    	'taxonomy' => 'proveedor_gasto',
				    	'field' => 'name',
				     	'terms' => $select_provee,
				    )
				),
			);
		}
		if ($select_catg != '0') {
			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => 'gastos',
				'post_status'      => 'publish',
				'orderby'          => 'meta_value',
				'meta_key'         => 'gasto_fecha_factura',
				'order'            => 'DESC',
				'tax_query' => array(
				    array(
				    	'taxonomy' => 'categoria_gasto',
				    	'field' => 'name',
				     	'terms' => $select_catg,
				    )
				),
			);
		}	
	}
	$gastos = get_posts( $args );
	$proveedor_gasto_terms = get_terms([
    'taxonomy' => 'proveedor_gasto',
    'hide_empty' => false,
	]);
	$categoria_gasto_terms = get_terms([
    'taxonomy' => 'categoria_gasto',
    'hide_empty' => false,
	]);

	$mes_array = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	$trim_array = array('Primer trimestre', 'Segundo trimestre', 'Tercer trimestre', 'Cuarto trimestre');
	?>
	<div class="listing_div1">
		<img src="<?php echo esc_url( plugins_url( 'assets/banner-772x250.png', dirname(__FILE__) ) );?>" class="div1_img">
		<h2>CONTROL GASTOS - FACTURAS</h2>
	</div>
	<form id="gastos_listing" action="" method="post" class="listing_form">
		<select name="select_provee" id="select_provee" >
			<option value="0">Selecciona el Proveedor</option>
			<?php
			foreach ( $proveedor_gasto_terms as $proveedor_gasto ) {
				$proveedor_name = $proveedor_gasto->name;
				if ($proveedor_name == $select_provee) {
					?>
					<option value="<?php echo esc_attr($proveedor_name);?>" selected><?php echo esc_html($proveedor_name);?></option>
					<?php
				} else {
					?>
					<option value="<?php echo esc_attr($proveedor_name);?>" ><?php echo esc_html($proveedor_name);?> </option>
					<?php
				}
			}
			?>
		</select>
		<select name="select_catg" id="select_catg" >
			<option value="0">Selecciona la Categoría</option>
			<?php
			foreach ( $categoria_gasto_terms as $categoria_gasto ) {
				$categoria_name = $categoria_gasto->name;
				if ($categoria_name == $select_catg) {
					?>
					<option value="<?php echo esc_attr($categoria_name);?>" selected><?php echo esc_html($categoria_name);?> </option>
					<?php
				} else {
					?>
					<option value="<?php echo esc_attr($categoria_name);?>" ><?php echo esc_html($categoria_name);?> </option>
					<?php
				}
			}
			?>
		</select>
		<select name="select_mes" id="select_mes" >
			<option value="0">Selecciona el mes</option>
			<?php
			foreach ($mes_array as $key => $mes_value) {
				$mes_key = $key + 1;
				if ($select_mes == $mes_key) {
					?>
					<option value="<?php echo esc_attr($mes_key);?>" selected><?php echo esc_html($mes_value);?> </option>
					<?php
				} else {
					?>
					<option value="<?php echo esc_attr($mes_key);?>"><?php echo esc_html($mes_value);?></option>
					<?php
				}
			}
			?>
		</select>
		<select name="select_trim" id="select_trim" >
			<option value="0">Selecciona el trimestre</option>
			<?php
			foreach ($trim_array as $key => $trim_value) {
				$key = $key + 1;
				if ($select_trim == $key) {
					?>
					<option value="<?php echo esc_attr($key);?>" selected><?php echo esc_html($trim_value);?> </option>
					<?php
				} else {
					?>
					<option value="<?php echo esc_attr($key);?>"><?php echo esc_html($trim_value);?></option>
					<?php
				}
			}
			?>
		</select>
		<select name="select_year" id="select_year" >
			<option value="0">Selecciona el año</option>
			<?php
			foreach ($year_array as $year_value) {
				if ($select_year == $year_value) {
					?>
					<option value="<?php echo esc_attr($year_value);?>" selected><?php echo esc_html($year_value);?> </option>
					<?php
				} else {
					?>
					<option value="<?php echo esc_attr($year_value);?>"><?php echo esc_html($year_value);?></option>
					<?php
				}
			}
			?>
		</select>
		<input name="submit" type="submit" id="filtrar" value="Aplicar Filtros" class="boton_listado" />
    	<input name="descarga" id="descarga" type="submit" value="Descargar" class="boton_listado listing_input" />
		<?php
		// Paginación
		$gastos_max_display = 20;
		$p = isset($_POST['post_page']) ? sanitize_text_field($_POST['post_page']) : 1;
		mgb_gastos_pagination($gastos, $p, $gastos_max_display);
		?>
	</form>
	<?php
	// Crea un array con los ficheros para la descarga
	$files_url = array();
	foreach ( $gastos as $gasto_key => $gasto ) {
		$file = get_field('gasto_file', $gasto->ID);
		$url = $file['url'];
		// Quitamos la url del sitio para que quede el path interno
		$files_url[$gasto->ID] = str_replace(site_url(), '..', $url);
	}
	?>
	<div class="listing_div2">
		<?php
		foreach ( $gastos as $gasto_key => $gasto ) {
			if (($gasto_key < $p*$gastos_max_display) && ($gasto_key >= $p*$gastos_max_display-$gastos_max_display)) {
			$gasto_title = get_the_title($gasto->ID);
			$proveedor = get_field('gasto_proveedor', $gasto->ID)->name;
			$categoria = get_field('gasto_categoria', $gasto->ID)->name;
			$fecha_fra = get_field('gasto_fecha_factura', $gasto->ID);
			$num_fra = get_field('gasto_num_fra', $gasto->ID);
			$imp_neto = get_field('gasto_importe', $gasto->ID);
			$imp_impuestos = get_field('gasto_iva', $gasto->ID);
			$imp_bruto = ($imp_neto + $imp_impuestos).' €';
			$file = get_field('gasto_file', $gasto->ID);
			$url = $file['url'];
			$image_id = $file['id'];
			$image_url = wp_get_attachment_image($image_id,'medium');
			if ( $file )  $pdf_imagen='<a href="'.esc_url($url).'?TB_iframe=true&width=700&height=600" title="'.esc_attr($gasto_title).'" class="thickbox listing_pdf_imagen_a">'.$image_url.'</a>';
			?>
			<div class="div_factura">
				<div class="inferior">
					<span><?php echo esc_html($proveedor); ?></span><br>
					<span><?php echo esc_html($categoria); ?></span><br>
					<span><?php echo esc_html($num_fra); ?></span><br>
					<span><?php echo esc_html($fecha_fra); ?></span><br>
					<span><?php echo esc_html($imp_bruto); ?></span>
				</div>
				<div>
					<?php echo wp_kses_post($pdf_imagen); ?>
				</div>
			</div>
			<?php
			}
		}
		?>
	</div>
	<?php
	// Paginación
	mgb_gastos_pagination($gastos, $p, $gastos_max_display, 1);

	// Descarga del zip
	if (isset( $_POST['descarga'])) {
		$zip_file_name='facturas-gastos-todos.zip';
		if ( ($select_year != 0) && ($select_mes == 0) && ($select_trim == 0) ) {
			$zip_file_name='facturas-gastos-'.$select_year.'.zip';
		}
		if ( ($select_mes != 0) && ($select_year != 0) ) {
			$zip_file_name='facturas-gastos-mes-'.$select_mes.'-'.$select_year.'.zip';
		}
		if ( ($select_trim != 0) && ($select_year != 0) ) {
			$zip_file_name='facturas-gastos-trim-'.$select_trim.'-'.$select_year.'.zip';
		}
		if ($select_provee != '0') {
			$zip_file_name='facturas-gastos-'.$select_provee.'.zip';
		}
		if ($select_catg != '0') {
			$zip_file_name='facturas-gastos-'.$select_catg.'.zip';
		}
		$zip = new ZipArchive;
		$tmp_file = tempnam('.','');
		$zip->open($tmp_file, ZipArchive::CREATE);
	    // Add files to the zip file
	    foreach ($files_url as $key => $value) {
	    	$file_dir = 'Proveedores/';
	    	$gasto_impuestos = get_field('gasto_iva', $key);
	    	if ($gasto_impuestos == 0) $file_dir = 'Inversion Sujeto Pasivo/';
	    	$zip->addFile($value, $file_dir.basename($value));
	    }
	    $zip->close();
	    header('Content-type: application/zip');
	    header('Content-Disposition: attachment; filename="'.$zip_file_name.'"');
	    header("Pragma: no-cache"); 
		header("Expires: 0");
	    header("Content-length: ".filesize($tmp_file));
	    ob_clean();
        flush();
	    readfile($tmp_file);
	    unlink($tmp_file);
	}
}