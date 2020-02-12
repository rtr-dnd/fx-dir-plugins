<script type="text/javascript">

	jQuery(function() {
		jQuery(document).on("click",'#btn_add_textbox', function(){
			jQuery('#input-text-list').append( '<li class="whitelist-item"><input type="textbox" class="whitelist-item-textbox form-control" name="whitelist[]"><button type="button" id="btn_remove_textbox" class="button btn-primary remove">delete</button></<input></li>');
		});
		jQuery(document).on('click','.remove', function(){
			jQuery(this).closest('.whitelist-item').remove();
			var $json_text = jQuery('.whitelist-item-textbox').map(function(){
				return '"'+jQuery(this).val()+'"';
				}).get();
			jQuery('#whitelist-json').val('['+$json_text+']');
		});
		jQuery(document).on('input','.whitelist-item-textbox', function(){
			var $json_text = jQuery('.whitelist-item-textbox').map(function(){
				return '"'+jQuery(this).val()+'"';
				}).get();
			jQuery('#whitelist-json').val('['+$json_text+']');
		});
	});

</script>

<button type="button" id="btn_add_textbox" class="button btn btn-primary">add textbox</button>
	<ul id="input-text-list">
		<?php
		$whitelist_json = get_option('algolia_usermeta_whitelist');
		 ?>
		<input type="hidden" id="whitelist-json" name="algolia_usermeta_whitelist" value="<?php echo $whitelist_json ?>"></input>
		<?php
		$listitems = json_decode($whitelist_json);
		if ( $listitems && (is_array($listitems) || is_object($listitems))) { foreach ($listitems as $listitem) {
		?>
		<div class="form-group">
			<li class="whitelist-item">
				<input type="textbox" class="whitelist-item-textbox form-control" name="whitelist[]" value="<?php echo $listitem ?>">
				<button type="button" id="btn_remove_textbox" class="button btn-primary remove">delete</button>
			</li>
		</div>
		<?php }} ?>
	</ul>
