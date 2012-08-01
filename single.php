<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
	<div class="single">
		<h2>Nicholson News</h2>
		<div class="row">
			<div class="span7">
				<h3><?=the_title()?></h3>
				<?=$content = str_replace(']]>', ']]>', apply_filters('the_content', $post->post_content))?>
			</div>
			<div class="span4 offset1">
				<div class="row">
					<div class="span4">
						<? if( ($featured_image = get_featured_image($post)) !== False && ($featured_image_url = get_featured_image_url($post->ID)) !== False) { ?>
						<img src="<?=$featured_image_url?>" />
						<? if($featured_image->post_excerpt != '') { ?>
						<p class="caption"><?=$featured_image->post_excerpt?></p>
						<? } ?>
						<? } ?>
					</div>
				</div>
				<? if(($quote = get_post_meta($post->ID, 'post_quote', True)) !== '') { ?>
				<div class="row">
					<div class="span4">
						<p class="quote">
							&ldquo;<?=esc_html($quote)?>&rdquo;
						</p>
					</div>
				</div>
				<? } ?>
			</div>
		</div>
		<?php get_template_part('includes/below-the-fold'); ?>
	</div>
<?php get_footer();?>