<?php

abstract class CustomPostType{
	public 
		$name           = 'custom_post_type',
		$plural_name    = 'Custom Posts',
		$singular_name  = 'Custom Post',
		$add_new_item   = 'Add New Custom Post',
		$edit_item      = 'Edit Custom Post',
		$new_item       = 'New Custom Post',
		$public         = True,  # I dunno...leave it true
		$use_title      = True,  # Title field
		$use_editor     = True,  # WYSIWYG editor, post content field
		$use_revisions  = True,  # Revisions on post content and titles
		$use_thumbnails = False, # Featured images
		$use_order      = False, # Wordpress built-in order meta data
		$use_metabox    = False, # Enable if you have custom fields to display in admin
		$use_shortcode  = False, # Auto generate a shortcode for the post type
		                         # (see also objectsToHTML and toHTML methods)
		$taxonomies     = array('post_tag'),
		$built_in       = False,

		# Optional default ordering for generic shortcode if not specified by user.
		$default_orderby  = null,
		$default_meta_key = null,
		$default_order    = null;
	
	
	/**
	 * Wrapper for get_posts function, that predefines post_type for this
	 * custom post type.  Any options valid in get_posts can be passed as an
	 * option array.  Returns an array of objects.
	 **/
	public function get_objects($options=array()){
		$defaults = array(
			'numberposts'   => -1,
			'orderby'       => 'title',
			'order'         => 'ASC',
			'post_type'     => $this->options('name'),
		);
		$options = array_merge($defaults, $options);
		$objects = get_posts($options);
		return $objects;
	}
	
	
	/**
	 * Similar to get_objects, but returns array of key values mapping post
	 * title to id if available, otherwise it defaults to id=>id.
	 **/
	public function get_objects_as_options($options=array()){
		$objects = $this->get_objects($options);
		$opt     = array();
		foreach($objects as $o){
			switch(True){
				case $this->options('use_title'):
					$opt[$o->post_title] = $o->ID;
					break;
				default:
					$opt[$o->ID] = $o->ID;
					break;
			}
		}
		return $opt;
	}
	
	
	/**
	 * Return the instances values defined by $key.
	 **/
	public function options($key){
		$vars = get_object_vars($this);
		return $vars[$key];
	}
	
	
	/**
	 * Additional fields on a custom post type may be defined by overriding this
	 * method on an descendant object.
	 **/
	public function fields(){
		return array();
	}
	
	
	/**
	 * Using instance variables defined, returns an array defining what this
	 * custom post type supports.
	 **/
	public function supports(){
		#Default support array
		$supports = array();
		if ($this->options('use_title')){
			$supports[] = 'title';
		}
		if ($this->options('use_order')){
			$supports[] = 'page-attributes';
		}
		if ($this->options('use_thumbnails')){
			$supports[] = 'thumbnail';
		}
		if ($this->options('use_editor')){
			$supports[] = 'editor';
		}
		if ($this->options('use_revisions')){
			$supports[] = 'revisions';
		}
		return $supports;
	}
	
	
	/**
	 * Creates labels array, defining names for admin panel.
	 **/
	public function labels(){
		return array(
			'name'          => __($this->options('plural_name')),
			'singular_name' => __($this->options('singular_name')),
			'add_new_item'  => __($this->options('add_new_item')),
			'edit_item'     => __($this->options('edit_item')),
			'new_item'      => __($this->options('new_item')),
		);
	}
	
	
	/**
	 * Creates metabox array for custom post type. Override method in
	 * descendants to add or modify metaboxes.
	 **/
	public function metabox(){
		if ($this->options('use_metabox')){
			return array(
				'id'       => $this->options('name').'_metabox',
				'title'    => __($this->options('singular_name').' Fields'),
				'page'     => $this->options('name'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => $this->fields(),
			);
		}
		return null;
	}
	
	
	/**
	 * Registers metaboxes defined for custom post type.
	 **/
	public function register_metaboxes(){
		if ($this->options('use_metabox')){
			$metabox = $this->metabox();
			add_meta_box(
				$metabox['id'],
				$metabox['title'],
				'show_meta_boxes',
				$metabox['page'],
				$metabox['context'],
				$metabox['priority']
			);
		}
	}
	
	
	/**
	 * Registers the custom post type and any other ancillary actions that are
	 * required for the post to function properly.
	 **/
	public function register(){
		$registration = array(
			'labels'   => $this->labels(),
			'supports' => $this->supports(),
			'public'   => $this->options('public'),
			'taxonomies' => $this->options('taxonomies'),
			'_builtin' => $this->options('built_in')
		);
		
		if ($this->options('use_order')){
			$regisration = array_merge($registration, array('hierarchical' => True,));
		}
		
		register_post_type($this->options('name'), $registration);
		
		if ($this->options('use_shortcode')){
			add_shortcode($this->options('name').'-list', array($this, 'shortcode'));
		}
	}
	
	
	/**
	 * Shortcode for this custom post type.  Can be overridden for descendants.
	 * Defaults to just outputting a list of objects outputted as defined by
	 * toHTML method.
	 **/
	public function shortcode($attr){
		$default = array(
			'type' => $this->options('name'),
		);
		if (is_array($attr)){
			$attr = array_merge($default, $attr);
		}else{
			$attr = $default;
		}
		return sc_object_list($attr);
	}
	
	
	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 **/
	public function objectsToHTML($objects, $css_classes){
		if (count($objects) < 1){ return '';}
		
		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;
		
		ob_start();
		?>
		<ul class="<?php if($css_classes):?><?=$css_classes?><?php else:?><?=$class->options('name')?>-list<?php endif;?>">
			<?php foreach($objects as $o):?>
			<li>
				<?=$class->toHTML($o)?>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	
	
	/**
	 * Outputs this item in HTML.  Can be overridden for descendants.
	 **/
	public function toHTML($object){
		$html = '<a href="'.get_permalink($object->ID).'">'.$object->post_title.'</a>';
		return $html;
	}
}


class Document extends CustomPostType{
	public
		$name           = 'document',
		$plural_name    = 'Documents',
		$singular_name  = 'Document',
		$add_new_item   = 'Add New Document',
		$edit_item      = 'Edit Document',
		$new_item       = 'New Document',
		$use_title      = True,
		$use_editor     = False,
		$use_shortcode  = True,
		$use_metabox    = True,
		$taxonomies     = array('doc_groups');
	
	public function fields(){
		$fields   = parent::fields();
		$fields[] = array(
			'name' => __('URL'),
			'desc' => __('Associate this document with a URL.  This will take precedence over any uploaded file, so leave empty if you want to use a file instead.'),
			'id'   => $this->options('name').'_url',
			'type' => 'text',
		);
		$fields[] = array(
			'name'    => __('File'),
			'desc'    => __('Associate this document with an already existing file.'),
			'id'      => $this->options('name').'_file',
			'type'    => 'file',
		);
		return $fields;
	}
	
	
	static function get_document_application($form){
		return mimetype_to_application(self::get_mimetype($form));
	}
	
	
	static function get_mimetype($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}
		
		$prefix   = post_type($form);
		$document = get_post(get_post_meta($form->ID, $prefix.'_file', True));
		
		$is_url = get_post_meta($form->ID, $prefix.'_url', True);
		
		return ($is_url) ? "text/html" : $document->post_mime_type;
	}
	
	
	static function get_title($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}
		
		$prefix = post_type($form);
		
		return $form->post_title;
	}
	
