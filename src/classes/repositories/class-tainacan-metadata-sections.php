<?php

namespace Tainacan\Repositories;

use Tainacan\Entities;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

use \Respect\Validation\Validator as v;

/**
 * Class Metadata
 */
class Metadata_Sections extends Repository {

	public $entities_type = '\Tainacan\Entities\Metadata_Section';
	private static $instance = null;

	protected function __construct() {
		parent::__construct();
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * {@inheritDoc}
	 * @see \Tainacan\Repositories\Repository::get_map()
	 */
	protected function _get_map() {
		return apply_filters( 'tainacan-get-map-' . $this->get_name(), [
			'name'                  => [
				'map'         => 'post_title',
				'title'       => __( 'Name', 'tainacan' ),
				'type'        => 'string',
				'description' => __( 'Name of the metadata section', 'tainacan' ),
				'on_error'    => __( 'The name should be a text value and not empty', 'tainacan' ),
				'validation'  => v::stringType()->notEmpty(),
			],
			'slug'                  => [
				'map'         => 'post_name',
				'title'       => __( 'Slug', 'tainacan' ),
				'type'        => 'string',
				'description' => __( 'A unique and sanitized string representation of the metadata sction', 'tainacan' ),
			],
			'status'                     => [
				'map'         => 'post_status',
				'title'       => __( 'Status', 'tainacan' ),
				'type'        => 'string',
				'default'     => 'publish',
				'description' => __( 'Status', 'tainacan' )
			],
			'description'           => [
				'map'         => 'post_content',
				'title'       => __( 'Description', 'tainacan' ),
				'type'        => 'string',
				'description' => __( 'The metadata section description.', 'tainacan' ),
				'default'     => '',
			],
			'description_bellow_name' => [
				'map'         => 'meta',
				'title'       => __( 'Description bellow name', 'tainacan' ),
				'type'        => 'string',
				'description' => __( 'Whether the section metadata description should be displayed bellow the name instead of inside a tooltip.', 'tainacan' ),
				'on_error'    => __( 'Please set the "Description bellow name" value as "yes" or "no"', 'tainacan' ),
				'validation'  => v::stringType()->in( [ 'yes', 'no' ] ), // yes or no
				'default'     => 'no'
			],
			'collection_id'         => [
				'map'         => 'meta',
				'title'       => __( 'Collection', 'tainacan' ),
				'type'        => ['integer', 'string'],
				'description' => __( 'The collection ID', 'tainacan' ),
			]
		] );
	}

	/**
	 * Get the labels for the custom post type of this repository
	 *
	 * @return array Labels in the format expected by register_post_type()
	 */
	public function get_cpt_labels() {
		return array(
			'name'               => __( 'Metadata Sections', 'tainacan' ),
			'singular_name'      => __( 'Metadata Section', 'tainacan' ),
			'add_new'            => __( 'Add new', 'tainacan' ),
			'add_new_item'       => __( 'Add new Metadata Section', 'tainacan' ),
			'edit_item'          => __( 'Edit Metadata Section', 'tainacan' ),
			'new_item'           => __( 'New Metadata Section', 'tainacan' ),
			'view_item'          => __( 'View Metadata Section', 'tainacan' ),
			'search_items'       => __( 'Search Metadata Section', 'tainacan' ),
			'not_found'          => __( 'No Metadata Section found ', 'tainacan' ),
			'not_found_in_trash' => __( 'No Metadata Section found in trash', 'tainacan' ),
			'parent_item_colon'  => __( 'Parent Metadata Section:', 'tainacan' ),
			'menu_name'          => __( 'Metadata Section', 'tainacan' )
		);
	}

	public function register_post_type() {
		$labels = $this->get_cpt_labels();
		$args   = array(
			'labels'              => $labels,
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => tnc_enable_dev_wp_interface(),
			'show_in_menu'        => tnc_enable_dev_wp_interface(),
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'map_meta_cap'        => true,
			'show_in_nav_menus'   => false,
			'capabilities'        => (array) $this->get_capabilities(),
			'supports'            => [
				'title',
				'editor',
				'page-attributes'
			]
		);
		register_post_type( Entities\Metadata_Section::get_post_type(), $args );
	}

	/**
	 * Get the default metadata section of the collection
	 * 
	 * @param \Tainacan\Entities\Collection|int the collection of the metadata section
	 *
	 * @return \Tainacan\Entities\Metadata_Section|false return de the default metadata section or false otherwise.
	 */
	public function get_default_section ($collection) {
		if($collection instanceof Entities\Collection) {
			$collection_id = $collection->get_id();
		} else if (is_int($collection)) {
			$collection_id = $collection;
			$collection = \tainacan_collections()->fetch($collection, 'OBJECT');
		} else {
			return false;
		}
		$name = __('Metadata', 'tainacan');
		$description = __('Metadata section', 'tainacan');
		$description_bellow_name = 'no';
		$default_metadata_section_properties = $collection->get_default_metadata_section_properties();
		if( !empty($default_metadata_section_properties) ) {
			$name =  isset($default_metadata_section_properties['name']) ? $default_metadata_section_properties['name'] : $name;
			$description = isset($default_metadata_section_properties['description']) ? $default_metadata_section_properties['description'] : $description;
			$description_bellow_name = isset($default_metadata_section_properties['description_bellow_name']) ? $default_metadata_section_properties['description_bellow_name'] : $description_bellow_name;
		}
		$defaul_section = new Entities\Metadata_Section();
		$defaul_section->set_slug(\Tainacan\Entities\Metadata_Section::$default_section_slug);
		$defaul_section->set_name($name);
		$defaul_section->set_description($description);
		$defaul_section->set_description_bellow_name($description_bellow_name);
		$defaul_section->set_collection_id($collection_id);
		return $defaul_section;
	}

	/**
	 * fetch metadata section based on ID or WP_Query args
	 *
	 * metadata section are stored as posts. Check WP_Query docs
	 * to learn all args accepted in the $args parameter (@see https://developer.wordpress.org/reference/classes/wp_query/)
	 * You can also use a mapped property, such as name and description, as an argument and it will be mapped to the
	 * appropriate WP_Query argument
	 *
	 * If a number is passed to $args, it will return a \Tainacan\Entities\Metadata_Section object. But if the post is not found or
	 * does not match the entity post type, it will return an empty array
	 *
	 * @param array $args WP_Query args || int $args the metadata section id
	 * @param string $output The desired output format (@see \Tainacan\Repositories\Repository::fetch_output() for possible values)
	 *
	 * @return Entities\Metadata_Section|\WP_Query|Array an instance of wp query OR array of entities;
	 * @throws \Exception
	 */
	public function fetch( $args, $output = null ) {

		if ( is_numeric( $args ) ) {
			$existing_post = get_post( $args );
			if ( $existing_post instanceof \WP_Post ) {
				try {
					return new Entities\Metadata_Section( $existing_post );
				} catch (\Exception $e) {
					return [];
				}
			} else {
				return [];
			}
		} elseif ( is_array( $args ) ) {
			$args = array_merge( [
				'posts_per_page' => - 1,
			], $args );

			$args = $this->parse_fetch_args( $args );
			$args['post_type'] = Entities\Metadata_Section::get_post_type();
			$args = apply_filters( 'tainacan-fetch-args', $args, 'metadata-section' );
			
			$wp_query = new \WP_Query( $args );

			return $this->fetch_output( $wp_query, $output );
		}
	}

	/**
	 * fetch metadata sections IDs based on WP_Query args
	 *
	 * to learn all args accepted in the $args parameter (@see https://developer.wordpress.org/reference/classes/wp_query/)
	 * You can also use a mapped property, such as name and description, as an argument and it will be mapped to the
	 * appropriate WP_Query argument
	 *
	 * @param array $args WP_Query args || int $args the item id
	 *
	 * @return Array array of IDs;
	 * @throws \Exception
	 */
	public function fetch_ids( $args = [] ) {

		$args['fields'] = 'ids';

		return $this->fetch( $args )->get_posts();
	}

	/**
	 * fetch metadata section by collection
	 *
	 * @param Entities\Collection $collection
	 * @param array $args WP_Query args
	 *
	 * @return array Entities\Metadata_Section
	 * @throws \Exception
	 */
	public function fetch_by_collection( Entities\Collection $collection, $args = [] ) {
		$collection_id = $collection->get_id();

		//get parent collections
		$parents = get_post_ancestors( $collection_id );

		//insert the actual collection
		if ( is_numeric($collection_id) ) {
			$parents[] = $collection_id;
		}

		$results = [];

		$args = array_merge( [
			'parent' => 0
		], $args );
		
		$original_meta_q = isset( $args['meta_query'] ) ? $args['meta_query'] : [];

		/**
		 * Since we introduced roles & capabalities management, we cannot rely
		 * on WordPress behavior when handling default post status values.
		 * WordPress checks if the current user can read_priva_posts, but this is
		 * not enough for us. We have to handle this ourselves to mimic WordPress behavior
		 * considering how tainacan manages metadata capabilities
		 */
		if ( ! isset($args['post_status']) ) {

			foreach ( $parents as $parent_id ) {

				// Add public states.
				$statuses = get_post_stati( array( 'public' => true ) );
				$read_private_cap = 'tnc_col_' . $parent_id . '_read_private_metadata_section';
				if ( current_user_can($read_private_cap) ) {
					$statuses = array_merge( $statuses, get_post_stati( array( 'private' => true ) ) );
				}

				$args['post_status'] = $statuses;

				$meta_query = array(
					'key'     => 'collection_id',
					'value'   => $parent_id,
				);

				$args['meta_query'] = $original_meta_q;
				$args['meta_query'][] = $meta_query;

				$results = array_merge($results, $this->fetch( $args, 'OBJECT' ));
			}

		} else {
			$meta_query = array(
				'key'     => 'collection_id',
				'value'   => $parents,
				'compare' => 'IN',
			);
			$args['meta_query'] = $original_meta_q;
			$args['meta_query'][] = $meta_query;
			$results = $this->fetch( $args, 'OBJECT' );
		}
		$results[] = $this->get_default_section($collection->get_id());

		return $this->order_result(
			$results,
			$collection,
			isset( $args['include_disabled'] ) ? $args['include_disabled'] : false
		);
	}

	/**
	 * @param \Tainacan\Entities\Metadata_Section $metadata_section
	 *
	 * @return \Tainacan\Entities\Metadata_Section
	 * {@inheritDoc}
	 * @see \Tainacan\Repositories\Repository::insert()
	 */
	public function insert( $metadata_section ) {
		$new_metadata_section = parent::insert( $metadata_section );
		return $new_metadata_section;
	}

	/**
	 * @param \Tainacan\Entities\Metadata_Section $object
	 * @param $new_values
	 *
	 * @return mixed|string|Entities\Entity
	 * @throws \Exception
	 */
	public function update( $object, $new_values = null ) {
		if($object->get_id() == \Tainacan\Entities\Metadata_Section::$default_section_slug) {
			$collection = $object->get_collection();
			if($collection instanceof \Tainacan\Entities\Collection) {
				$properties = array(
					'name' => $object->get_name(),
					'description' => $object->get_description(),
					'description_bellow_name' => $object->get_description_bellow_name()
				);
				$collection->set_default_metadata_section_properties($properties);
				if($collection->validate()) {
					\tainacan_collections()->update($collection);
					return $object;
				}
			}
			return false;
		}
		return $this->insert( $object );
	}

	public function add_metadata($metadata_section_id, $metadata_list) {
		$metadata_section = $this->fetch($metadata_section_id, 'OBJECT');
		if ($metadata_section) {
			foreach($metadata_list as $metadata_id) {
				//update_post_meta($metadata_id, 'metadata_section_id', $metadata_section_id);
				add_post_meta($metadata_id, 'metadata_section_id', $metadata_section_id);
			}
			return $metadata_section;
		}
		return false;
	}

	public function delete_metadata($metadata_section_id, $metadata_list) {
		$metadata_section = $this->fetch($metadata_section_id, 'OBJECT');
		if ($metadata_section) {
			foreach($metadata_list as $metadata_id) {
				delete_post_meta($metadata_id, 'metadata_section_id', $metadata_section_id);
			}
			return $metadata_section;
		}
		return false;
	}

	public function get_metadata_object_list($metadata_section_id, $args = []) {
		$metadata_section = $this->fetch($metadata_section_id);
		if ($metadata_section) {
			$metadata_repository = \Tainacan\Repositories\Metadata::get_instance();
			$metadata_list = $metadata_repository->fetch_by_metadata_section($metadata_section, $args);
			return $metadata_list;
		}
		return false;
	}

	public function get_default_section_metadata_object_list (Entities\Collection $collection, $args = []) {
		$metadata_sections_ids = $this->fetch_ids();
		$args = array_merge(
			$args,
			array(
				'meta_query' => array(
					array(
						'relation' => 'OR',
						array(
							'key' => 'metadata_section_id',
							'value' => \Tainacan\Entities\Metadata_Section::$default_section_slug,
							'compare' => '='
						),
						array(
							array(
								'key' => 'metadata_section_id',
								'compare' => 'NOT EXISTS'
							)
						),
						array(
							'key' => 'metadata_section_id',
							'value' => $metadata_sections_ids,
							'compare' => 'NOT IN'
						),
					)
				)
			)
		);
		$metadata_repository = \Tainacan\Repositories\Metadata::get_instance();
		$metadata_list = $metadata_repository->fetch_by_collection($collection, $args);
		return $metadata_list;
	}

	/**
	 * @inheritDoc
	 */
	public function delete( Entities\Entity $entity, $permanent = true ) {
		//test if not exist a metadata using this section
		if (  !empty( $this->get_metadata_object_list($entity->get_id() ) )  ) {
			throw new \Exception( 'The metadata section must not contain metadata before deleted' );
		}
		return parent::delete($entity, $permanent);
	}

	public function order_result( $result, Entities\Collection $collection, $include_disabled = false ) {
		$order = $collection->get_metadata_section_order();

		if ( $order ) {
			$order = ( is_array( $order ) ) ? $order : unserialize( $order );

			if ( is_array( $result ) ) {
				$result_ordinate = [];
				$not_ordinate    = [];

				foreach ( $result as $item ) {
					$id = $item->get_id();
					$index = array_search( $id, array_column( $order, 'id' ) );

					if ( $index !== false ) {

						// skipping metadata disabled if the arg is set
						if ( ! $include_disabled && isset( $order[ $index ]['enabled'] ) && ! $order[ $index ]['enabled'] ) {
							continue;
						}

						$enable = ( isset( $order[ $index ]['enabled'] ) ) ? $order[ $index ]['enabled'] : true;
						$item->set_enabled_for_collection( $enable );

						$result_ordinate[ $index ] = $item;
					} else {
						$not_ordinate[] = $item;
					}
				}

				ksort( $result_ordinate );
				$result_ordinate = array_merge( $result_ordinate, $not_ordinate );
				return $result_ordinate;
			}

		}
		return $result;
	}

	/**
	 * Check if $user can read the entity
	 *
	 * @param Entities\Metadata_Section|Entities\Entity $entity
	 * @param int|\WP_User|null $user default is null for the current user
	 *
	 * @return boolean
	 * @throws \Exception
	 */
	public function can_read( Entities\Entity $entity, $user = null ) {
		if ( is_null($entity) )
			return false;

		if ($entity instanceof Entities\Metadata_Section && $entity->get_id() == Entities\Metadata_Section::$default_section_slug ) {
			$collection = $entity->get_collection();
			if($collection instanceof Entities\Collection) {
				return $collection->can_read();
			}
			return false;
		}

		return parent::can_read($entity, $user);
	}
}
