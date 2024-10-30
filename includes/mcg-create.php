<?php
/*		GASTOS		*/
// Register Custom Post Type Gastos
if ( ! function_exists('mgb_gastos_post_type') ) {
function mgb_gastos_post_type() {

	$labels = array(
		'name'                  => _x( 'Gastos', 'Post Type General Name', 'mgb-control-gastos' ),
		'singular_name'         => _x( 'Gasto', 'Post Type Singular Name', 'mgb-control-gastos' ),
		'menu_name'             => __( 'Gastos', 'mgb-control-gastos' ),
		'name_admin_bar'        => __( 'Gastos', 'mgb-control-gastos' ),
		'archives'              => __( 'gasto_file', 'mgb-control-gastos' ),
		'attributes'            => __( 'gasto_atributo', 'mgb-control-gastos' ),
		'parent_item_colon'     => __( 'Gasto Superior', 'mgb-control-gastos' ),
		'all_items'             => __( 'Todos los Gastos', 'mgb-control-gastos' ),
		'add_new_item'          => __( 'Añadir nuevo Gasto', 'mgb-control-gastos' ),
		'add_new'               => __( 'Añadir Nuevo', 'mgb-control-gastos' ),
		'new_item'              => __( 'Nuevo Gasto', 'mgb-control-gastos' ),
		'edit_item'             => __( 'Editar Gasto', 'mgb-control-gastos' ),
		'update_item'           => __( 'Actualizar Gasto', 'mgb-control-gastos' ),
		'view_item'             => __( 'Ver Gasto', 'mgb-control-gastos' ),
		'view_items'            => __( 'Ver Gastos', 'mgb-control-gastos' ),
		'search_items'          => __( 'Buscar Gasto', 'mgb-control-gastos' ),
		'not_found'             => __( 'No encontrado', 'mgb-control-gastos' ),
		'not_found_in_trash'    => __( 'No encontrado en papelera', 'mgb-control-gastos' ),
		'featured_image'        => __( 'Imagen', 'mgb-control-gastos' ),
		'set_featured_image'    => __( 'Establecer Imagen', 'mgb-control-gastos' ),
		'remove_featured_image' => __( 'Quitar Imagen', 'mgb-control-gastos' ),
		'use_featured_image'    => __( 'Usar como Imagen', 'mgb-control-gastos' ),
		'insert_into_item'      => __( 'Insertar', 'mgb-control-gastos' ),
		'uploaded_to_this_item' => __( 'Subir', 'mgb-control-gastos' ),
		'items_list'            => __( 'Lista de Gastos', 'mgb-control-gastos' ),
		'items_list_navigation' => __( 'Navegación por la lista', 'mgb-control-gastos' ),
		'filter_items_list'     => __( 'Filtrar Lista', 'mgb-control-gastos' ),
	);
	$args = array(
		'label'                 => __( 'Gasto', 'mgb-control-gastos' ),
		'description'           => __( 'Gastos', 'mgb-control-gastos' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-rest-api',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => false,
	);
	register_post_type( 'gastos', $args );

}
add_action( 'init', 'mgb_gastos_post_type', 0 );
}

// Registro de Taxonomías de Gastos
function mgb_taxonomias_gastos() {
	register_taxonomy( 'categoria_gasto',
	array (0 => 'gastos'),
	array(
		'hierarchical'      => true,
		'label'             => __('Categorías','mgb-control-gastos'),
		'show_ui'           => true,
		'query_var'         => true,
		'show_admin_column' => true,
		'labels'            => array (
			'search_items'               => __('Buscar Categoría','mgb-control-gastos'),
			'popular_items'              => __('Más populares','mgb-control-gastos'),
			'all_items'                  => __('Todas','mgb-control-gastos'),
			'parent_item'                => __('Superior','mgb-control-gastos'),
			'parent_item_colon'          => __('Categoría superior','mgb-control-gastos'),
			'edit_item'                  => __('Editar Categoría','mgb-control-gastos'),
			'update_item'                => __('Actualizar Categoría','mgb-control-gastos'),
			'add_new_item'               => __('Añadir nueva Categoría','mgb-control-gastos'),
			'new_item_name'              => __('Nueva Categoría','mgb-control-gastos'),
			'separate_items_with_commas' => __('Separar por comas','mgb-control-gastos'),
			'add_or_remove_items'        => __('Añadir o borrar','mgb-control-gastos'),
			'choose_from_most_used'      => __('Elegir de las más usadas','mgb-control-gastos'),
		)
	) );
	register_taxonomy( 'proveedor_gasto',
	array (0 => 'gastos'),
	array(
		'hierarchical'      => true,
		'label'             => __('Proveedores','mgb-control-gastos'),
		'show_ui'           => true,
		'query_var'         => true,
		'show_admin_column' => true,
		'labels'            => array (
			'search_items'               => __('Buscar Proveedor','mgb-control-gastos'),
			'popular_items'              => __('Más populares','mgb-control-gastos'),
			'all_items'                  => __('Todos','mgb-control-gastos'),
			'parent_item'                => __('Superior','mgb-control-gastos'),
			'parent_item_colon'          => __('Proveedor superior','mgb-control-gastos'),
			'edit_item'                  => __('Editar Proveedor','mgb-control-gastos'),
			'update_item'                => __('Actualizar Proveedor','mgb-control-gastos'),
			'add_new_item'               => __('Añadir nuevo Proveedor','mgb-control-gastos'),
			'new_item_name'              => __('Nuevo Proveedor','mgb-control-gastos'),
			'separate_items_with_commas' => __('Separar por comas','mgb-control-gastos'),
			'add_or_remove_items'        => __('Añadir o borrar','mgb-control-gastos'),
			'choose_from_most_used'      => __('Elegir de las más usados','mgb-control-gastos'),
		)
	) );
}
add_action( 'init', 'mgb_taxonomias_gastos');

// Cambios en las columnas del  Custom Post Type Gastos
add_filter( 'manage_gastos_posts_columns', 'mcg_posts_columns' );
add_action( 'manage_gastos_posts_custom_column', 'mcg_posts_custom_columns', 10, 2 );

function mcg_posts_columns( $columns ){
	unset($columns['title']);
    unset($columns['taxonomy-categoria_gasto']);
    unset($columns['taxonomy-proveedor_gasto']);
    unset($columns['date']);
	return array_merge ( $columns, array ( 
     'title' => __( 'Título','mgb-control-gastos'),
     'fra_num' => __( 'Fra. número','mgb-control-gastos'),
     'mcg_post_thumbs' => __('Imagen','mgb-control-gastos'),
	 'taxonomy-categoria_gasto'   => __( 'Categoría','mgb-control-gastos' ),
	 'taxonomy-proveedor_gasto'   => __( 'Proveedor','mgb-control-gastos' ),
	 'factura_importe'   => __( 'Fra. Importe','mgb-control-gastos' ),
     'factura_fecha' => __('Fra. Fecha','mgb-control-gastos'),
   ) );
}
function mcg_posts_custom_columns( $column_name, $id ) {
	switch ($column_name) {
		case 'fra_num':
			the_field('gasto_num_fra');
			break;
		case 'mcg_post_thumbs':
			$attachments = get_posts( array(
				'post_type'   => 'attachment',
				'numberposts' => 1,
				'post_status' => null,
				'post_parent' => $post->ID
			) );
			$gasto_title = get_the_title($post->ID);
			if ( $attachments ) {
				foreach ( $attachments as $attachment ) {
					
					$file = get_field('gasto_file', $post->ID);
					$url = $file['url'];
					$image_id = $file['id'];
					$image_url = wp_get_attachment_image($image_id, array('50', '60'), true );
					?>
					    <a href="<?php echo esc_url($url); ?>?TB_iframe=true&width=700&height=600" title="<?php echo esc_attr($gasto_title); ?>" class="thickbox">
					    	<?php echo wp_kses_post($image_url); ?>
					    </a
					<?php
				}
			}
			break;
		case 'factura_importe':
			$factura_importe = get_field('gasto_importe') + get_field('gasto_iva');
			echo number_format($factura_importe, 2, ",", ".") . "€";
			break;
		case 'factura_fecha':
			the_field('gasto_fecha_factura');
			break;

    }
}