	static function get_url($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}
		
		$prefix = post_type($form);
		
		$x = get_post_meta($form->ID, $prefix.'_url', True);
		$y = wp_get_attachment_url(get_post_meta($form->ID, $prefix.'_file', True));
		
		if (!$x and !$y){
			return '#';
		}
		
		return ($x) ? $x : $y;
	}
	
	
	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 **/
	public function objectsToHTML($objects, $css_classes){
		if (count($objects) < 1){ return '';}
		
		$class_name = get_custom_post_type($objects[0]->post_type);
		$class      = new $class_name;
		
		ob_start();
		?>
		<ul class="unstyled <?php if($css_classes):?><?=$css_classes?><?php else:?><?=$class->options('name')?>-list<?php endif;?>">
			<?php foreach($objects as $o):?>
			<li class="document <?=$class_name::get_document_application($o)?>">
				<?=$class->toHTML($o)?>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	
	
	/**
	 * Outputs this item in HTML.  Can be overridden for descendants.
	 **/
	public function toHTML($object){
		$title = Document::get_title($object);
		$url   = Document::get_url($object);
		$html = "<a href='{$url}'>{$title}</a>";
		return $html;
	}
}


class Video extends CustomPostType{
	public 
		$name           = 'video',
		$plural_name    = 'Videos',
		$singular_name  = 'Video',
		$add_new_item   = 'Add New Video',
		$edit_item      = 'Edit Video',
		$new_item       = 'New Video',
		$public         = True,
		$use_editor     = False,
		$use_thumbnails = True,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True;
	
