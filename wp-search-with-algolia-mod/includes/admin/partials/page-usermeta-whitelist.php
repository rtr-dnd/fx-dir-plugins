<div class="wrap">
  <h1>
	<?php echo esc_html( get_admin_page_title() ); ?>
	<button type="button" class="algolia-reindex-button button button-primary" data-index="usermeta"><?php esc_html_e( 'Re-index usermeta on Algolia', 'wp-search-with-algolia' ); ?></button>
  </h1>
  <form method="post" action="options.php">
		<?php
		settings_fields( $this->option_group );
		do_settings_sections( $this->slug );
		submit_button();
		?>
  </form>
</div>

