<?php
/*
 * Plugin Name: Megabits Control Gastos
 * Plugin URI: https://megabits.es
 * Description: Control de facturas de gastos.
 * Version: 2.3
 * Author: Megabits
 * Author URI: mailto:info@megabits.es
 * Copyright: © 2024 Megabits
 * WP tested up to: 6.4.3
 * Text Domain: mgb-control-gastos
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
register_activation_hook( __FILE__, 'megabits_control_gastos_activate' );
function megabits_control_gastos_activate(){
    global $wpdb;
    // Crea la tabla para los eventos del calendario si no existe
    $tabla_events = $wpdb->prefix . 'mgb_events';
    $charset_collate = $wpdb->get_charset_collate();
    $query = "CREATE TABLE IF NOT EXISTS $tabla_events (
        id mediumint(11) NOT NULL AUTO_INCREMENT,
        gasto_id int(11) NOT NULL,
        title varchar(50) NOT NULL,
        description varchar(255) NOT NULL,
        URL longtext NOT NULL,
        byuser varchar(10) NOT NULL,
        start date NOT NULL,
        end date NOT NULL,
        UNIQUE (id)
        ) $charset_collate;";
    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($query);

    $sql = "SELECT * FROM $tabla_events";
    $result = $wpdb->get_results($sql);
    if (!$result) {
        add_option( 'mcg_need_update_events', 'SI');
    }
}

// Comprueba si hay gastos que incluir en eventos
add_action( 'admin_init', 'mcg_check_events_update' );
function mcg_check_events_update() {
    $update_option = get_option( 'mcg_need_update_events' );
    if (($update_option) && ($update_option == 'SI')) {
        global $post;
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'gastos',
            'post_status'      => 'publish',
        );
        $gastos = get_posts( $args );
        if ($gastos) {
            foreach ( $gastos as $gasto ) {
                $fecha_vto = get_field('gasto_fecha_vto', $gasto->ID);
                if ($fecha_vto) mcg_add_factura_event($gasto->ID);
            }
            delete_option('mcg_need_update_events');
        }
    }
}

add_action( 'admin_enqueue_scripts', 'mcg_js_scripts' );
function mcg_js_scripts() {
    ?>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
    <?php
    add_thickbox();
    wp_enqueue_script( 'mcg_calendar_js', plugins_url( '/js/calendar.js', __FILE__ ), '', '', true );
    wp_enqueue_script( 'mcg_facturas_js_listing', plugins_url( '/js/facturas_listing.js', __FILE__ ), array('jquery'), '', true );
	wp_enqueue_style( 'mcg_factura_css', plugins_url( '/css/factura.css', __FILE__ ) );
    wp_enqueue_style( 'mcg_calendar_css', plugins_url( '/css/calendar.css', __FILE__ ) );
	global $post_type;
	if ( $post_type == 'gastos' ) {
	    wp_enqueue_script( 'mcg_gastos_js_admin', plugins_url( '/js/gastos_admin.js', __FILE__ ), array('jquery'), '', true );
	    wp_enqueue_style( 'mcg_gastos_css_admin', plugins_url( '/css/gastos_admin.css', __FILE__ ) );
	}
}

include "includes/mcg-calendar.php";
include "includes/mcg-acf.php";
include "includes/mcg-create.php";
include "includes/mcg-listing.php";
include "includes/mcg-pagination.php";
include "includes/mcg-info.php";