	public function get_player_html($video){
		return sc_video(array('video' => $video));
	}
	
	public function metabox(){
		$metabox = parent::metabox();
		
		$metabox['title']   = 'Videos on Media Page';
		$metabox['helptxt'] = 'Video icon will be resized to width 210px, height 118px.';
		return $metabox;
	}
	
	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'URL',
				'desc' => 'YouTube URL pointing to video.<br>  Example: http://www.youtube.com/watch?v=IrSeMg7iPbM',
				'id'   => $prefix.'url',
				'type' => 'text',
				'std'  => ''
			),
			array(
				'name' => 'Video Description',
				'desc' => 'Short description of the video.',
				'id'   => $prefix.'description',
				'type' => 'textarea',
				'std'  => ''
			),
			array(
				'name' => 'Shortcode',
				'desc' => 'To include this video in other posts, use the following shortcode:',
				'id'   => 'video_shortcode',
				'type' => 'shortcode',
				'value' => '[video name="TITLE"]',
			),
		);
	}
}


class Publication extends CustomPostType{
	public 
		$name           = 'publication',
		$plural_name    = 'Publications',
		$singular_name  = 'Publication',
		$add_new_item   = 'Add New Publication',
		$edit_item      = 'Edit Publication',
		$new_item       = 'New Publication',
		$public         = True,
		$use_editor     = False,
		$use_thumbnails = True,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True;
	
	public function toHTML($pub){
		return sc_publication(array('pub' => $pub));
	}
	
	public function metabox(){
		$metabox = parent::metabox();
		
		$metabox['title']   = 'Publications on Media Page';
		$metabox['helptxt'] = 'Publication cover icon will be resized to width 153px, height 198px.';
		return $metabox;
	}
	
	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name'  => 'Publication URL',
				'desc' => 'Example: <span style="font-family:monospace;font-weight:bold;color:#21759B;">http://publications.smca.ucf.edu/admissions/viewbook.html</span>',
				'id'   => $prefix.'url',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name' => 'Shortcode',
				'desc' => 'To include this publication in other posts, use the following shortcode: <input disabled="disabled" type="text" value="[publication name=]" />',
				'id'   => 'publication_shortcode',
				'type' => 'help',
				'value' => '[publication name="TITLE"]',
			),
		);
	}
}

class Page extends CustomPostType {
	public
		$name           = 'page',
		$plural_name    = 'Pages',
		$singular_name  = 'Page',
		$add_new_item   = 'Add New Page',
		$edit_item      = 'Edit Page',
		$new_item       = 'New Page',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = False,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True,
		$built_in       = True;

	public function fields() {
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'Hide Lower Section',
				'desc' => 'This section normally contains the Flickr, News and Events widgets. The footer will not be hidden',
				'id'   => $prefix.'hide_fold',
				'type' => 'checkbox',
			)
		);
	}
}

class FrontPageCenterpiece extends CustomPostType {
	public
		$name           = 'centerpiece',
		$plural_name    = 'Front Page Centerpieces',
		$singular_name  = 'Front Page Centerpiece',
		$add_new_item   = 'Add New Front Page Centerpiece',
		$edit_item      = 'Edit Front Page Centerpiece',
		$new_item       = 'New Front Page Centerpiece',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = True,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True,
		$use_shortcode  = False;

	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'Front Page Centerpiece',
				'desc' => 'To include this publication in other posts, use the following shortcode: <input disabled="disabled" type="text" value="[publication name=]" />',
				'id'   => $prefix.'help',
				'type' => 'help',
				'value' => '[publication name="TITLE"]',
			),
			array(
				'name' => 'Position',
				'desc' => 'Where on the centerpiece image the content will be displayed',
				'id'   => $prefix.'position',
				'type' => 'select',
				'options' => array('Top Banner' => 'top', 'Left' => 'left', 'Center' => 'center', 'Bottom Banner' => 'bottom')
			)
		);
	}
}

class Program extends CustomPostType {
	public
		$name           = 'program',
		$plural_name    = 'Programs',
		$singular_name  = 'Program',
		$add_new_item   = 'Add New Program',
		$edit_item      = 'Edit Front Program',
		$new_item       = 'New Front Program',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = True,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True,
		$use_shortcode  = False;

	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name'  => 'Feature Text',
				'desc'  => 'The content of this field will be used in conjunction with the title of this post to form the content for the front page feature of this program.',
				'id'    => $prefix.'feature_text',
				'type'  => 'textarea',
			),
		);
	}
}

