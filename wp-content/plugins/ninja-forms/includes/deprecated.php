<?php

/**
 * Deprecated as of version 2.7.
 */

// Hook into our new save sub filter to add any deprecated filters
function nf_old_save_sub_filter( $user_value, $field_id ) {
	return apply_filters( 'ninja_forms_save_sub', $user_value, $field_id );
}

add_filter( 'nf_save_sub_user_value', 'nf_old_save_sub_filter', 10, 2 );

// Hook into our new nf_save_sub action and add any actions hooked into our old action hooks.
function nf_old_save_sub_action( $sub_id ) {
	do_action( 'ninja_forms_insert_sub', $sub_id );
}

add_action( 'nf_save_sub', 'nf_old_save_sub_action' );

// Hook into our new submissions CSV filename filter.
function nf_old_subs_csv_filename( $filename ) {
	return apply_filters( 'ninja_forms_export_subs_csv_file_name', $filename );
}

add_filter( 'nf_subs_csv_filename', 'nf_old_subs_csv_filename' );

// Hook into our new submissions CSV label filter.
function nf_old_subs_csv_label( $label, $field_id ) {
	return apply_filters( 'ninja_forms_export_sub_label', $label, $field_id );
}

add_filter( 'nf_subs_csv_field_label', 'nf_old_subs_csv_label', 10, 2 );

// Hook into our new submissions CSV label array filter.
function nf_old_subs_csv_label_array( $label_array, $sub_ids ) {
	return apply_filters( 'ninja_forms_export_subs_label_array', $label_array, $sub_ids );
}

add_filter( 'nf_subs_csv_label_array', 'nf_old_subs_csv_label_array', 10, 2 );

// Hook into our new submissions CSV pre_value filter.
function nf_old_subs_csv_pre_value( $user_value, $field_id ) {
	return apply_filters( 'ninja_forms_export_sub_pre_value', $user_value, $field_id );
}

add_filter( 'nf_subs_export_pre_value', 'nf_old_subs_csv_pre_value', 10, 2 );

// Hook into our new submissions CSV value filter.
function nf_old_subs_csv_value( $user_value, $field_id ) {
	return apply_filters( 'ninja_forms_export_sub_value', $user_value, $field_id );
}

add_filter( 'nf_subs_csv_field_value', 'nf_old_subs_csv_value', 10, 2 );

// Hook into our new submissions CSV value array filter.
function nf_old_subs_csv_value_array( $values_array, $sub_ids ) {
	return apply_filters( 'ninja_forms_export_subs_value_array', $values_array, $sub_ids );
}

add_filter( 'nf_subs_csv_value_array', 'nf_old_subs_csv_value_array', 10, 2 );

// Hook into our new CSV BOM filter
function nf_old_subs_csv_bom( $bom ) {
	return apply_filters( 'ninja_forms_csv_bom', $bom );
}

add_filter( 'nf_sub_csv_bom', 'nf_old_subs_csv_bom' );

// Hook into our new CSV delimiter filter
function nf_old_subs_csv_delimiter( $delimiter ) {
	return apply_filters( 'ninja_forms_csv_delimiter', $delimiter );
}

add_filter( 'nf_sub_csv_delimiter', 'nf_old_subs_csv_delimiter' );

// Hook into our new CSV enclosure filter
function nf_old_subs_csv_enclosure( $enclosure ) {
	return apply_filters( 'ninja_forms_csv_enclosure', $enclosure );
}

add_filter( 'nf_sub_csv_enclosure', 'nf_old_subs_csv_enclosure' );

// Hook into our new CSV terminator filter
function nf_old_subs_csv_terminator( $terminator ) {
	return apply_filters( 'ninja_forms_csv_terminator', $terminator );
}

add_filter( 'nf_sub_csv_terminator', 'nf_old_subs_csv_terminator' );

// Hook into our new Submissions table row-actions filter
function nf_old_subs_table_row_actions_filter( $actions, $sub_id, $form_id ) {
	return apply_filters( 'ninja_forms_sub_table_row_actions', array(), false, $sub_id, $form_id );
}

add_filter( 'nf_sub_table_row_actions', 'nf_old_subs_table_row_actions_filter', 10, 3 );

