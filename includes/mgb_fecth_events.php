<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
global $wpdb;
$tabla_events = $wpdb->prefix . 'mgb_events';
$sql = "SELECT * FROM $tabla_events";
$events = $wpdb->get_results($sql, ARRAY_A);
$event_array = array();
$event_array_start = array();
foreach ($events as $event) {
	if ($event['byuser'] == 'NO') {
		$event_array[$event['id']] = $event['gasto_id'];
		$event_array_start[$event['gasto_id']] = $event['start'];
	}
}
global $post;
$args = array(
    'posts_per_page'   => -1,
    'post_type'        => 'gastos',
    'post_status'      => 'publish',
);
$gastos = get_posts( $args );
if ($gastos) {
	$gastos_array = array();
    foreach ( $gastos as $gasto ) {
        $fecha_vto = get_field('gasto_fecha_vto', $gasto->ID);
        if ($fecha_vto) {
        	$gastos_array[] = $gasto->ID;
        	if (!in_array($gasto->ID, $event_array)) {
        		mcg_add_factura_event($gasto->ID);
        	} else {
        		$start_gasto = DateTime::createFromFormat('d/m/Y',$fecha_vto)->format('Y-m-d');
        		if ($event_array_start[$gasto->ID] != $start_gasto) {
        			$wpdb->update($tabla_events, array(
			            'start' => $start_gasto,
			            'end' => $start_gasto,
			            ),
			            array('id'=>array_search($gasto->ID, $event_array)));
        		}
        	}
        }
    }
}
foreach ($event_array as $key => $value) {
	if (!in_array($value, $gastos_array)) $wpdb->delete($tabla_events, array('id' => $key ));
}
$sql = "SELECT * FROM $tabla_events";
$result = $wpdb->get_results($sql);
echo json_encode($result);