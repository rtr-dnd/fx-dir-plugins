<?php

use AlgoliaSearch\AlgoliaException;

class Algolia_Usermeta_Changes_Watcher implements Algolia_Changes_Watcher {

	/**
	 * @var Algolia_Index
	 */
	private $index;

	/**
	 * @param Algolia_Index $index
	 */
	public function __construct( Algolia_Index $index ) {
		$this->index = $index;
	}

	protected function get_usermetas_by_userid( int $user_id ) {
		global $wpdb;
		$wpdb->show_errors = TRUE;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->usermeta} WHERE user_id = {$user_id}");
		if(! $results){
			$wpdb->print_error();
			// Or you can choose to show the last tried query.
			echo $wpdb->last_query;
			error_log($wpdb->last_query);
		}
		return $results;
	}

	public function watch() {
		// Fires immediately after an existing user is updated.
		add_action( 'profile_update', array( $this, 'sync_item' ) );

		// Fires immediately after a new user is registered.
		add_action( 'user_register', array( $this, 'sync_item' ) );

		// Fires immediately before a user is deleted.
		add_action( 'delete_user', array( $this, 'delete_item' ) );
	}

	/**
	 * @param $umeta_id
	 */
	public function sync_item( $user_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$usermetas = $this->get_usermetas_by_userid( $user_id );

		foreach($usermetas as $usermeta) {
			if ( ! $usermeta || ! $this->index->supports( $usermeta ) ) {
				continue;
			}
			try {
				$this->index->sync( $usermeta );
			} catch ( AlgoliaException $exception ) {
				error_log( $exception->getMessage() );
			}
		}
	}

	/**
	 * @param int $umeta_id
	 */
	public function delete_item( $user_id ) {
		$usermetas = $this->get_usermetas_by_userid( $user_id );
		foreach($usermetas as $usermeta) {
			if ( ! $usermeta || ! $this->index->supports( $usermeta ) ) {
				continue;
			}

			try {
				$this->index->delete_item( $usermeta );
			} catch ( AlgoliaException $exception ) {
				error_log( $exception->getMessage() );
			}
		}
	}
}
