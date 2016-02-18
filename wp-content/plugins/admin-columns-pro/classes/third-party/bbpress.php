<?php

class cpac_bbpress_support {

	private $post_types;

	function __construct() {

		$this->post_types = array( 'topic', 'forum', 'reply' );

		// remove the old filter which disabled bbpress support
		remove_filter( 'cac/post_types', 'cpac_posttypes_remove_bbpress' );

		// init
		add_action( 'cac/loaded', array( $this, 'init' ) );

		// add bbpress menu type
		add_filter( 'cac/menu_types', array( $this, 'add_menu_type' ) );
		add_filter( 'cac/storage_models', array( $this, 'set_menu_type' ) );

		// default names
		add_filter( 'cac/columns/defaults', array( $this, 'default_column_names' ), 10, 3 );
	}

	public function init( $cpac ) {

		// The bail() method from bbpress looks for $current_screen->post_type. This will fake the post_type var.
		if ( class_exists( 'bbPress', false ) && ( $cpac->is_settings_screen() || $cpac->is_doing_ajax() ) ) {
			add_filter( 'manage_topic_posts_columns', array( $this, 'set_topic_screen' ) );
			add_filter( 'manage_forum_posts_columns', array( $this, 'set_forum_screen' ) );
			add_filter( 'manage_reply_posts_columns', array( $this, 'set_reply_screen' ) );
		}
	}

	private function get_columns( $type ) {
		$columns = array();

		// copied from bbpress :(
		switch ( $type ) {
			case 'forum' :
				$columns = array(
					'cb'                    => '<input type="checkbox" />',
					'title'                 => __( 'Forum', 'bbpress' ),
					'bbp_forum_topic_count' => __( 'Topics', 'bbpress' ),
					'bbp_forum_reply_count' => __( 'Replies', 'bbpress' ),
					'author'                => __( 'Creator', 'bbpress' ),
					'bbp_forum_created'     => __( 'Created', 'bbpress' ),
					'bbp_forum_freshness'   => __( 'Freshness', 'bbpress' )
				);
				break;
			case 'topic' :
				$columns = array(
					'cb'                    => '<input type="checkbox" />',
					'title'                 => __( 'Topics', 'bbpress' ),
					'bbp_topic_forum'       => __( 'Forum', 'bbpress' ),
					'bbp_topic_reply_count' => __( 'Replies', 'bbpress' ),
					'bbp_topic_voice_count' => __( 'Voices', 'bbpress' ),
					'bbp_topic_author'      => __( 'Author', 'bbpress' ),
					'bbp_topic_created'     => __( 'Created', 'bbpress' ),
					'bbp_topic_freshness'   => __( 'Freshness', 'bbpress' )
				);
				break;
			case 'reply' :
				$columns = array(
					'cb'                => '<input type="checkbox" />',
					'title'             => __( 'Title', 'bbpress' ),
					'bbp_reply_forum'   => __( 'Forum', 'bbpress' ),
					'bbp_reply_topic'   => __( 'Topic', 'bbpress' ),
					'bbp_reply_author'  => __( 'Author', 'bbpress' ),
					'bbp_reply_created' => __( 'Created', 'bbpress' ),
				);
				break;
		}
		return $columns;
	}

	public function add_menu_type( $menu_types ) {
		$menu_types['bbpress'] = __( 'bbPress', 'codepress-admin-columns' );
		return $menu_types;
	}

	public function set_topic_screen( $headers ) {
		return $this->get_columns( 'topic' );
	}

	public function set_forum_screen( $headers ) {
		return $this->get_columns( 'forum' );
	}

	public function set_reply_screen( $headers ) {
		return $this->get_columns( 'reply' );
	}

	public function set_menu_type( $storage_models ) {
		if ( class_exists( 'bbPress', false ) ) {
			foreach ( $storage_models as $k => $storage_model ) {
				if ( in_array( $storage_model->get_post_type(), $this->post_types ) ) {
					$storage_models[ $k ] = $storage_model->set_menu_type( 'bbpress' );
				}
			}
		}
		return $storage_models;
	}

	public function default_column_names( $column_names, $column, $storage_model ) {
		if ( $columns = $this->get_columns( $storage_model->get_post_type() ) ) {
			$column_names = array_keys( $columns );
		}
		return $column_names;
	}
}

new cpac_bbpress_support;