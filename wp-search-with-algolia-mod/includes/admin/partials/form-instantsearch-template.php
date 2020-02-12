<div class="form-group">
	<textarea class="instantsearch-template-textarea form-control" name="algolia_instantsearch_template" rows="20" style="min-width:500px; max-width:100%;min-height:50px;height:100%;width:100%;" ><?php echo get_option("algolia_instantsearch_template"); ?></textarea>
</div>

<h2>default value</h2>
<textarea readonly rows="20" style="min-width:500px; max-width:100%;min-height:50px;height:100%;width:100%;">
<script type="text/html" id="tmpl-instantsearch-hit">
	<article itemtype="http://schema.org/Article">
		<# if ( data.images && data.images.thumbnail ) { #>
		<div class="ais-hits--thumbnail">
			<a href="{{ data.permalink }}" title="{{ data.post_title }}">
				<img src="{{ data.images.thumbnail.url }}" alt="{{ data.post_title }}" width="80" title="{{ data.post_title }}" itemprop="image" />
			</a>
		</div>
		<# } #>

		<div class="ais-hits--content">
		<# if ( data.permalink ) { #>
			<h2 itemprop="name headline"><a href="{{ data.permalink }}" title="{{ data.post_title }}" itemprop="url">{{{ data._highlightResult.post_title.value }}}</a></h2>
			<div class="excerpt">
				<p>
		<# if ( data._snippetResult && data._snippetResult['content'] ) { #>
		<span class="suggestion-post-content">{{{ data._snippetResult['content'].value }}}</span>
		<# } #>
				</p>
			</div>
		<# } else { #>
			<h2 itemprop="name headline"><a href="{{ data.posts_url }}" title="{{ data.display_name }}" itemprop="url">{{{ data.display_name }}}</a></h2>
			<div class="excerpt">
				<p>
		<# if ( data._highlightResult && data._highlightResult.meta_value['value'] ) { #>
		<span class="suggestion-post-content">{{{ data._highlightResult.meta_value['value'] }}}</span>
		<# } #>
				</p>
			</div>
		<# } #>
		</div>
		<div class="ais-clearfix"></div>
	</article>
</script>
</textarea>