/**
 * Describes a staff member
 *
 * @author Chris Conover
 **/
class Person extends CustomPostType
{
	/*
	The following query will pre-populate the person_orderby_name
	meta field with a guess of the last name extracted from the post title.
	
	>>>BE SURE TO REPLACE wp_<number>_... WITH THE APPROPRIATE SITE ID<<<
	
	INSERT INTO wp_29_postmeta(post_id, meta_key, meta_value) 
	(	SELECT	id AS post_id, 
						'person_orderby_name' AS meta_key, 
						REVERSE(SUBSTR(REVERSE(post_title), 1, LOCATE(' ', REVERSE(post_title)))) AS meta_value
		FROM		wp_29_posts AS posts
		WHERE		post_type = 'person' AND
						(	SELECT meta_id 
							FROM wp_29_postmeta 
							WHERE post_id = posts.id AND
										meta_key = 'person_orderby_name') IS NULL)
	*/

	public
		$name           = 'person',
		$plural_name    = 'People',
		$singular_name  = 'Person',
		$add_new_item   = 'Add Person',
		$edit_item      = 'Edit Person',
		$new_item       = 'New Person',
		$public         = True,
		$use_shortcode  = True,
		$use_metabox    = True,
		$use_thumbnails = True,
		$use_order      = True,
		$taxonomies     = array('org_groups', 'category'),

		$default_orderby  = 'meta_value',
		$default_meta_key = 'person_orderby_name';

