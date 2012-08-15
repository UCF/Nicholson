			<div id="footer" class="clearfix">
				<div class="row">
					<div class="span4 left" id="social">
						<h2>Get Social</h2>
						<ul class="clearfix">
							<? $facebook_url = get_theme_option('facebook_url'); ?>
							<? if($facebook_url != '') { ?>
							<li>
								<a id="facebook" href="<?=$facebook_url?>">Facebook</a>
							</li>
							<? } ?>
							<? $twitter_url = get_theme_option('twitter_url'); ?>
							<? if($twitter_url != '') { ?>
							<li>
								<a id="twitter" href="<?=$twitter_url?>">Twitter</a>
							</li>
							<? } ?>
							<? $linkedin_url = get_theme_option('linkedin_url'); ?>
							<? if($linkedin_url != '') { ?>
							<li>
								<a id="linkedin" href="<?=$linkedin_url?>">LinkedIn</a>
							</li>
							<? } ?>
							<? $youtube_url = get_theme_option('youtube_url'); ?>
							<? if($youtube_url != '') { ?>
							<li>
								<a id="youtube" href="<?=$youtube_url?>">Youtube</a>
							</li>
							<? } ?>
						</ul>
						<? $foursquare_url = get_theme_option('foursquare_url'); ?>
						<? if($foursquare_url != '') { ?>
						<a id="foursquare" href="<?=$foursquare_url?>">Foursquare</a>
						<? } ?>
					</div>
					<div class="span5 center">
						<? $donate_link_text = get_theme_option('donate_link_text'); ?>
						<? if($donate_link_text) { ?>
						<a href="<?=get_theme_option('donate_link_url')?>" id="donate">
							<h2>Make a Gift &amp; Support Nicholson</h2>
							<?=$donate_link_text?>
						</a>
						<? } ?>
					</div>
					<div class="span3 right">
						<h2>Contact Us</h2>
						<p>
							<?=get_theme_option('organization_name')?>
						</p>
						<p>
							<?=get_theme_option('mailing_address')?>
						</p>
						<p>
							<?=get_theme_option('contact_information')?>
						</p>
					</div>
				</div>
			</div>
		</div><!-- #blueprint-container -->
	</body>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?="\n".footer_()."\n"?>
</html>