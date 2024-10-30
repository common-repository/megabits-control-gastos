<?php
// Sub-página en el CPT Gastos para mostrar las facturas
add_action( 'admin_menu', 'mgb_custom_page_gastos_calendar');
function mgb_custom_page_gastos_calendar() {
add_submenu_page( '/edit.php?post_type=gastos', __( 'Calendario', 'mgb-control-gastos' ), __( 'Calendario', 'mgb-control-gastos' ), 'manage_options', 'gastos-calendar', 'mgb_gastos_calendar_callback');
}

function mgb_gastos_calendar_callback() {
	?>
	<div class="info_div1">
		<img src="<?php echo esc_url( plugins_url( 'assets/banner-772x250.png', dirname(__FILE__) ) );?>" class="div1_img">
		<h2>CONTROL GASTOS - CALENDARIO DE VENCIMIENTOS</h2>
	</div>
	<div id="calendar" style="width: 80%; margin: 0 auto;"></div>
	<?php
}

// Pone un link al Calendario en admin-bar
add_action( 'wp_before_admin_bar_render', 'mcg_admin_bar_calendar', 100 );
function mcg_admin_bar_calendar() {
    global $wp_admin_bar;
    $wp_admin_bar->add_menu( array(
        'id' => 'mcg-calendar-menu',
        'parent' => false,
        'title' => '<img src="'.plugins_url( "assets/icono-megabits-color.jpg", dirname(__FILE__) ).'" style="width:18px;vertical-align:sub;"> Calendario',
        'href' => admin_url('edit.php?post_type=gastos&page=gastos-calendar'),
    ) );
}

// Crea los eventos para los gastos
function mcg_add_factura_event($post_id) {
    $gasto_title = get_the_title($post_id);
    $proveedor = get_field('gasto_proveedor', $post_id)->name;
    $categoria = get_field('gasto_categoria', $post_id)->name;
    $fecha_fra = get_field('gasto_fecha_factura', $post_id);
    $fecha_vto = get_field('gasto_fecha_vto', $post_id);
    $num_fra = get_field('gasto_num_fra', $post_id);
    $imp_neto = get_field('gasto_importe', $post_id);
    $imp_impuestos = get_field('gasto_iva', $post_id);
    $imp_bruto = ($imp_neto + $imp_impuestos).' €';
    $file = get_field('gasto_file', $post_id);
    $url = $file['url'];
    $image_id = $file['id'];
    $image_url = wp_get_attachment_image($image_id,'medium');
    if ( $file )  $pdf_imagen='<a href="'.esc_url($url).'?TB_iframe=true&width=700&height=600" title="'.esc_attr($gasto_title).'" class="thickbox listing_pdf_imagen_a">'.$image_url.'</a>';
    global $wpdb;
    $tabla_events = $wpdb->prefix . 'mgb_events';
    $sql = "SELECT id FROM $tabla_events WHERE gasto_id = $post_id";
    $result = $wpdb->get_var($sql);
    if ($result) {
        $wpdb->delete($tabla_events, array('id' => $result ));
    }
    $eventTitle = $imp_bruto.' '.$proveedor;
    $eventDesc = $categoria;
    $start = DateTime::createFromFormat('d/m/Y',$fecha_vto)->format('Y-m-d');
    $eventURL = '<div class="div_factura_event">
                <div class="inferior">
                    <span>'.$proveedor.'</span><br>
                    <span>'.$categoria.'</span><br>
                    <span>'.$num_fra.'</span><br>
                    <span>'.$fecha_fra.'</span><br>
                    <span>'.$imp_bruto.'</span>
                </div>
                <div>'.$pdf_imagen.'</div>
            </div>';
    $wpdb->insert($tabla_events, array(
            'gasto_id' => $post_id,
            'title' => $eventTitle,
            'description' => $eventDesc,
            'URL' => $eventURL,
            'byuser' => 'NO',
            'start' => $start,
            'end' => $start,
        ));
}