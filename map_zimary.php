<?php
/*
Plugin Name: Map for the camp Zimary
Description: Interactive map for the camp Zimary
Version: 2.0.1
Author: Salivon Eugene
*/

$fpath = trailingslashit(dirname(__FILE__));

//Activation of the page for plugin settings
add_action('admin_menu', array('MapZimary', 'MapSettingsInit'));
add_action( 'admin_init', array('MapZimary', 'InitDB'));
	
//Map shortcode
function build_map_z(){
	
	wp_register_style('style_map_z', plugins_url( '/css/zimary.css', __FILE__ ));
    wp_enqueue_style( 'style_map_z'); 
	wp_register_style('font-awesome-z', plugins_url( '/css/font-awesome.min.css', __FILE__ ));
    wp_enqueue_style( 'font-awesome-z'); 	    	
	wp_register_script( 'raphael_z', plugins_url( '/js/raphael.js', __FILE__ ) );
	wp_enqueue_script(  'raphael_z');
	wp_register_script( 'init_z', plugins_url( '/js/init.js', __FILE__ ) );
	wp_enqueue_script(  'init_z');
		
	if( current_user_can('edit_others_pages') ) { 
		if($_GET['editor_map']==true) echo '<a href="./">Выйти из режима редактирования</a>';
		else echo '<a href="?editor_map=true">Редактировать карту</a>';
	}
	echo '<div id="zmap"></div>';
}

add_shortcode( 'ZimaryM', 'build_map_z' );

//Class for handling map areas on manager side
class MapZimary{

	function __construct(){

	}

	function MapSettingsInit(){
		add_menu_page('Map Zimary Settings', 'Map Zimary Settings', 'edit_posts', 'zimary_map', array('MapZimary', 'MapZimarySettings'), plugin_dir_url( __FILE__ ) .'/images/zicon.png');
	}

	//Preparing table in db
	function InitDB(){
		global $wpdb;

		$tb_name = $wpdb->get_blog_prefix() . 'map_zimary';

		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$sql = "CREATE TABLE IF NOT EXISTS {$tb_name} (
					`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`element_uid` VARCHAR(100) DEFAULT NULL,
					`element_name` VARCHAR(500) NOT NULL,
					`element_status` VARCHAR(500) NOT NULL,
					`d` mediumtext NOT NULL,
					`element_place` VARCHAR(500) NOT NULL,
					`element_price` VARCHAR(500) NOT NULL,
					`element_photo` BIGINT(20) NOT NULL,
					`element_price_new` VARCHAR(500) NOT NULL,
					`element_cadaster_number` VARCHAR(50) NOT NULL
		) {$charset_collate};";

		dbDelta($sql);
	}

	//Page for plugin settings
	function MapZimarySettings(){
		?>
		<div class="wrap">
			<h2>Список участков поселка "Зимари"</h2>
			<section class="grid">
				
			</section>
		</div>
		<?php
	}		
			
}
?>