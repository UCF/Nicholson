			<div id="footer" class="clearfix">
				<div class="row">
					<div class="span4 left" id="social">
						<h2>Get Social</h2>
						<ul class="clearfix">
							<li>
								<a id="facebook" href="http://www.facebook.com">Facebook</a>
							</li>
							<li>
								<a id="twitter" href="http://www.twitter.com">Twitter</a>
							</li>
							<li>
								<a id="linkedin" href="http://www.linked.com">LinkedIn</a>
							</li>
							<li>
								<a id="youtube" href="http://www.youtube.com">Youtube</a>
							</li>
						</ul>
						<a id="foursquare" href="http://www.foursquare.com">Foursquare</a>
					</div>
					<div class="span5 center">
						<a href="<?=get_theme_option('donate_link_url')?>" id="donate">
							<h2>Make a Gift &amp; Support Nicholson</h2>
							Click here to donate now
						</a>
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