<?php
add_action( 'nf_save_sub', 'nf_csv_attachment' );

function nf_csv_attachment( $sub_id ){
	global $ninja_forms_processing;

	// make sure this form is supposed to attach a CSV
	if( 1 == $ninja_forms_processing->get_form_setting( 'admin_attach_csv' ) AND 'submit' == $ninja_forms_processing->get_action() ) {
		
		// create CSV content
		$csv_content = Ninja_Forms()->sub( $sub_id )->export( true );
		
		$upload_dir = wp_upload_dir();
		$path = trailingslashit( $upload_dir['path'] );

		// create temporary file
		$path = tempnam( $path, 'Sub' );
		$temp_file = fopen( $path, 'r+' );
		
		// write to temp file
		fwrite( $temp_file, $csv_content );
		fclose( $temp_file );
		
		// find the directory we will be using for the final file
		$path = pathinfo( $path );
		$dir = $path['dirname'];
		$basename = $path['basename'];
		
		// create name for file
		$new_name = apply_filters( 'ninja_forms_submission_csv_name', 'ninja-forms-submission' );
		
		// remove a file if it already exists
		if( file_exists( $dir.'/'.$new_name.'.csv' ) ) {
			unlink( $dir.'/'.$new_name.'.csv' );
		}
		
		// move file
		rename( $dir.'/'.$basename, $dir.'/'.$new_name.'.csv' );
		$file1 = $dir.'/'.$new_name.'.csv';
		
		// add new file to array of existing files
		$files = $ninja_forms_processing->get_form_setting( 'admin_attachments' );
		array_push( $files, $file1 );
		$ninja_forms_processing->update_form_setting( 'admin_attachments', $files );
		$ninja_forms_processing->update_extra_value( '_attachment_csv_path', $file1 );
	}
}