		public function fields(){
			$fields = array(
				array(
					'name'    => __('Title Prefix'),
					'desc'    => _('Example: Dr.'),
					'id'      => $this->options('name').'_title_prefix',
					'type'    => 'text',
				),
				array(
					'name'    => __('Title Suffix'),
					'desc'    => __('Be sure to include leading comma or space if neccessary.<br />Example:, Ed.D'),
					'id'      => $this->options('name').'_title_suffix',
					'type'    => 'text',
				),
				array(
					'name'    => __('Job Title'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_jobtitle',
					'type'    => 'text',
				),
				array(
					'name'    => __('Phone Number'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_phones',
					'type'    => 'text',
				),
				array(
					'name'    => __('Email Address'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_email',
					'type'    => 'text',
				),
				array(
					'name'    => __('Office Location URL'),
					'desc'    => __('A URL of a location on the Online Campus Map (e.g. http://map.ucf.edu/?show=1)'),
					'id'      => $this->options('name').'_office_location_url',
					'type'    => 'text',
				),
				array(
					'name'    => __('Office Location Text'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_office_location_text',
					'type'    => 'text',
				),
				array(
					'name'    => __('Office Hours'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_office_hours',
					'type'    => 'textarea',
				),
				array(
					'name'    => __('C.V.'),
					'desc'    => __(''),
					'id'      => $this->options('name').'_cv',
					'type'    => 'file',
				),
				array(
					'name'    => __('Order By Name'),
					'desc'    => __('Name used for sorting. Leaving this field blank may lead to an unexpected sort order.'),
					'id'      => $this->options('name').'_orderby_name',
					'type'    => 'text',
				),
			);
			return $fields;
		}

	public function get_objects($options=array()){
		$options['order']    = 'ASC';
		$options['orderby']  = 'person_orderby_name';
		$options['meta_key'] = 'person_orderby_name';
		return parent::get_objects($options);
	}

	public static function get_name($person) {
		$prefix = get_post_meta($person->ID, 'person_title_prefix', True);
		$suffix = get_post_meta($person->ID, 'person_title_suffix', True);
		$name = $person->post_title;
		return $prefix.' '.$name.$suffix;
	}

	public static function get_news_posts($person, $limit = null) {
		$posts      = get_posts(array('numberposts' =>-1, 'post_type' => 'post'));
		$news_posts = array();
		$count = 1;
		foreach($posts as $post) {
			$people = get_post_meta($post->ID, 'post_person', True);
			if($people != '' && ($people = unserialize($people)) !== False && is_array($people)) {
				if(in_array($person->ID, $people)) {
					$news_posts[] = $post;
					if( (is_null($limit) && $count == 4) || (is_numeric($limit) && $count == $limit)) {
						break;
					}
					$count++;
				}
			}
		}
		return $news_posts;
	}

	public function objectsToHTML($people, $css_classes) {
		ob_start();?>
		<div class="row">
			<div class="span12">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col" class="name">Name</th>
							<th scope="col" class="job_title">Title</th>
							<th scope="col" class="phones">Phone(s)</th>
							<th scope="col" class="email">E-Mail</th>
						</tr>
					</thead>
					<tbody>
				<?
				foreach($people as $person) { 
					$email = get_post_meta($person->ID, 'person_email', True); 
					$link = ($person->post_content == '') ? False : True; ?>
						<tr>
							<td class="name">
								<a href="<?=get_permalink($person->ID)?>">
									<?=$this->get_name($person)?>
								</a>
							</td>
							<td class="job_title">
								<a href="<?=get_permalink($person->ID)?>">
									<?=get_post_meta($person->ID, 'person_jobtitle', True)?>
								</a>
							</td> 
							<td class="phones">
								<a href="<?=get_permalink($person->ID)?>">
									<?=get_post_meta($person->ID, 'person_phones', True)?>
								</a>
							</td>
							<td class="email">
								<?=(($email != '') ? '<a href="mailto:'.$email.'">'.$email.'</a>' : '')?>
							</td>
						</tr>
				<? } ?>
				</tbody>
			</table> 
		</div>
	</div><?
	return ob_get_clean();
	}
} // END class 

/**
 * Override base post type to poeple taxonomy
 *
 * @author Chris Conover
 **/
class Post extends CustomPostType
{
	public 
		$name           = 'post',
		$plural_name    = 'Posts',
		$singular_name  = 'Post',
		$add_new_item   = 'Add New Post',
		$edit_item      = 'Edit Post',
		$new_item       = 'New Post',
		$public         = True,
		$use_thumbnails = True,
		$use_editor     = True,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True,
		$use_shortcode  = True,
		$taxonomies     = Array('people', 'post_tag', 'category'),
		$built_in       = True,

		$default_orderby = 'date',
		$default_order   = 'DESC' ;

	public function fields() {

		# Person options
		$person_options = array();
		foreach(get_posts(array('numberposts'=>-1, 'post_type'=>'person', 'orderby'=>'post_title')) as $person) {
			$person_options[Person::get_name($person)] = $person->ID;
		}

		return array(
			array(
				'name'    => __('Person'),
				'desc'    => 'Optional. Posts associated with a person will be displayed on their profile page.<br />Hold the control (windows) or command (mac) key and click to select mulitple.',
				'id'      => $this->options('name').'_person',
				'type'    => 'multiselect',
				'options' => $person_options
			),
			array(
				'name'    => __('Quote'),
				'desc'    => 'Optional. A particularly good quote that, if provided, will appear on the right column of the article page.<br />Enclosing quotes will be automatically added.',
				'id'      => $this->options('name').'_quote',
				'type'    => 'textarea',
			)
		);
	}

	public function objectsToHTML($objects, $css_classes) {
		global $post;

		# Sort the objects into 3 columns
		$column_1 = array();
		$column_2 = array();
		$column_3 = array();

		$index = 1;
		foreach($objects as $object) {
			switch($index) {
				case 1:
					$column_1[] = $object;
					$index++;
					break;
				case 2:
					$column_2[] = $object;
					$index++;
					break;
				case 3:
					$column_3[] = $object;
					$index = 1;
					break;
			}
		}

		ob_start();?>
		<div class="row">
			<? foreach(array($column_1, $column_2, $column_3) as $column) { ?>
			<div class="span4">
				<? 
				foreach($column as $post) { setup_postdata($post); 
					$featured_image_url = get_featured_image_url($post);
					$category_links     = array();
					foreach(wp_get_post_categories($post->ID) as $id) {
						# Use get_term_by because other functions have stupid
						# return values if the term doesn't exist
						$category = get_term_by('id', $id, 'category');
						if($category !== False) {
							$category_links[] = '<a href="'.get_category_link($id).'">'.esc_html($category->name).'</a>';
						}
					}
				?>
				<div class="row">
					<div class="span4 post-item">
						<a href="<?=get_permalink($post->ID);?>">
							<? if($featured_image_url !== False) {?>
							<img src="<?=$featured_image_url?>" />
							<? } ?>
							<h4><?=get_the_title();?></h4>
							<p><?=get_the_excerpt();?></p>
							<div class="when">Posted: <?=the_time('F j, Y')?></div>
						</a>
						<div class="under">
							Filed Under: <?=implode(', ', $category_links);?>
						</div>
					</div>
				</div>
				<? } ?>
			</div>
			<? } ?>
		</div>
		<?
		wp_reset_postdata();
		return ob_get_clean();
	}
}

?>