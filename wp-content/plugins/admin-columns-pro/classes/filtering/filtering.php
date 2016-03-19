<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CAC_FC_URL', plugins_url( '', __FILE__ ) );
define( 'CAC_FC_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Addon class
 *
 * @since 1.0
 */
class CAC_Addon_Filtering {

	private $cpac;

	private $models;

	function __construct() {

		// init addon
		add_action( 'cac/loaded', array( $this, 'init' ) );

		// Add column properties
		add_filter( 'cac/column/default_properties', array( $this, 'set_column_default_properties' ) );

		// Add column options
		add_filter( 'cac/column/default_options', array( $this, 'set_column_default_options' ) );

		// Add setting field
		add_action( 'cac/column/settings_after', array( $this, 'add_settings_field' ), 9 );

		// add setting filtering indicator
		add_action( 'cac/column/settings_meta', array( $this, 'add_label_filter_indicator' ), 9 );

		// clear timeout
		add_action( 'cac/storage_model/columns_stored', array( $this, 'clear_timeout' ), 10, 2 );

		// hides default dropdown, eg. date and categories
		add_action( 'admin_head', array( $this, 'maybe_hide_default_dropdowns' ) );
	}

	/**
	 * @since 3.8
	 */
	public function maybe_hide_default_dropdowns() {
		if ( ! $storage_model = cpac()->get_current_storage_model() ) {
			return;
		}
		if ( ! $model = $this->get_model( $storage_model->key ) ) {
			return;
		}

		$disabled = array();
		if ( $element_ids = $model->get_dropdown_html_element_ids() ) {
			$columns = $storage_model->get_columns();
			foreach ( $element_ids as $column_name => $id ) {
				if ( ! isset( $columns[ $column_name ] ) || ( isset( $columns[ $column_name ] ) && ! $model->is_filterable( $columns[ $column_name ] ) ) ) {
					$disabled[] = '#' . $id;
				}
			}
		}

		if ( $disabled ) { ?>
			<style type="text/css">
				<?php echo implode( ', ', $disabled ) . '{ display: none; }'; ?>
			</style>
			<?php
		}
	}

	/**
	 * Init Addons
	 *
	 * @since 1.0
	 */
	public function init( $cpac ) {
		if ( ! $cpac->is_cac_screen() ) {
			return;
		}

		$this->cpac = $cpac;

		include_once CAC_FC_DIR . 'classes/model.php';
		include_once CAC_FC_DIR . 'classes/post-object.php';

		foreach ( cpac()->get_storage_models() as $storage_model ) {
			if ( $storage_model->subpage ) {
				continue;
			}

			$filtering_model = false;

			switch ( $storage_model->get_type() ) {
				case 'post' :
					include_once CAC_FC_DIR . 'classes/post.php';
					$filtering_model = new CAC_Filtering_Model_Post( $storage_model );
					break;

				case 'user' :

					// Multisite
					if ( 'wp-ms_users' == $storage_model->key ) {
						include_once CAC_FC_DIR . 'classes/ms-user.php';
						$filtering_model = new CAC_Filtering_Model_MS_User( $storage_model );
					}

					// User
					else {
						include_once CAC_FC_DIR . 'classes/user.php';
						$filtering_model = new CAC_Filtering_Model_User( $storage_model );
					}
					break;

				case 'media' :
					include_once CAC_FC_DIR . 'classes/media.php';
					$filtering_model = new CAC_Filtering_Model_Media( $storage_model );
					break;

				case 'comment' :
					include_once CAC_FC_DIR . 'classes/comment.php';
					$filtering_model = new CAC_Filtering_Model_Comment( $storage_model );
					break;
			}

			if ( $filtering_model ) {
				$this->models[ $storage_model->key ] = $filtering_model;
			}
		}

		// Init hooks for columns screen
		if ( $storage_model = $this->cpac->get_current_storage_model() ) {
			if ( $model = $this->get_model( $storage_model->key ) ) {
				$model->init_hooks();
			}
		}

		// Styling & scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * @since 1.0
	 */
	public function scripts() {

		$minified = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Settings screen
		if ( cpac()->is_settings_screen() ) {
			wp_enqueue_style( 'cac-addon-filtering-css', CAC_FC_URL . "/assets/css/filtering{$minified}.css", array(), CAC_PRO_VERSION, 'all' );
		}

		// Listings screen
		else if ( cpac()->is_columns_screen() && ( $storage_model = cpac()->get_current_storage_model() ) ) {

			wp_register_script( 'cac-addon-filtering-listings-js', CAC_FC_URL . '/assets/js/listings_screen.js', array( 'jquery' ), CAC_PRO_VERSION );
			wp_localize_script( 'cac-addon-filtering-listings-js', 'CAC_Filtering', array(
					'storage_model' => $storage_model->key,
					'layout'        => $storage_model->get_layout(),
					'nonce'         => wp_create_nonce( 'ac-filtering' )
				)
			);

			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'cac-addon-filtering-listings-js' );

			wp_enqueue_style( 'cac-addon-filtering-listings-css', CAC_FC_URL . "/assets/css/listings_screen{$minified}.css", array(), CAC_PRO_VERSION, 'all' );
		}
	}

	/**
	 * @since 1.0
	 * Property user_filter_operator: Determine if operators like > >= < <= can be used
	 * Property filterable_type: Specify the filter type like ACF date etc
	 */
	public function set_column_default_properties( $properties ) {
		$properties['is_filterable'] = false;
		$properties['filterable_type'] = null;
		$properties['use_filter_operator'] = false;

		return $properties;
	}

	/**
	 * @since 1.0
	 */
	public function set_column_default_options( $options ) {
		$options['filter'] = 'off';
		$options['filter_format'] = '';

		return $options;
	}

	/**
	 * @since 1.0
	 */
	public function add_settings_field( $column ) {

		if ( ! $column->properties->is_filterable ) {
			return false;
		}
		?>
		<tr class="column_filtering">
			<?php $column->label_view( __( 'Enable filtering?', 'codepress-admin-columns' ), __( 'This will make the column support filtering.', 'codepress-admin-columns' ), 'filter' ); ?>
			<td class="input" data-toggle-id="<?php $column->attr_id( 'filter' ); ?>">
				<label for="<?php $column->attr_id( 'filter' ); ?>-on">
					<input type="radio" value="on" name="<?php $column->attr_name( 'filter' ); ?>" id="<?php $column->attr_id( 'filter' ); ?>-on"<?php checked( $column->options->filter, 'on' ); ?>>
					<?php _e( 'Yes' ); ?>
				</label>
				<label for="<?php $column->attr_id( 'filter' ); ?>-off">
					<input type="radio" value="off" name="<?php $column->attr_name( 'filter' ); ?>" id="<?php $column->attr_id( 'filter' ); ?>-off"<?php checked( $column->options->filter, '' ); ?><?php checked( $column->options->filter, 'off' ); ?>>
					<?php _e( 'No' ); ?>
				</label>
			</td>
		</tr>

		<?php

		$filter_items = array();

		switch ( $column->get_property( 'filterable_type' ) ) {
			case 'date' :
			case 'date_format' :
				$filter_items = array(
					''            => __( 'Daily' ),
					'monthly'     => __( 'Monthly' ),
					'yearly'      => __( 'Yearly' ),
					'future_past' => __( 'Future / Past', 'codepress-admin-columns' ),
					'range'       => __( 'Range', 'codepress-admin-columns' )
				);
				break;
			case 'numeric' :
				$filter_items = array(
					''      => __( 'Exact match', 'codepress-admin-columns' ),
					'range' => __( 'Range', 'codepress-admin-columns' ),
				);
				break;
		}

		if ( $filter_items ) {
			$column->display_field_select(
				'filter_format',
				__( 'Filter by:', 'codepress-admin-columns' ),
				$filter_items,
				__( "This will allow you to set the filter format.", 'codepress-admin-columns' ),
				'filter'
			);
		}
	}

	/**
	 * @since 1.0
	 */
	public function add_label_filter_indicator( $column ) {
		if ( $column->properties->is_filterable ) : ?>
			<span class="filtering <?php echo esc_attr( $column->options->filter ); ?>" data-indicator-id="<?php $column->attr_id( 'filter' ); ?>" title="<?php echo esc_attr( __( 'Enable filtering?', 'codepress-admin-columns' ) ); ?>"></span>
			<?php
		endif;
	}

	/**
	 * @since 3.6
	 */
	public function get_model( $storage_model_key ) {
		return isset( $this->models[ $storage_model_key ] ) ? $this->models[ $storage_model_key ] : false;
	}

	/**
	 * @since 3.7
	 */
	public function clear_timeout( $columns, $storage_model ) {
		if ( $model = $this->get_model( $storage_model->key ) ) {
			$model->clear_timeout();
		}
	}
}

new CAC_Addon_Filtering;