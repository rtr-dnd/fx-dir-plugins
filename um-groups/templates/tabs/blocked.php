<?php
/**
 * Template for the UM Groups, The group "Blocked" tab content
 *
 * Page: "Group", tab "Blocked"
 * Caller: function um_groups_single_page_content__blocked()
 * Child template: list-users.php
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/um-groups/tabs/blocked.php
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$unique_hash = substr( md5( $group_id ), 10, 5 );
$current_page = ( ! empty( $_GET[ 'page_' . $unique_hash ] ) && is_numeric( $_GET[ 'page_' . $unique_hash ] ) ) ? (int) $_GET[ 'page_' . $unique_hash ] : 1;

UM()->get_template( 'js/members-list.php', um_groups_plugin, array( 'list' => 'blocked' ), true );
UM()->get_template( 'members-header.php', '', array(), true );
UM()->get_template( 'members-pagination.php', '', array(), true ); ?>

<div class="um-groups-blocked-wrapper" data-hash="<?php echo esc_attr( $unique_hash ); ?>"
     data-page="<?php echo esc_attr( $current_page ) ?>">
	<div class="um-members-overlay"><div class="um-ajax-loading"></div></div>
	<div class="um-groups-members-list"></div>
	<div class="um-members-pagination-box"></div>
</div>