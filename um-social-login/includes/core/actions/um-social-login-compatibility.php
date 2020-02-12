<?php 

add_action("wp_mail_smtp_mailcatcher_smtp_send_before","um_sso_compatibility_wp_mail_smtp", 10, 1 );
function um_sso_compatibility_wp_mail_smtp(){
	
	remove_action( 'template_redirect', array( 'Social_Login_Connect', 'init' ) );	
}
