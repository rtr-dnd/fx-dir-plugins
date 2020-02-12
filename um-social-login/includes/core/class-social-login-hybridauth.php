<?php 

namespace um_ext\um_social_login\core;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once um_social_login_path . "includes/libs/hybridauth/src/autoload.php";

use Hybridauth\Hybridauth;
use Hybridauth\Storage\Session;
use Hybridauth\HttpClient;

/**
 * Class Social_Login_Hybridauth
 * @package um_ext\um_social_login\core
 */
class Social_Login_Hybridauth{

	/**
	 * Networks
	 * @var array
	 */
	var $networks;


	/**
	 * __construct
	 */
	function __construct() {	
		
		add_action("init", array( &$this,"init" ) );
	
	}

	/**
	 * Init
	 */
	function init(){
		
		$this->all_networks = apply_filters( 'um_social_login_networks', array() );
		$this->available_networks = UM()->Social_Login_API()->available_networks();

	}


	/**
	 * Get Session
	 * @return object
	 */
	function getSession(){
		$storage = new Session();
	   
	   	return $storage;

	}

	/**
	 * Connect a user with a provider account
	 * @param  string $provider 
	 */
	function connectUser( $provider = '', $callback_url = '', $has_authenticated = false ){

		$config = $this->getConfig( $provider );

		if( isset( $config['provider'] ) ){
			$provider = $config['provider'];
		}

		if( ! empty( $callback_url ) ){
			$config['callback'] = $callback_url;
		}

		$providerClass = "Hybridauth\Provider\\$provider";

		if( class_exists( $providerClass ) ){
			try {

				$hybridauth = new $providerClass( $config );

				if ( ! isset( $_REQUEST['return_provider'] ) && ! $has_authenticated ) {
						$sso_session = $this->getSession();
						$sso_session->set("sso_last_auth_provider", $config['provider_slug'] );
						$sso_session->set("sso_last_auth_page", $config['returnUrl'] );
						$hybridauth->authenticate();
				}

				$userProfile = $hybridauth->getUserProfile();

				$accessToken = $hybridauth->getAccessToken();

				// oAuth 2
				if ( $accessToken ) {

					return array( 
						'hybridauth'    => $hybridauth,
						'userProfile'   => $userProfile,
						'callbackUrl'   => $config['callback'],
						'returnUrl'     => $config['returnUrl']
					);
					
				}

				// OpenID
				if( $userProfile ){

					return array( 
						'hybridauth'    => $hybridauth,
						'userProfile' 	=> $userProfile, 
						'callbackUrl' 	=> $config['callback'], 
						'returnUrl'	 	=> $config['returnUrl'] 
					);
					
				}

				if( ! $userProfile || ! $accessToken ){
					return array( 
						'has_errors' 	=> true, 
						'callbackUrl' 	=> $config['callback'], 
						'returnUrl'	 	=> $config['returnUrl'] 
					);
				}

			}

			/**
			 * Catch Curl Errors
			 *
			 * This kind of error may happen in case of:
			 *     - Internet or Network issues.
			 *     - Your server configuration is not setup correctly.
			 *
			 * The full list of curl errors that may happen can be found at http://curl.haxx.se/libcurl/c/libcurl-errors.html
			 */

			catch (Hybridauth\Exception\HttpClientFailureException $e) {
			   wp_die('Curl text error message : '.$hybridauth->getHttpClient()->getResponseClientError(), 'UM Social Login', array( 'back_link' => true ) );
			}

			/**
			 * Catch API Requests Errors
			 *
			 * This usually happens when requesting a:
			 *     - Wrong URI or a mal-formatted http request.
			 *     - Protected resource without providing a valid access token.
			 */

			catch (Hybridauth\Exception\HttpRequestFailedException $e) {
			    wp_die('Raw API Response: '.$hybridauth->getHttpClient()->getResponseBody(), 'UM Social Login', array( 'back_link' => true )  );
			}

			/**
			 * Base PHP's exception that catches everything [else]
			 */

			catch (\Exception $e) {

 				return array( 
 					'has_errors' 			=> true, 
 					'raw_error_response' 	=> $e->getMessage(), 
 					'error_message' 		=> '', 
 					'callbackUrl' 			=> $config['callback'], 
 					'returnUrl' 			=> $config['returnUrl'] 
 				);
					
			}


		}else{
			wp_die('Invalid class: '.$providerClass, 'UM Social Login', array( 'back_link' => true )  );
		}
	
	}

	/**
	 * Get Config
	 * @return array $config
	 */
	function getConfig( $provider = '' ){

		if( empty( $provider ) ) return false;

	    if( ! isset( $this->available_networks[ $provider ] ) && ! empty( $provider ) ) wp_die( __("Provider not available.","um-social-login" ) );

	    $arr_providers = array();
	    $config = array();

	    foreach(  $this->available_networks as $p_key => $p_data ){
	    	$arr_providers[ $p_key ] = array(
		            'enabled' 		=> true,
		            'keys' 			=> $this->getKeys( $p_data['opts'] ),
		            'provider' 		=> $p_data['hybridauth_key'],
		            'provider_slug' => $p_key,
		    );
		}

		$config['callback'] 	= $this->getCallbackUrl( $provider );
		$config['returnUrl'] 	= $this->getReturnUrl( $provider, $config['callback'] );
		$config['redirect_uri'] = $this->getReturnUrl( $provider, $config['callback'] );


		if(  $provider ){
		    $config = array_merge( $config, $arr_providers[ $provider ] );
		}else{
		    $config['providers'] =  $arr_providers;
		}

		return $config;
	}

