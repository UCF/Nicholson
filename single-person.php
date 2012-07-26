<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
	<div class="page-content person-profile">
		<div class="row">
			<div class="span12">
				<h2>Nicholson Faculty</h2>
			</div>
		</div>
		<div class="row">
			<div id="picture">
				<? $image_url = get_featured_image_url($person);?>
				<?=sc_shadow_well(array('wrap'=>False, 'width'=>'4'), '<img src="'.($image_url ? $image_url : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg').'" />') ?>
			</div>
			<div class="span8">
				<div class="row">
					<div class="span8">
						<h3 id="name"><?=Person::get_name($post)?></h3>
					</div>
				</div>
				<div class="row">
					<div class="span8">
						<ul id="details" class="unstyled">
							<li class="job_title"><?=get_post_meta($post->ID, 'person_jobtitle', True)?></li>
							<li class="phones"><?=get_post_meta($post->ID, 'person_phones', True)?></li>
							<li class="email">
								<? $email     = get_post_meta($post->ID, 'person_email', True); ?>
								<? if($email) {?>
									<a href="mailto:<?=$email?>"><?=$email?></a>
								<? } ?>
							</li>
							<li class="location">
								<? 
									$office_location_url  = get_post_meta($post->ID, 'person_office_location_url', True);
									$office_location_text = get_post_meta($post->ID, 'person_office_location_text', True);
								?>
								<? if($office_location_url && $office_location_text) {?>
									<a href="<?=$office_location_url?>"><?=$office_location_text?></a>
								<? } ?>
							</li>
							<li class="cv">
								<? 
									$cv_url = False;
									if(($cv_post_id = get_post_meta($post->ID, 'person_cv', True)) && ($cv_post = get_post($cv_post_id))) {
										$cv_url  = wp_get_attachment_url($cv_post->ID);
									}
								?>
								<? if($cv_url) {?>
									<a href="<?=$cv_url?>">Curriculum Vita</a>
								<? } ?>
							</li>
						</ul>
					</div>
				</div>
				<? $office_hours = get_post_meta($post->ID, 'person_office_hours', True); ?>
				<? if($office_hours != '') { ?>
				<div class="row">
					<?=sc_shadow_well(
						array('wrap'=>False, 'width'=>3, 'gold'=>True),
						'<h4>OFFICE HOURS</h4><p>'.get_post_meta($post->ID, 'person_office_hours', True).'</p>'
					);?>
				</div>
				<? } ?>
			</div>
		</div>
		<? 
			$news_posts_content = '';
			$news_posts         = Person::get_news_posts($post);
			if(count($news_posts) > 0) {
				$news_posts_content = '<h3>News</h3><ul class="unstyled" id="news">';
				foreach($news_posts as $news_post) {
					$news_posts_content .= '<li><a href="'.get_permalink($news_post->ID).'">'.$news_post->post_title.'</a></li>';
				}
				$news_posts_content .= '</ul>';
			}
		?>
		<?= sc_titled_section(
				array('title'=>'Biography'),
				'<div class="row"><div class="span8">'.
					str_replace(']]>', ']]>', apply_filters('the_content', $post->post_content))
				.'</div><div class="span3 offset1">
					<div class="row">
						<div class="span3">
							'.$news_posts_content.'
						</div>
					</div>
				</div></div>'
			);
		?>
	<?php get_template_part('includes/below-the-fold'); ?>
<?php get_footer();?>