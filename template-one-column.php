<?php
/**
 * Template Name: One Column
 **/
?>
<?php get_header(); the_post();?>
	
	<div class="span12 page-content" id="<?=$post->post_name?>">
		<article>
			<h2><?php the_title();?></h2>
			<?php the_content();?>
		</article>
		<?
		if(!get_post_meta($post->ID, 'page_hide_fold', True)):
			get_template_part('includes/below-the-fold');
		endif
		?>
	</div>

<?php get_footer();?>