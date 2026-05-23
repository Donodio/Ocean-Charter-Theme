<?php
/**
 * One Click Demo Import (OCDI) Support
 *
 * @package OceanCharter
 */

function ocean_charter_import_files() {
	return array(
		array(
			'import_file_name'             => 'Ocean Charter Luxury Demo',
			'categories'                   => array( 'Luxury', 'Yachting' ),
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'ocdi/demo-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'ocdi/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'ocdi/customizer.dat',
			'import_preview_image_url'     => get_template_directory_uri() . '/screenshot.png',
			'import_notice'                => __( 'After you import this demo, you will have the complete luxury yacht demo ready to go. The process may take a few minutes as high-resolution images are downloaded.', 'ocean-charter' ),
			'preview_url'                  => 'https://example.com/ocean-charter',
		),
	);
}
add_filter( 'ocdi/import_files', 'ocean_charter_import_files' );

function ocean_charter_after_import_setup() {
	// Assign front page and posts page (if any).
	$front_page_id = get_page_by_title( 'Home' );

	if ( $front_page_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
	}

	// Assign menus to locations.
	$main_menu   = get_term_by( 'name', 'Main Menu', 'nav_menu' );
	$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

	$locations = get_theme_mod( 'nav_menu_locations' );

	if ( $main_menu ) {
		$locations['primary'] = $main_menu->term_id;
	}

	if ( $footer_menu ) {
		$locations['footer'] = $footer_menu->term_id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'ocdi/after_import', 'ocean_charter_after_import_setup' );
