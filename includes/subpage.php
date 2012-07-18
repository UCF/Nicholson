<?php get_header(); the_post();?>
	<div class="page-content">
		<div class="row">
			<div class="span12">
				<h2><?=$post->post_title?></h2>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<?=$content = str_replace(']]>', ']]>', apply_filters('the_content', $post->post_content))?>
				<?php get_template_part('includes/below-the-fold'); ?>
			</div>
		</div>
	</div>
<?php get_footer();?>