/**
 * ninja_forms_get_subs() has been deprecated in favour of Ninja_Forms()->subs()->get( $args ) or Ninja_Forms()->form( 23 )->get_subs( $args )
 * You can also use WordPress queries ,since this is a custom post type.
 * 
 * @since 2.7
 */

function ninja_forms_get_subs( $args = array() ) {

	$plugin_settings = nf_get_settings();

	if ( isset ( $plugin_settings['date_format'] ) ) {
		$date_format = $plugin_settings['date_format'];
	} else {
		$date_format = 'm/d/Y';
	}

	if( is_array( $args ) AND ! empty( $args ) ) {

		$subs_results = array();
		$meta_query = array();
		$date_query = array();

		if( isset( $args['form_id'] ) ) {
			$meta_query[] = array(
				'key' => '_form_id',
				'value' => $args['form_id'],
			);
		}

		if( isset( $args['user_id'] ) ) {
			$query_args['author'] = $args['user_id'];
		}

		if( isset( $args['action'])){
			$meta_query[] = array(
				'key' => '_action',
				'value' => $args['action'],
			);
		}

		if( isset( $args['begin_date'] ) AND $args['begin_date'] != '') {
			$query_args['date_query']['after'] = nf_get_begin_date( $args['begin_date'] )->format("Y-m-d G:i:s");
		}

		if( isset( $args['end_date'] ) AND $args['end_date'] != '' ) {
			$query_args['date_query']['before'] = nf_get_end_date( $args['end_date'] )->format("Y-m-d G:i:s");
		}

		$query_args = array(
			'post_type' 	=> 'nf_sub',
			'date_query' 	=> $date_query,
			'meta_query' 	=> $meta_query,
			'posts_per_page'	=> -1,
		);

		$subs = get_posts( $query_args );

		if ( is_array( $subs ) && ! empty( $subs ) ) {
			$x = 0;
			foreach ( $subs as $sub ) {
				$data = array();
				$subs_results[$x]['id'] = $sub->ID;
				$subs_results[$x]['user_id'] = $sub->post_author;
				$subs_results[$x]['form_id'] = get_post_meta( $sub->ID, '_form_id' );
				$subs_results[$x]['action'] = get_post_meta( $sub->ID, '_action' );

				$meta = get_post_custom( $sub->ID );

				foreach ( $meta as $key => $array ) {
					if ( strpos( $key, '_field_' ) !== false ) {
						$field_id = str_replace( '_field_', '', $key );
						$user_value = $array[0];
						$data[] = array( 'field_id' => $field_id, 'user_value' => $user_value );
					}
				}

				$subs_results[$x]['data'] = $data;
				$subs_results[$x]['date_updated'] = $sub->post_modified;

				$x++;
			}
		}

		return $subs_results;
	}
}

/**
 * ninja_forms_get_sub_count() has been deprecated in favour of Ninja_Forms()->form( 23 )->sub_count or nf_get_sub_count()
 * Function that returns a count of the number of submissions.
 *
 * @since 2.7
 */

function ninja_forms_get_sub_count( $args = array() ) {
	return count( ninja_forms_get_subs( $args ) );
}

/**
 * ninja_forms_get_sub_by_id( $sub_id ) has been deprecated in favour of Ninja_Forms()->sub( 23 );
 * 
 * @since 2.7
 */

function ninja_forms_get_sub_by_id( $sub_id ) {
	$sub = Ninja_Forms()->sub( $sub_id );
	if ( $sub ) {
		$sub_row = array();
		$data = array();
		$sub_row['id'] = $sub_id;
		$sub_row['user_id'] = $sub->user_id;
		$sub_row['form_id'] = $sub->form_id;
		$sub_row['action'] = $sub->action;

		if ( $sub->action == 'submit' ) {
			$sub_row['status'] = 1;
		} else {
			$sub_row['status'] = 0;
		}

		$meta = get_post_custom( $sub_id );

		foreach ( $meta as $key => $array ) {
			if ( strpos( $key, '_field_' ) !== false ) {
				$field_id = str_replace( '_field_', '', $key );
				$user_value = is_serialized( $array[0] ) ? unserialize( $array[0] ) : $array[0];
				$data[] = array( 'field_id' => $field_id, 'user_value' => $user_value );
			}
		}

		$sub_row['data'] = $data;
		$sub_row['date_updated'] = $sub->date_submitted;

		return $sub_row;
	} else {
		return false;
	}
}

