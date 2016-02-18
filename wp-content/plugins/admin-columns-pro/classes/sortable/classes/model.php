<?php

/**
 * Addon class
 *
 * @since 1.0
 *
 */
abstract class CAC_Sortable_Model {

	protected $storage_model;
	protected $default_orderby;
	private $show_all_results;

	abstract function get_sortables();

	abstract function get_items( $args );

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct( $storage_model ) {

		$this->storage_model = $storage_model;

		$this->set_default_orderby();

		// enable sorting per column
		add_action( "cac/columns/registered/default/storage_key={$this->storage_model->key}", array( $this, 'enable_sorting' ) );
		add_action( "cac/columns/registered/custom/storage_key={$this->storage_model->key}", array( $this, 'enable_sorting' ) );

		// handle reset request
		add_action( 'admin_init', array( $this, 'handle_reset' ) );

		// Add reset button to admin settings
		add_action( 'cac/settings/form_actions', array( $this, 'add_general_reset_button' ) );
	}

	/**
	 * @since 3.7
	 */
	public function set_default_orderby() {
		$this->default_orderby = '';
	}

	/**
	 * @since 3.7
	 */
	public function get_show_all_results() {
		if ( null === $this->show_all_results ) {
			$this->show_all_results = $this->storage_model->get_general_option( 'show_all_results' );
		}

		return $this->show_all_results;
	}

	/**
	 * Get sorting preference
	 *
	 * @since 1.0
	 */
	private function get_sorting_preference() {
		$options = get_user_meta( get_current_user_id(), 'cpac_sorting_preference', true );

		if ( empty( $options ) || ! is_array( $options ) || empty( $options[ $this->storage_model->key ] ) ) {
			return false;
		}

		// when it's a WP default orderby we can skip as a preference
		if ( $this->default_orderby == $options[ $this->storage_model->key ]['orderby'] ) {
			return false;
		}

		return $options[ $this->storage_model->key ];
	}

	private function update_sorting_preference( $orderby, $order = '' ) {
		$options = get_user_meta( get_current_user_id(), 'cpac_sorting_preference', true );

		// in some rare case we can have a broken serialized string
		if ( is_string( $options ) ) {
			$options = array();
		}

		$options[ $this->storage_model->key ] = array(
			'orderby' => $orderby,
			'order'   => $order ? $order : 'ASC'
		);

		update_user_meta( get_current_user_id(), 'cpac_sorting_preference', $options );
	}

	/**
	 * Add general reset button
	 *
	 * @since 1.0
	 */
	public function add_general_reset_button( $storage_model ) {

		if ( $storage_model->key !== $this->storage_model->key ) {
			return;
		}

		if ( ! ( $preference = $this->get_sorting_preference() ) ) {
			return;
		}

		$sortby = isset( $preference['orderby'] ) ? $preference['orderby'] : '';

		$url = add_query_arg( array(
			'_cpac_nonce' => wp_create_nonce( 'restore-sorting' ),
			'cpac_key'    => $storage_model->key,
			'cpac_action' => 'restore_sorting_type'
		), admin_url( 'options-general.php?page=codepress-admin-columns' ) );

		$sortby_label = $sortby ? ' <em style="color:#808080">' . sprintf( __( ' by %s', 'codepress-admin-columns' ), $sortby ) . '</em>' : '';

		echo "
			<div class='form-reset'>
				<a class='reset-column-type' href='{$url}'>" . __( 'Reset sorting preference', 'codepress-admin-columns' ) . "</a>" . $sortby_label . "
			</div>";
	}