	/**
	 * Get Provider's API keys
	 * @param  array $options 
	 * @return array          
	 */
	function getKeys( $options ){

		$arr_keys = array();
		$arr_options_keys = array_keys( $options );
		foreach( $arr_options_keys as $key ){
			
			if( strrpos( $key , 'id' ) ){
				$arr_keys['id'] = UM()->options()->get( $key );
			}

			if( strrpos( $key , 'key' ) ){
				$arr_keys['key'] = UM()->options()->get( $key );
			}

			if( strrpos( $key , 'secret' ) ){
				$arr_keys['secret'] = UM()->options()->get( $key );
			}

		}

		return $arr_keys;
	}

	/**
	 * Get current URL
	 * @return  string
	 */
	function getCurrentUrl(){

		global $wp;

		$current_url = home_url( add_query_arg( array(), $wp->request ) );

		$current_url = remove_query_arg( array('provider','state','code'), $current_url );
		
		// Reject all file URLs from current URL
		if( strpos( $current_url ,"/wp-content/") !== false ){
			
			exit;
		}

		return $current_url;

		
		
	}

	/**
	 * Get Login URL
	 * @param  string $provider 
	 */
	function getConnectUrl( $provider = '', $is_shortcode = false ){

		if( defined("DOING_AJAX") && DOING_AJAX ){
			return;
		}

		if (  isset( $_REQUEST['provider'] ) || isset( $_REQUEST['code'] ) || isset( $_REQUEST['state'] ) ) {
			return;
		}
		
		if( isset( $_REQUEST['um_dynamic_sso'] ) && ! isset( $_REQUEST['err'] ) ) {
			return;
		}

		$sso_session = $this->getSession();

		$current_url = add_query_arg( 'oauthWindow','true', '' );

		$current_url = add_query_arg('provider', $provider, $current_url );

		if ( $is_shortcode ) {

			$current_url = add_query_arg('um_sso', $is_shortcode , $current_url );
			$sso_session->set('um_sso_has_dynamic_return_url', true );
			$sso_session->set('um_sso', $is_shortcode );
			$returnUrl = $this->getCurrentUrl();
			$sso_session->set('um_sso_current_url', $returnUrl );
			$sso_session->set('um_social_login_redirect', $returnUrl );

		}else{
			$sso_session->set('um_sso_has_dynamic_return_url', null );
			$sso_session->set('um_sso', null );
			$sso_session->set('_um_shortcode_id', null );
			$sso_session->set('um_sso_current_url', null );
		}

		if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			$sso_session->set( 'um_social_login_redirect', esc_url_raw( $_REQUEST['redirect_to'] ) );
		}
		
		$current_url = apply_filters( "um_social_login_connect_url", $current_url, $provider );
		$current_url = apply_filters( "um_social_login_connect_url__{$provider}", $current_url );


		return $current_url;
	}

	/**
	 * Get Disconnect Url
	 * @param  string $provider 
	 * @return string     
	 */
	function getDisconnectUrl( $provider = '', $userId = null ){

		if ( isset( $_REQUEST['provider'] ) ) {
			return;
		}

		$current_url = add_query_arg( 'disconnect', $provider, '' );

		return $current_url;
	}

	/**
	 * Get Callback URL
	 * @param  string $provider 
	 * @return string            
	 */
	function getCallbackUrl( $provider = '' ){

		if( um_is_core_page('login') ){

			$callback_url = um_get_core_page('login');
		
		}else if( um_is_core_page('register') ){

			$callback_url = um_get_core_page('register');

		}else if( um_is_core_page('account') ){

			$callback_url = um_get_core_page('account').'social/';

		}else{

			$callback_url = um_get_core_page('login');
					
		}

		$callback_url = add_query_arg( 'provider', $provider, $callback_url );

		$callback_url = remove_query_arg( 'err', $callback_url );

		$callback_url = remove_query_arg( 'oauthWindow', $callback_url );

		$callback_url = apply_filters("um_social_login_callback_url", $callback_url, $provider );
			
		$callback_url = apply_filters("um_social_login_callback_url__{$provider}", $callback_url );
		
		return $callback_url;
	}

	/**
	 * Get return Url
	 * @param  string $provider    
	 * @param  string $callbackUrl 
	 * @return string          
	 */
	function getReturnUrl( $provider = '', $callbackUrl = '' ){

			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$callback_url = esc_url_raw( $_REQUEST['redirect_to'] );
			}
			
			$returnUrl = remove_query_arg("provider", $callbackUrl );

			$sso_session = $this->getSession();

			$has_dynamic_sso = $sso_session->get('um_sso');

			if ( $has_dynamic_sso  && ! um_is_core_page("account") && ! um_is_core_page("login") ) {

				//$returnUrl = $sso_session->set('um_sso_has_dynamic_return_url', true );
				$returnUrl = remove_query_arg("provider", $returnUrl );

				$returnUrl = add_query_arg("um_dynamic_sso", 1, $returnUrl );

			}


			$returnUrl = add_query_arg("return_provider", $provider , $returnUrl );

			return $returnUrl;
	}

	/**
	 * Jsdump browser console for debugging purposes
	 * @param  array $args
	 * @return json
	 */
	function jsdump( $args ){
		
		echo "<script>console.log(".json_encode( $args ).");</script>";
	}

}	