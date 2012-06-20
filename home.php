<?php get_header()?>
	<div class="page-content" id="home" data-template="home-description">
		<div class="row">
			<div class="span-12" id="centerpiece">
				<? 
					$centerpiece = get_posts(array('post_type'=>'centerpiece', 'numberposts'=>1, 'orderby'=>'rand')); 
					$centerpiece = (count($centerpiece) > 0) ? $centerpiece[0] : False;
					if($centerpiece) {
						$centerpiece_image = wp_get_attachment_image_src(get_post_thumbnail_id($centerpiece->ID), 'single-post-thumbnail');
						if($centerpiece_image) {
							$position = get_post_meta($centerpiece->ID, 'centerpiece_position', True);
							$position = ($position == '') ? 'left': $position;
							$content = str_replace(']]>', ']]&gt;', apply_filters('the_content', $centerpiece->post_content));
							echo sprintf('<div class="shoutout sans '.$position.'"><span class="title">%s</span>%s</div>', esc_html($centerpiece->post_title), $content);
							echo sprintf('<img src="%s" alt="%s" />', $centerpiece_image[0], esc_html($centerpiece->post_title));

							$features = get_posts(array('post_type'=>'program', 'numberposts'=>5, 'orderby'=>'rand'));
							echo '<ul id="features">';
							foreach($features as $feature) {
								$feature_image = wp_get_attachment_image_src(get_post_thumbnail_id($feature->ID), 'single-post-thumbnail');
								if($feature_image) {
									echo sprintf(
										'<li><a href="%s"><img src="%s" alt="%s" /><p class="title">%s</p><p class="description">%s</p></a></li>',
										get_permalink($feature->ID),
										$feature_image[0],
										$feature->post_title.' Thumbnail',
										apply_filters('the_title', $feature->post_title),
										get_post_meta($feature->ID, 'program_feature_text', True)
									);
								}
							}
							echo '</ul>';
						}
					} else {
						echo '<div style="text-align:center;font-size:40px;line-height:1.5em;">There is no Front Page Centerpiece to display. Please create on in the WordPress Administration Dashboard.</div>';
					}
				?>
			</div>
		</div>
		<?php get_template_part('includes/below-the-fold'); ?>
	</div>
<?php get_footer();?>