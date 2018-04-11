<?php

namespace Tainacan\Entities;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Represents entity Log
 */
class Log extends Entity {

	protected
		$title,
		$order,
		$parent,
		$description,
		$blog_id,
		$user_id,
		$log_date,
		$user_name;

	protected static $post_type = 'tainacan-log';
	/**
	 * {@inheritDoc}
	 * @see \Tainacan\Entities\Entity::repository
	 * @var string
	 */
	protected $repository = 'Logs';

	public function __construct( $which = 0 ) {
		parent::__construct( $which );

		if ( is_int( $which ) && $which == 0 ) {
			$this->set_user_id();
			$this->set_blog_id();
		}
	}

	public function __toString() {
		return 'Hello, my title is ' . $this->get_title();
	}

	/**
	 * @return array
	 */
	public function __toArray() {
		$array_log = parent::__toArray();

		$array_log['user_name']     = $this->get_user_name();

		return $array_log;
	}

	/**
	 * Return the Log title
	 *
	 * @return string
	 */
	function get_title() {
		return $this->get_mapped_property( 'title' );
	}

	/**
	 * @return string
	 */
	function get_user_name() {
		return get_the_author_meta( 'display_name', $this->get_user_id() );
	}

	/**
	 * Return the log date
	 *
	 * @return mixed|null
	 */
	function get_log_date() {
		return $this->get_mapped_property( 'log_date' );
	}

	/**
	 * Return the log order type
	 *
	 * @return string
	 */
	function get_order() {
		return $this->get_mapped_property( 'order' );
	}

	/**
	 * Retun the parent ID
	 *
	 * @return integer
	 */
	function get_parent() {
		return $this->get_mapped_property( 'parent' );
	}

	/**
	 * Return the Log description
	 *
	 * @return string
	 */
	function get_description() {
		return $this->get_mapped_property( 'description' );
	}

	/**
	 * Return the ID of blog
	 *
	 * @return integer
	 */
	function get_blog_id() {
		return $this->get_mapped_property( 'blog_id' );
	}

	/**
	 * Return User Id of who make the action
	 *
	 * @return int User Id of logged action
	 */
	function get_user_id() {
		return $this->get_mapped_property( 'user_id' );
	}

	/**
	 * Get value of log entry
	 *
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function get_value() {
		return maybe_unserialize( base64_decode( $this->get_mapped_property( 'value' ) ) );
	}

	/**
	 * Get old value of log entry object
	 *
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function get_old_value() {
		return maybe_unserialize( base64_decode( $this->get_mapped_property( 'old_value' ) ) );
	}

	/**
	 * Set log tittle
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	function set_title( $value ) {
		$this->set_mapped_property( 'title', $value );
	}

	/**
	 * Define the order type
	 *
	 * @param [string] $value
	 *
	 * @return void
	 */
	function set_order( $value ) {
		$this->set_mapped_property( 'order', $value );
	}

	/**
	 * Define the parent ID
	 *
	 * @param [integer] $value
	 *
	 * @return void
	 */
	function set_parent( $value ) {
		$this->set_mapped_property( 'parent', $value );
	}

	/**
	 * Define the Log description
	 *
	 * @param [string] $value
	 *
	 * @return void
	 */
	function set_description( $value ) {
		$this->set_mapped_property( 'description', $value );
	}

	/**
	 * Define the user ID of log entry
	 *
	 * @param [integer] $value
	 *
	 * @return void
	 */
	protected function set_user_id( $value = 0 ) {
		if ( 0 == $value ) {
			$value = get_current_user_id();
		}
		$this->set_mapped_property( 'user_id', $value );
	}

	/**
	 * Define the blog ID of log entry
	 *
	 * @param [integer] $value
	 *
	 * @return void
	 */
	protected function set_blog_id( $value = 0 ) {
		if ( 0 == $value ) {
			$value = get_current_blog_id();
		}
		$this->set_mapped_property( 'blog_id', $value );
	}

	/**
	 * Define the value of log entry
	 *
	 * @param [mixed] $value
	 *
	 * @return void
	 */
	protected function set_value( $value = null ) {
		$this->set_mapped_property( 'value', base64_encode( maybe_serialize( $value ) ) );
	}

	/**
	 * Set old value of log entry
	 *
	 * @param [mixed] $value
	 *
	 * @return void
	 */
	protected function set_old_value( $value = null ) {
		$this->set_mapped_property( 'old_value', base64_encode( maybe_serialize( $value ) ) );
	}

	/**
	 *
	 * @param boolean|string $msn
	 * @param string $desc
	 * @param mixed $new_value
	 * @param mixed $old_value
	 * @param string $status 'publish', 'private' or 'pending'
	 *
	 * @throws \Exception
	 * @return \Tainacan\Entities\Log
	 */
	public static function create( $msn = false, $desc = '', $new_value = null, $old_value = null, $status = 'publish' ) {
		$log = new Log();
		$log->set_title( $msn );
		$log->set_description( $desc );
		$log->set_status( $status );

		if ( ! is_null( $new_value ) ) {
			$log->set_value( $new_value );

			if ( ! is_null( $old_value ) ) {
				$log->set_old_value( $old_value );
			}
		} elseif ( $msn === false ) {
			throw new \Exception( 'msn or new_value is need to log' );
		}

		$Tainacan_Logs = \Tainacan\Repositories\Logs::get_instance();

		if ( $log->validate() ) {
			return $Tainacan_Logs->insert( $log );
		} else {
			throw new \Exception( 'Invalid log' );
		}

	}

	/**
	 * {@inheritDoc}
	 * @see \Tainacan\Repositories\Logs::approve
	 */
	public function approve() {
		$repository = $this->get_repository();

		return $repository->approve( $this );
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Tainacan\Entities\Entity::diff()
	 */
	public function diff($which = 0) {
		$log = $this;

		if($which != 0) {
			$log = new self($which);
		}

		$value = $log->get_value();
		$old = $log->get_old_value();
		return $value->diff($old);
	}
}