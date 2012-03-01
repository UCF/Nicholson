<?php get_header(); the_post();?>
	<div class="page-content">
		<h2><?=$post->post_title?></h2>
		<?=$content = str_replace(']]>', ']]>', apply_filters('the_content', $post->post_content))?>
		<?php get_template_part('includes/below-the-fold'); ?>
	</div>
<?php get_footer();?>