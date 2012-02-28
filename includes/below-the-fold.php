<?php $options = get_option(THEME_OPTIONS_NAME);?>
<div class="row">
	<div id="below-the-fold" class="clearfix">
		<div class="span6 left">
			<?=display_news()?>
			<h2>Search</h2>
			<?=get_search_form()?>
		</div>
		<div class="span3 center">
			<?=display_events()?>
		</div>
		<div class="span3 right">
			<h2>Resources</h2>
			<?=get_menu('resources', array(), 'resources')?>
			<h2 id="about-title">About the College</h2>
			<?=get_menu('about', array(), 'about')?>
		</div>
		<div class="clear" style="width:100%;">&nbsp;</div>
	</div>
</div>