	/**
	 * Add reset button
	 *
	 * Which resets the sorting to it's default.
	 *
	 * @since 1.0
	 */
	public function add_reset_button() {
		global $post_type_object, $pagenow;

		if (
			// corrrect page?
			( $this->storage_model->page . '.php' !== $pagenow ) ||
			// posttype?
			( isset( $post_type_object->name ) && $post_type_object->name !== $this->storage_model->key )
		) {
			return false;
		}

		if ( ! ( $preference = $this->get_sorting_preference() ) ) {
			return;
		}

		$sortby = isset( $preference['orderby'] ) ? $preference['orderby'] : '';

		?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				if (jQuery('body').data('cpac_init_reset') == true) {
					return true;
				}
				jQuery('.tablenav.top .actions:last').append('<a title="<?php _e( 'Reset sorting', 'codepress-admin-columns' ); echo ' ' . esc_attr( $sortby ); ?>" href="javascript:;" id="cpac-reset-sorting" class="cpac-edit add-new-h2"><?php _e( 'Reset sorting', 'codepress-admin-columns' ); ?></a>');
				jQuery('#cpac-reset-sorting').click(function () {
					jQuery('#post-query-submit').trigger('mousedown'); // reset bulk actions
					jQuery('<input>').attr({
						type: 'hidden',
						name: 'reset-sorting',
						value: '<?php echo $this->storage_model->key; ?>'
					}).appendTo(this);
					jQuery(this).closest('form').submit();
				});
				jQuery('body').data('cpac_init_reset', true);
			});
		</script>
		<?php
	}

	/**
	 * Do sorting reset
	 *
	 * @since 1.0.3
	 *
	 * @param string Storage_model Key
	 */
	private function do_reset( $storage_model_key ) {

		$options = get_user_meta( get_current_user_id(), 'cpac_sorting_preference', true );

		if ( ! isset( $options[ $storage_model_key ] ) ) {
			return false;
		}

		unset( $options[ $storage_model_key ] );

		return update_user_meta( get_current_user_id(), 'cpac_sorting_preference', $options );
	}

	/**
	 * Handle reset request
	 *
	 * @since 1.0
	 */
	public function handle_reset() {
		global $pagenow;

		// On Admin settings page
		if ( isset( $_GET['cpac_action'] ) && 'restore_sorting_type' == $_GET['cpac_action'] && ! empty( $_GET['cpac_key'] ) && 'options-general.php' == $pagenow && ! empty( $_GET['page'] ) && 'codepress-admin-columns' == $_GET['page'] ) {

			// security check
			if ( wp_verify_nonce( $_GET['_cpac_nonce'], 'restore-sorting' ) ) {
				$result = $this->do_reset( $_GET['cpac_key'] );

				if ( $result ) {
					cpac_admin_message( "<strong>{$this->storage_model->label}</strong> " . __( 'sorting preference succesfully reset.', 'codepress-admin-columns' ), 'updated' );
				}
			}
		}

		// On Columns page
		if ( $this->storage_model->page . '.php' == $pagenow && ! empty( $_REQUEST['reset-sorting'] ) && $_REQUEST['reset-sorting'] == $this->storage_model->key ) {

			// do a reset
			$this->do_reset( $_REQUEST['reset-sorting'] );

			// redirect back to admin
			$admin_url = trailingslashit( admin_url() ) . $this->storage_model->page . '.php';

			// for posts we need to add the type to the admin url
			if ( 'post' == $this->storage_model->type ) {
				$admin_url = $admin_url . '?post_type=' . $this->storage_model->key;
			}

			wp_safe_redirect( $admin_url );
			exit;
		}
	}

	/**
	 * Enable sorting
	 *
	 * @since 1.0
	 */
	public function enable_sorting( $columns ) {
		$sortables = $this->get_sortables();
		foreach ( $columns as $column ) {
			if ( ! in_array( $column->properties->type, $sortables ) ) {
				continue;
			}

			$column->set_properties( 'is_sortable', true );
			$column->set_options( 'sort', 'on' );
		}
	}

	/**
	 * Get column by orderby
	 *
	 * Returns column object based on which column heading is sorted.
	 *
	 * @since 1.0
	 *
	 * @param string $orderby
	 * @param string $type
	 *
	 * @return array Column
	 */
	public function get_column_by_orderby( $orderby ) {

		$column = false;

		if ( $columns = $this->storage_model->columns ) {
			foreach ( $columns as $_column ) {
				if ( $orderby == $_column->get_sanitized_label() ) {
					$column = $_column;
				}
			}
		}

		return apply_filters( 'cac/column/by_orderby', $column, $orderby, $this->storage_model->key );
	}

	/**
	 * Apply sorting preference
	 *
	 * @since 1.0
	 *
	 * @param array &$vars
	 * @param string $type
	 */
	public function apply_sorting_preference( $vars ) {

		// Apply the stored sorting preference when user hasn't sorted.
		if ( empty( $_GET['orderby'] ) && ( $preference = $this->get_sorting_preference() ) ) {
			$vars['orderby'] = $preference['orderby'];
			$vars['order'] = $preference['order'];
		}

		// Update preference
		if ( ! empty( $vars['orderby'] ) ) {
			$this->update_sorting_preference( $vars['orderby'], isset( $vars['order'] ) ? $vars['order'] : 'ASC' );
		}

		return $vars;
	}

	/**
	 * Prepare the value for being by sorting
	 *
	 * Removes tags and only get the first 20 chars and force lowercase.
	 *
	 * @since 1.0
	 *
	 * @param string $string
	 *
	 * @return string String
	 */
	protected function prepare_sort_string_value( $string ) {
		if ( is_array( $string ) && ( is_string( $string[0] ) || is_numeric( $string[0] ) ) ) {
			$string = $string[0];
		}

		return $string ? strtolower( substr( trim( strip_tags( $string ) ), 0, 20 ) ) : false;
	}

	/**
	 * Set post__in for use in WP_Query
	 *
	 * This will order the ID's asc or desc and set the appropriate filters.
	 *
	 * @since 1.0
	 *
	 * @param array &$vars
	 * @param array $sortposts
	 * @param const $sort_flags
	 *
	 * @return array Posts Variables
	 */
	protected function get_vars_post__in( $vars, $unsorted, $sort_flag = SORT_REGULAR ) {

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @since 3.1
		 *
		 * @param int $sort_flag Used to modify the behavior of the asort() method.
		 * @param object $this CAC_Sortable_Model
		 */
		$sort_flag = apply_filters( 'cac/addon/sortable/sort_flag', $sort_flag, $this );

		if ( 'asc' == $vars['order'] ) {
			asort( $unsorted, $sort_flag );
		} else {
			arsort( $unsorted, $sort_flag );
		}

		$vars['orderby'] = 'post__in';
		$vars['post__in'] = array_keys( $unsorted );

		return $vars;
	}

	/**
	 * Get post ID's
	 *
	 * @since 1.0.7
	 *
	 * @param array $args
	 *
	 * @return array Posts
	 */
	public function get_posts( $args = array() ) {
		$defaults = array(
			'posts_per_page' => - 1,
			'post_status'    => array( 'any', 'trash' ),
			'post_type'      => $this->storage_model->get_post_type(),
			'fields'         => 'ids',
			'no_found_rows'  => 1, // lowers our carbon footprint
		);

		$post_ids = (array) get_posts( array_merge( $defaults, $args ) );

		return $post_ids;
	}

	/**
	 * Get posts sorted by taxonomy
	 *
	 * This will post ID's by the first term in the taxonomy
	 *
	 * @since 1.0.7
	 *
	 * @param string $post_type
	 * @param string $taxonomy
	 *
	 * @return array Posts
	 */
	protected function get_posts_sorted_by_taxonomy( $taxonomy = 'category' ) {
		$args = array(
			'suppress_filters' => false,
			'_acp_taxonomy'    => $taxonomy,
		);

		add_filter( 'posts_clauses', array( $this, 'get_posts_sorted_by_taxonomy_args' ), 10, 2 );
		$posts = $this->get_posts( $args );
		remove_filter( 'posts_clauses', array( $this, 'get_posts_sorted_by_taxonomy_args' ), 10, 2 );

		return array_flip( $posts );
	}

	/**
	 * Setup clauses to sort by taxonomies
	 *
	 * @since 3.4
	 * @return array
	 */
	public function get_posts_sorted_by_taxonomy_args( $clauses, $query ) {
		global $wpdb;

		$clauses['join'] .= "
            LEFT OUTER JOIN {$wpdb->term_relationships}
                ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id
            LEFT OUTER JOIN {$wpdb->term_taxonomy}
                USING (term_taxonomy_id)
            LEFT OUTER JOIN {$wpdb->terms}
                USING (term_id)
        ";

		$conditions[] = $wpdb->prepare( 'taxonomy = %s', $query->get( '_acp_taxonomy' ) );
		$conditions[] = $this->get_show_all_results() ? ' OR taxonomy IS NULL' : '';

		$clauses['where'] .= vsprintf( ' AND (%s%s)', $conditions );
		$clauses['orderby'] = "{$wpdb->terms}.name " . $query->get( 'order' );

		return $clauses;
	}

	/**
	 * Add sortable headings
	 *
	 * @since 1.0
	 *
	 * @param array $columns
	 *
	 * @return array Column name | Sanitized Label
	 */
	public function add_sortable_headings( $columns ) {

		// get columns from storage model.
		// columns that are active and have enabled sort will be added to the sortable headings.
		if ( $_columns = $this->storage_model->columns ) {

			foreach ( $_columns as $column ) {

				if ( $column->properties->is_sortable ) {

					if ( 'on' == $column->options->sort ) {
						$columns[ $column->properties->name ] = $column->get_sanitized_label();
					}

					if ( 'off' == $column->options->sort ) {
						unset( $columns[ $column->properties->name ] );
					}
				}
			}
		}

		return $columns;
	}

	/**
	 * @since 3.7
	 *
	 * @param $column
	 * @param array $item_args
	 *
	 * @return array|bool
	 */
	protected function get_meta_items_for_sorting( $column, $item_args = array() ) {

		$items = array();
		$show_all_results = $this->get_show_all_results();

		switch ( $column->options->field_type ) {

			case 'title_by_id':
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_items( $item_args ) as $id ) {

					// sort by the actual post_title instead of ID
					$meta = $column->get_meta_by_id( $id );
					$title_ids = $column->get_ids_from_meta( $meta );
					$title = isset( $title_ids[0] ) ? $column->get_post_title( $title_ids[0] ) : '';

					if ( $title || $show_all_results ) {
						$items[ $id ] = $title;
					}
				}
				break;
			case 'checkmark':
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_items( $item_args ) as $id ) {
					$value = $column->get_raw_value( $id );
					if ( $value || $show_all_results ) {
						$_users[ $id ] = $value ? '1' : '0';
					}
				}
				break;
			case 'count':
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_items( $item_args ) as $id ) {
					$count = $column->get_raw_value( $id, false );
					if ( $count || $show_all_results ) {
						$items[ $id ] = count( $count );
					}
				}
				break;
			case 'date':
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_items( $item_args ) as $id ) {
					$raw = $column->get_raw_value( $id );
					$timestamp = $column->get_timestamp( $raw );
					if ( $timestamp || $show_all_results ) {
						$items[ $id ] = $timestamp;
					}
				}
				break;
			case 'term_by_id':
				$sort_flag = SORT_REGULAR;
				break;
			case 'numeric' :
			case 'library_id' :
			case 'count' :
				if ( $show_all_results ) {
					$sort_flag = SORT_NUMERIC;
				}
				break;

			default:
				if ( $show_all_results ) {
					$sort_flag = SORT_REGULAR;
				}
		}

		if ( ! isset( $sort_flag ) ) {
			return false;
		}

		return array(
			'items'     => $items,
			'sort_flag' => $sort_flag
		);
	}
}