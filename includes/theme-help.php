<div id="theme-help" class="i-am-a-fancy-admin">
	<div class="container">
		<h2>Help</h2>
		
		<?php if ($updated):?>
		<div class="updated fade"><p><strong><?=__( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>
		
		<div class="sections">
			<ul>
				<li class="section"><a href="#general">General</a></li>
				<li class="section"><a href="#shortcodes">Shortcodes</a></li>
			</ul>
		</div>
		<div class="fields">
			<ul>
				<li class="section" id="general">
					<h3>General Information</h3>
					<h4>Github</h4>
					<p>The theme is currently hosted on Github at <a href="https://github.com/UCF/Nicholson">https://github.com/UCF/Nicholson</a>. We can either grant Nicholson personnel access to the /UCF repository or it can be forked into the Nichoslson account.</p>

					<h4>Bootstrap</h4>
					<p>This theme is based on Twitter's Bootstrap HTML, CSS, and JavaScript framework located here: <a href="http://twitter.github.com/bootstrap/">http://twitter.github.com/bootstrap/</a>. Anything currently possible in the Bootstrap examples is possible in this theme.</p>

					<h4>Support</h4>
					<p>If you have any questions or problems, you can email the general Web Communications Team email <a href="mailto:webcom@ucf.edu">webcom@ucf.edu</a> or contact any individual directly.</p>
				</li>
				<li class="section" id="shortcodes">
					<h3>Shortcodes</h3>
					
					<h4>Generic Shortcode</h4>
					<p>Any post type (Posts, Pages, Front Page Centerpieces, People, and Documents) can be outputted using a shortcode of the form <post type>-list. The <post type> placeholder is the name of the post type as specified in custom_post_types.php. For example, to output a list of People posts the shortcode would look like the following: [person-list]. </p>

					<p>This generic shortcode can take the following arguments although none are required:</p>
					<ul style="margin-left:20px;">
						<li><strong>limit</strong> – How many posts are displayed. All are displayed by default.</li>
						<li><strong>class</strong> – Additional CSS classes to add to the surrounding element. Empty by default.</li>
						<li><strong>orderby</strong> – Which field or fields the posts will be ordered by. By default: menu_order and title. For more details on the orderby parameter, see the following WordPress documentation: http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters</li>
						<li><strong>order</strong> – Which direction the posts are ordered in. Default is ascending. The possible options are ASC and DESC.</li>
						<li><strong>Taxonomies</strong> - There are four taxonomy arguments in this theme. Passing one or more of these arguments will cause only posts that exist in the specified taxonomies to appear in the output. All taxonomies must be specified in slug format. The available taxonomies are:
							<ul style="margin-left:40px;">
								<li><strong>tags</strong></li>
								<li><strong>categories</strong></li>
								<li><strong>org_groups</strong></li>
								<li><strong>document_groups</strong></li>
							</ul>
						</li>
					</ul>
					<p>As an example, the shortcode to display six random people from the Executive organizational group would be: [person-list limit=”6” orderby=”rand” org_groups=”executive”]</p>

					<p>While the default styling for the generic shortcode is an unstyled list, some post types have different styling. These include:</p>
					<ul style="margin-left:20px;">
						<li><strong>document</strong> – An unstyled list with a small icon indicating the document type next to each list item.</li>
						<li><strong>person</strong> – A table with the following columns: name, job title, phone number and email.</li>
						<li><strong>post</strong> – Each post is a stylized box that is 4 columns wide and contains the post’s featured image (if one exists), the post title, the post excerpt, the post creation date and time, and the categories that the post belongs to.</li>
					</ul>

					<h4>Person Picture List (person-picture-list)</h4>
					<p>This shortcode is very similar to the generic shortcode [person-list] except that it outputs the profile image of the person, name, and job title in a row format instead of a table. In addition to all the arguments that the [person-list] shortcode takes, this shortcode also takes an optional row-size argument that controls how many people will be displayed in each row. The default is five.</p>

					<h4>Shadow Well (shadow-well)</h4>
					<p>This shortcodes wraps the specified content in a stylized, shadow box. It takes three optional arguments:</p>
					<ul style="margin-left:20px;">
						<li>width – Width of the well in columns. Default is 12</li>
						<li>wrap – Whether or not to include the bootstrap row wrap markup. Default is true.</li>
						<li>gold – Whether to use a gold background color instead of white. The default is false.</li>
					</ul>
					<p>As an example, the shortcode to style the content “This is a shadow well!” in a shadow well would be: [shadow-well]This is a shadow well![/shadow-well]</p>

					<h4>Titled Section (titled-section)</h4>
					<p>This shortcode wraps the specific content in markup that adds a gold header bar to the section. This shortcode takes one required argument: title.</p>

					<h4>Category List (category-list)</h4>
					<p>This shortcode outputs a list of post categories in a series of unstyled lists divided into columns. Each item is a link to the category’s listing page. This shortcode takes two optional arguments:</p>
					<ul style="margin-left:20px">
						<li><strong>columns</strong> – The number of columns the categories should be split into. The default is three.</li>
						<li><strong>width</strong> – The width of each of the columns in spans. The default is two.</li>
					</ul>

					<h4>Archive Year list (archive-year-list)</h4>
					<p>This shortcode outputs an unstyled list of archive years that link to the archive pages.</p>
				</li>
			</ul>
		</div>
	</div>
</div>