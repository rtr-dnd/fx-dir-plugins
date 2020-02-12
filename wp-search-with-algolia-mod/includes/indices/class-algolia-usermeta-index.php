<?php

final class Algolia_Usermeta_Index extends Algolia_Index {

	/**
	 * @var string
	 */
	protected $contains_only = 'usermeta';

	/**
	 * @return string The name displayed in the admin UI.
	 */
	public function get_admin_name() {
		return __( 'Usermeta' );
	}

	/**
	 * @return array all usermeta.
	 */
	protected function get_all_usermeta( $args ) {
		// $args = array(
		// 	'order'   => 'ASC',
		// 	'orderby' => 'umeta_id',
		// 	'offset'  => $offset,
		// 	'number'  => $batch_size,
		// );

		global $wpdb;
		$wpdb->show_errors = TRUE;
		$sql = "SELECT * FROM $wpdb->usermeta ORDER BY {$args['orderby']} {$args['order']} LIMIT {$args['offset']}, {$args['number']};";

		$results = $wpdb->get_results($sql);
		if(! $results){
			$wpdb->print_error();
			// Or you can choose to show the last tried query.
			echo $wpdb->last_query;
			error_log($wpdb->last_query);
			error_log("sql: {$sql}");
		}

		return $results;
	}

	/**
	 * @return int all usermeta count.
	 */
	protected function count_all_usermeta() {
		global $wpdb;
		$wpdb->show_errors = TRUE;
		$result = $wpdb->get_row( "SELECT COUNT(*) as c FROM $wpdb->usermeta");
		if(! $result){
			$wpdb->print_error();
			// Or you can choose to show the last tried query.
			echo $wpdb->last_query;
			error_log($wpdb->last_query);
		}
		return (int) $result->c;
	}

	/**
	 * @param $user_id
	 *
	 * @return string url for user profile page
	 */
	protected function get_profile_url($username){
		$modified_user_name = str_replace(" ", "+", $username);
		$result = home_url('/user/') . $modified_user_name;
		return $result;
	}

	/**
	 * @param $user_id
	 *
	 * @return string url for user profile photo
	 */
	protected function get_profile_thumbnail_image($user_id){


		global $wpdb;
		$wpdb->show_errors = TRUE;
		$result = $wpdb->get_row( "SELECT meta_value AS p FROM wp_usermeta WHERE meta_key = 'profile_photo' AND user_id = ".$user_id);
		if(! $result){
			// return default avatar
			return content_url() . '/plugins/ultimate-member/assets/img/default_avatar.jpg';
		}

		$filepath = pathinfo($result->p);

		return content_url()  . '/uploads/ultimatemember/' . $user_id . '/' . $filepath['filename'] . '-80x80.' . $filepath['extension'];
	}

	/**
	 * @param $item
	 *
	 * @return bool
	 */
	protected function should_index( $item ) {
		return true;
	}

	/**
	 * @param $item
	 *
	 * @return array
	 */
	protected function get_records( $item ) {
		$record                 = array();
		$record['objectID']     = $item->umeta_id;
		$record['umeta_id']     = $item->umeta_id;
		$record['user_id']      = $item->user_id;
		$record['meta_key']      = $item->meta_key;
		$record['user_name']    = get_userdata($item->user_id)->user_login;
		$record['display_name']    = get_userdata($item->user_id)->display_name;
		$record['posts_url']    = $this->get_profile_url($record['user_name']);
		$record['meta_value']    = strip_tags(nl2br($item->meta_value));
		$record['images']['thumbnail']['url'] = $this->get_profile_thumbnail_image($item->user_id);



		$record = (array) apply_filters( 'algolia_usermeta_record', $record, $item );

		return array( $record );
	}

	/**
	 * @return int
	 */
	protected function get_re_index_items_count() {
		$usermeta_count = $this->count_all_usermeta();
		return (int) $usermeta_count;
	}

	/**
	 * @return array
	 */

	 # TODO
	protected function get_settings() {
		$settings = array(
			'attributesToIndex' => array(
				'unordered(meta_value)',
			),
			'attributesForFaceting' => array(
				'meta_key',
			),
			'customRanking'     => array(
				'desc(user_id)',
			),
		);

		return (array) apply_filters( 'algolia_usermeta_index_settings', $settings );
	}

	/**
	 * @return array
	 */
	protected function get_synonyms() {
		return (array) apply_filters( 'algolia_usermeta_index_synonyms', array() );
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return 'usermeta';
	}


	/**
	 * @param int $page
	 * @param int $batch_size
	 *
	 * @return array
	 */
	protected function get_items( $page, $batch_size ) {
		$offset = $batch_size * ( $page - 1 );

		$args = array(
			'order'   => 'ASC',
			'orderby' => 'umeta_id',
			'offset'  => $offset,
			'number'  => $batch_size,
		);

		// We use prior to 4.5 syntax for BC purposes, no `paged` arg.
		return $this->get_all_usermeta( $args );
	}

	/**
	 * A performing function that return true if the item can potentially
	 * be subject for indexation or not. This will be used to determine if an item is part of the index
	 * As this function will be called synchronously during other operations,
	 * it has to be as lightweight as possible. No db calls or huge loops.
	 *
	 * @param mixed $item
	 *
	 * @return bool
	 */
	public function supports( $item ) {
		return true;
	}

	public function get_default_autocomplete_config() {
		$config = array(
			'position'        => 40,
			'max_suggestions' => 3,
			'tmpl_suggestion' => 'autocomplete-usermeta-suggestion',
		);

		return array_merge( parent::get_default_autocomplete_config(), $config );
	}

	/**
	 * @param mixed $item
	 */
	public function delete_item( $item ) {
		$this->assert_is_supported( $item );
		$this->get_index()->deleteObject( $item->umeta_id );
	}
}