/**
 * ninja_forms_get_all_subs() has been deprecated in favour of Ninja_Forms()->subs()->get();
 * 
 * @since 2.7
 */

 function ninja_forms_get_all_subs( $form_id = '' ){
	if ( $form_id == '' )
		return false;

	$args = array( 'form_id' => $form_id );
	return ninja_forms_get_subs( $args );
}

/**
 * ninja_forms_insert_sub() has been deprecated in favour of Ninja_Forms()->subs()->create( $form_id );
 * Because submissions are now a CPT, this function will only return false. 
 * Please replace any instances of this function with the replacement.
 * 
 * @since 2.7
 */

function ninja_forms_insert_sub( $args ) {

	if ( ! isset ( $args['form_id'] ) )
		return false;

	$form_id = $args['form_id'];
	
	$sub_id = Ninja_Forms()->subs()->create( $form_id );
	$args['sub_id'] = $sub_id;

	ninja_forms_update_sub( $args );

	return $sub_id;
}

/**
 * ninja_forms_update_sub() has been deprecated in favour of Ninja_Forms()->sub( 23 )->update_field( id, value );
 * Because submissions are now a CPT, this function will only return false. 
 * Please replace any instances of this function with the replacement.
 * 
 * @since 2.7
 */

function ninja_forms_update_sub( $args ){
	if ( ! isset ( $args['sub_id'] ) )
		return false;

	$sub_id = $args['sub_id'];
	$sub = Ninja_Forms()->sub( $sub_id );

	if ( isset ( $args['data'] ) ) {
		$data = $args['data'];
		unset ( $args['data'] );

		if ( is_serialized( $data ) ) {
			$data = unserialize( $data );

			foreach ( $data as $d ) {
				$field_id = $d['field_id'];
				$user_value = $d['user_value'];
				$sub->add_field( $field_id, $user_value );
			}
		}		
	}

	foreach ( $args as $key => $value ) {
		$sub->update_meta( '_' . $key, $value );
	}

}

/**
 * ninja_forms_export_subs_to_csv() has been deprecated in favour of Ninja_Forms()->subs()->export( sub_ids, return );
 * or Ninja_Forms()->sub( 23 )->export( return );
 * Please replace any instances of this function with the replacement.
 * 
 * @since 2.7
 */

function ninja_forms_export_subs_to_csv( $sub_ids = '', $return = false ){
	Ninja_Forms()->subs()->export( $sub_ids, $return );
}

function ninja_forms_implode_r($glue, $pieces){
	$out = '';
	foreach ( $pieces as $piece ) {
		if ( is_array ( $piece ) ) {
			if ( $out == '' ) {
				$out = ninja_forms_implode_r($glue, $piece);
			} else {
				$out .= ninja_forms_implode_r($glue, $piece); // recurse
			}			
		} else {
			if ( $out == '' ) {
				$out .= $piece;
			} else {
				$out .= $glue.$piece;
			}
		}
	}
	return $out;
}


/**
 * Get the csv delimiter
 * 
 * @return string
 */
function ninja_forms_get_csv_delimiter() {
	return apply_filters( 'ninja_forms_csv_delimiter', ',' );
}


/**
 * Get the csv enclosure
 * 
 * @return string
 */
function ninja_forms_get_csv_enclosure() {
	return apply_filters( 'ninja_forms_csv_enclosure', '"' );
}


/**
 * Get the csv delimiter
 * 
 * @return string
 */
function ninja_forms_get_csv_terminator() {
	return apply_filters( 'ninja_forms_csv_terminator', "\n" );
}

/**
 * Wrapper for nf_save_sub()
 */
function ninja_forms_save_sub() {
	nf_save_sub();
}

function nf_change_all_forms_filter( $cap ) {
	return apply_filters( 'ninja_forms_admin_menu_capabilities', $cap );
}

add_filter( 'ninja_forms_admin_all_forms_capabilities', 'nf_change_all_forms_filter' );

function nf_change_admin_menu_filter( $cap ) {
	return apply_filters( 'ninja_forms_admin_menu_capabilities', $cap );
}

add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'nf_change_admin_menu_filter' );