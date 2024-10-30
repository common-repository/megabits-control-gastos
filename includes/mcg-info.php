<?php
// Sub-página en el CPT Gastos para mostrar las facturas
add_action( 'admin_menu', 'mgb_custom_page_gastos_info');
function mgb_custom_page_gastos_info() {
add_submenu_page( '/edit.php?post_type=gastos', __( 'Info', 'mgb-control-gastos' ), __( 'Info', 'mgb-control-gastos' ), 'manage_options', 'gastos-info', 'mgb_gastos_info_callback');
}
function mgb_gastos_info_callback() {
	?>
	<div class="info_div1">
		<img src="<?php echo esc_url( plugins_url( 'assets/banner-772x250.png', dirname(__FILE__) ) );?>" class="div1_img">
		<h2>CONTROL GASTOS - INFORMACIÓN</h2>
	</div>
	<div class="info_div2">
		<?php
		$file = plugin_dir_path( __DIR__ )."readme.txt";
		$myfile = fopen($file, 'r') or die("Unable to open file!");
		while(!feof($myfile)) {
  			echo fgets($myfile) . "<br>";
		}
		fclose($myfile);
		?>
	</div>
	<?php
}