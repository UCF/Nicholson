<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
	<div class="single">
		<div class="row">
			<div class="span12">
				<h2>Nicholson News</h2>
			</div>
		</div>
		<div class="row">
			<div class="span7">
				<h3><?=the_title()?></h3>
				<?=$content = str_replace(']]>', ']]>', apply_filters('the_content', $post->post_content))?>
				<?php get_template_part('includes/below-the-fold'); ?>
			</div>
			<div class="span4 offset1">
				<? if( ($featured_image = get_featured_image($post)) !== False && ($featured_image_url = get_featured_image_url($post->ID)) !== False) { ?>
				<img src="<?=$featured_image_url?>" />
					<? if($featured_image->post_excerpt != '') { ?>
					<p class="caption"><?=$featured_image->post_excerpt?></p>
					<? } ?>
				<? } ?>
			</div>
		</div>
	</div>
<?php get_footer();?>