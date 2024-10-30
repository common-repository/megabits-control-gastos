<?php
// Define path and URL to the ACF plugin.
define( 'MGB_ACF_PATH', plugin_dir_path( __DIR__ ) . 'includes/acf/' );
define( 'MGB_ACF_URL', plugin_dir_url( __DIR__ ) . 'includes/acf/' );

// Include the ACF plugin.
include_once( MGB_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'mgb_acf_settings_url');
function mgb_acf_settings_url( $url ) {
    return MGB_ACF_URL;
}

// Hide the ACF admin menu item.
add_filter('acf/settings/show_admin', 'mgb_acf_settings_show_admin');
function mgb_acf_settings_show_admin( $show_admin ) {
    return false;
}

// Modifica donde se guarda el json
add_filter('acf/settings/save_json', 'mgb_acf_json_save_point');
function mgb_acf_json_save_point( $path ) {   
    // update path
    $path = plugin_dir_path( __DIR__ ) . 'includes/acf-json';  
    // return
    return $path;
}
// Modifica de donde carga el json
add_filter('acf/settings/load_json', 'mgb_acf_json_load_point');
function mgb_acf_json_load_point( $paths ) { 
    // remove original path (optional)
    unset($paths[0]); 
    // append path
    $paths[] = plugin_dir_path( __DIR__ ) . 'includes/acf-json';
    // return
    return $paths;
}

// Añade imagen al icono de subida del pdf
add_filter( 'wp_mime_type_icon', 'mgb_acf_change_icon_on_files', 10, 3 );
function mgb_acf_change_icon_on_files ( $icon, $mime, $attachment_id ){
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) === false && $mime === 'application/pdf' ){
        $get_image = wp_get_attachment_image_src ( $attachment_id, 'thumbnail' );
        if ( $get_image ) {
            $icon = $get_image[0];
        } 
    }
    return $icon;
}

// Cambiamos la fecha del post a la fecha de la factura
add_action('acf/save_post', 'mgb_acf_save_post');
function mgb_acf_save_post( $post_id ) {
    if ( 'gastos' == get_post_type() ) {
        $fecha_fra = get_field('gasto_fecha_factura', $post_id);
        $post_date = DateTime::createFromFormat('d/m/Y',$fecha_fra)->format('Y-m-d');
        $my_post = array();
        $my_post['ID'] = $post_id;
        $my_post['post_date'] = $post_date;
        wp_update_post( $my_post );

    }
}