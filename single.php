<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
	<div class="single">
		<h2>Nicholson News</h2>
		<div class="row">
			<div class="span7">
				<h3><?=the_title()?></h3>
				<p><?=date('F j, Y', strtotime($post->post_date))?></p>
				<?=$content = str_replace(']]>', ']]>', apply_filters('the_content', $post->post_content))?>
			</div>
			<div class="span4 offset1">
				<? if( ($featured_image = get_featured_image($post)) !== False && ($featured_image_url = get_featured_image_url($post->ID)) !== False) { ?>
				<div class="row featured_image">
					<div class="span4">
						<img src="<?=$featured_image_url?>" />
						<? if($featured_image->post_excerpt != '') { ?>
						<p class="caption"><?=$featured_image->post_excerpt?></p>
						<? } ?>
					</div>
				</div>
				<? } ?>
				<? if(($quote = get_post_meta($post->ID, 'post_quote', True)) !== '') { ?>
				<div class="row">
					<div class="span4">
						<p class="quote">
							&ldquo;<?=esc_html($quote)?>&rdquo;
						</p>
					</div>
				</div>
				<? } ?>
				<? if( ($additional_image = get_additional_image($post)) !== False && ($additional_image_url = wp_get_attachment_image_src($additional_image->ID, 'full')) !== False) { ?>
				<div class="row additional_image">
					<div class="span4">
						<img src="<?=$additional_image_url[0]?>" />
						<? if($additional_image->post_excerpt != '') { ?>
						<p class="caption"><?=$additional_image->post_excerpt?></p>
						<? } ?>
					</div>
				</div>
				<? } ?>
			</div>
		</div>
		<div class="row previous-next">
			<div class="span7">

				<span class="previous pull-left"><?=previous_post_link('%link', 'Previous Article')?></a></span>
				<span class="next pull-right"><?=next_post_link('%link', 'Next Article')?></a>
			</div>
		</div>
		<?php get_template_part('includes/below-the-fold'); ?>
	</div>
<?php get_footer();?>