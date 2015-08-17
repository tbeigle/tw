<?php

function ninja_forms_return_echo($function_name){
	$arguments = func_get_args();
    array_shift($arguments); // We need to remove the first arg ($function_name)
    ob_start();
    call_user_func_array($function_name, $arguments);
	$return = ob_get_clean();
	return $return;
}

function ninja_forms_random_string($length = 10){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $random_string;
}

function ninja_forms_remove_from_array($arr, $key, $val, $within = FALSE) {
    foreach ($arr as $i => $array)
            if ($within && stripos($array[$key], $val) !== FALSE && (gettype($val) === gettype($array[$key])))
                unset($arr[$i]);
            elseif ($array[$key] === $val)
                unset($arr[$i]);

    return array_values($arr);
}

function ninja_forms_letters_to_numbers( $size ) {
	$l		= substr( $size, -1 );
	$ret	= substr( $size, 0, -1 );
	switch( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
	}
	return $ret;
}

function ninja_forms_subval_sort( $a, $subkey ) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	if ( is_array ( $b ) ) {
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;		
	} else {
		return $a;
	}

}

/**
 * Return the begin date with an added 00:00:00.
 * Checks for the current date format setting and tries to respect it.
 * 
 * @since 2.7
 * @param string $begin_date
 * @return string $begin_date
 */
function nf_get_begin_date( $begin_date ) {
	$plugin_settings = nf_get_settings();

	if ( isset ( $plugin_settings['date_format'] ) ) {
		$date_format = $plugin_settings['date_format'];
	} else {
		$date_format = 'm/d/Y';
	}

	if ( $date_format == 'd/m/Y' ) {
		$begin_date = str_replace( '/', '-', $begin_date );
	} else if ( $date_format == 'm-d-Y' ) {
		$begin_date = str_replace( '-', '/', $begin_date );
	}
	$begin_date .= '00:00:00';
	$begin_date = new DateTime( $begin_date );

	return $begin_date;
}

/**
 * Return the end date with an added 23:59:59.
 * Checks for the current date format setting and tries to respect it.
 * 
 * @since 2.7
 * @param string $end_date
 * @return string $end_date
 */
function nf_get_end_date( $end_date ) {
	$plugin_settings = nf_get_settings();

	if ( isset ( $plugin_settings['date_format'] ) ) {
		$date_format = $plugin_settings['date_format'];
	} else {
		$date_format = 'm/d/Y';
	}

	if ( $date_format == 'd/m/Y' ) {
		$end_date = str_replace( '/', '-', $end_date );
	} else if ( $date_format == 'm-d-Y' ) {
		$end_date = str_replace( '-', '/', $end_date );
	}
	$end_date .= '23:59:59';
	$end_date = new DateTime( $end_date );

	return $end_date;
}

/**
 * Checks whether function is disabled.
 *
 * @since 2.7
 *
 * @param string  $function Name of the function.
 * @return bool Whether or not function is disabled.
 */
function nf_is_func_disabled( $function ) {
	$disabled = explode( ',',  ini_get( 'disable_functions' ) );

	return in_array( $function, $disabled );
}