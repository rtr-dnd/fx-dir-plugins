<?php 

/**
 * Social Networks settings
 * @param  array $networks 
 * @return array          
 */
function um_social_login_networks( $networks ){

	$networks['facebook'] = array(

			'name'      => __( 'Facebook', 'um-social-login' ),
			'button'    => __( 'Connect with Facebook', 'um-social-login' ),
			'color'     => '#fff',
			'bg'        => '#4267B2',
			'bg_hover'  => '#365899',
			'icon'      => 'um-faicon-facebook-square',
			'opts'      => array(
				'facebook_app_id'       => __( 'App ID', 'um-social-login' ),
				'facebook_app_secret'   => __( 'App Secret', 'um-social-login' ),
			),
			'sync'      => array(
				'handle'    => 'facebook_handle',
				'link'      => 'facebook_link',
				'photo_url' => 'http://graph.facebook.com/{id}/picture?type=square',
			),
			'hybridauth_key' => 'Facebook',
			'sync_v2' => array(
				'identifier',
				'displayName',
				'firstName',
				'lastName',
				'profileURL',
				'webSiteURL',
				'gender',
				'language',
				'description',
				'email',
				'region',
				'photoURL',
				'emailVerified',
				'city',
				'country',
				'birthYear',
				'birthMonth',
				'birthDay'
			),
			'has_multiple_callback' => true,
	);

	$networks['twitter'] = array(
			'name'      => __( 'Twitter', 'um-social-login' ),
			'button'    => __( 'Sign in with Twitter', 'um-social-login' ),
			'color'     => '#fff',
			'bg'        => '#55acee',
			'bg_hover'  => '#4997D2',
			'icon'      => 'um-faicon-twitter',
			'opts'      => array(
				'twitter_consumer_key'      => __( 'Consumer Key', 'um-social-login' ),
				'twitter_consumer_secret'   => __( 'Consumer Secret', 'um-social-login' ),
			),
			'sync'      => array(
				'handle'        => 'twitter_handle',
				'link'          => 'twitter_link',
				'photo_url_dyn' => 'twitter_photo_url_dyn',
			),
			'hybridauth_key' => 'Twitter',
			'sync_v2' => array(
				'identifier',
				'displayName',
				'description',
				'firstName',
				'email',
				'emailVerified',
				'webSiteURL',
				'region',
				'profileURL',
				'photoURL',
				'data'
			),
			'has_multiple_callback' => true,
	);

	$networks['google'] = array(
			'name'      => __( 'Google','um-social-login' ),
			'button'    => __( 'Sign in with Google', 'um-social-login' ),
			'color'     => '#fff',
			'bg'        => '#4285f4',
			'bg_hover'  => '#3574de',
			'icon'      => 'um-faicon-google',
			'opts'      => array(
				'google_client_id'      => __( 'Client ID', 'um-social-login' ),
				'google_client_secret'  => __( 'Client secret', 'um-social-login' ),
			),
			'sync'      => array(
				'handle'        => 'google_handle',
				'link'          => 'google_link',
				'photo_url_dyn' => 'google_photo_url_dyn',
			),
			'hybridauth_key' => 'Google',
			'sync_v2' => array(
				'identifier',
				'firstName',
				'lastName',
				'displayName',
				'photoURL',
				'profileURL',
				'gender',
				'language',
				'email',
				'emailVerified',
			),
			'has_multiple_callback' => true,
	);
	
	$networks['linkedin'] = array(
			'name'      => __( 'LinkedIn', 'um-social-login' ),
			'button'    => __( 'Sign in with LinkedIn', 'um-social-login' ),
			'color'     => '#fff',
			'bg'        => '#0976b4',
			'bg_hover'  => '#07659B',
			'icon'      => 'um-faicon-linkedin',
			'opts'      => array(
				'linkedin_api_key'      => __( 'API Key', 'um-social-login' ),
				'linkedin_api_secret'   => __( 'API Secret', 'um-social-login' ),
			),
			'sync'      => array(
				'handle'        => 'linkedin_handle',
				'link'          => 'linkedin_link',
				'photo_url_dyn' => 'linkedin_photo_url_dyn',
			),
			'hybridauth_key' => 'LinkedIn',
			'sync_v2' => array(
				'firstName',
				'lastName',
				'identifier',
				'photoURL',
				'email',
				'emailVerified',
				'displayName',
			),
			'has_multiple_callback' => true,
	);
	
	$networks['instagram'] = array(
			'name'      => __( 'Instagram', 'um-social-login' ),
			'button'    => __( 'Sign in with Instagram', 'um-social-login' ),
			'color'     => '#fff',
			'bg'        => '#3f729b',
			'bg_hover'  => '#4480aa',
			'icon'      => 'um-faicon-instagram',
			'opts'      => array(
				'instagram_client_id'       => __( 'Client ID', 'um-social-login' ),
				'instagram_client_secret'   => __( 'Client Secret', 'um-social-login' ),
			),
			'sync'      => array(
				'handle'        => 'instagram_handle',
				'link'          => 'instagram_link',
				'photo_url_dyn' => 'instagram_photo_url_dyn',
			),
			'hybridauth_key' => 'Instagram',
			'sync_v2' => array(
				'identifier',
				'description',
				'photoURL',
				'webSiteURL',
				'displayName',
				'profileURL',
				'data',
			),
			'has_single_callback' => true,
	);

	$networks['vk'] = array(
			'name'      => __( 'Vkontakte', 'um-social-login' ),
			'button'    => __( 'Sign in with VK', 'um-social-login' ),
			'color'     => '#fff',
			'bg'        => '#45668e',
			'bg_hover'  => '#395f8e',
			'icon'      => 'um-faicon-vk',
			'opts'      => array(
				'vk_api_key'    => __( 'API Key', 'um-social-login' ),
				'vk_api_secret' => __( 'API Secret', 'um-social-login' ),
			),
			'sync'      => array(
				'handle'        => 'vk_handle',
				'link'          => 'vk_link',
				'photo_url_dyn' => 'vk_photo_url_dyn',
			),
			'hybridauth_key' => 'Vkontakte',
			'sync_v2' => array(
				'identifier',
				'email',
				'firstName',
				'lastName',
				'displayName',
				'photoURL',
				'profileURL',
				'gender'
			),
			'has_multiple_callback' => true,
	);


	$networks['github'] = array(
			'name'      => __( 'Github', 'um-social-login' ),
			'button'    => __( 'Sign in with Github', 'um-social-login' ),
			'color'     => '#ffffff',
			'bg'        => '#444444',
			'bg_hover'  => '#000000',
			'icon'      => 'um-faicon-github',
			'opts'      => array(
				'github_api_key'    => __( 'Client ID', 'um-social-login' ),
				'github_api_secret' => __( 'Client Secret', 'um-social-login' ),
			),
			'sync'      => array(
				'handle'        => 'github_handle',
				'link'          => 'github_link',
				'photo_url_dyn' => 'github_photo_url_dyn',
			),
			'hybridauth_key' => 'GitHub',
			'sync_v2' => array(
				'identifier',
				'displayName',
				'description',
				'photoURL',
				'profileURL',
				'email',
				'webSiteURL',
				'region',
				'email',
			),
			'has_single_callback' => true,
	);

	$networks['wordpress'] = array(
			'name'      => __( 'Wordpress', 'um-social-login' ),
			'button'    => __( 'Sign in with Wordpress', 'um-social-login' ),
			'color'     => '#ffffff',
			'bg'        => '#0085ba',
			'bg_hover'  => '#0085ba',
			'icon'      => 'um-faicon-wordpress',
			'opts'      => array(
				'wordpress_api_id'    => __( 'Client ID', 'um-social-login' ),
				'wordpress_api_secret' => __( 'Client Secret', 'um-social-login' ),
			),
			'sync'      => array(
				'handle'        => 'wordpress_handle',
				'link'          => 'wordpress_link',
				'photo_url_dyn' => 'wordpress_photo_url_dyn',
			),
			'hybridauth_key' => 'WordPress',
			'sync_v2' => array(
				'identifier',
				'displayName',
				'description',
				'photoURL',
				'profileURL',
				'email',
				'webSiteURL',
				'region',
				'email',
			),
			'has_multiple_callback' => true,
	);


	return $networks;
}
add_filter("um_social_login_networks","um_social_login_networks");
