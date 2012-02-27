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
							$content = str_replace(']]>', ']]&gt;', apply_filters('the_content', $centerpiece->post_content));
							echo sprintf('<div class="shoutout sans"><span class="title">%s</span>%s</div>', esc_html($centerpiece->post_title), $content);
							echo sprintf('<img src="%s" alt="%s" />', $centerpiece_image[0], esc_html($centerpiece->post_title));
						}
					} else {
						echo '<div style="text-align:center;font-size:40px;line-height:1.5em;">There is no Front Page Centerpiece to display. Please create on in the WordPress Administration Dashboard.</div>';
					}
				?>
			</div>
		</div>
	</div>
<?php get_footer();?>