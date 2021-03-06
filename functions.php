<?php

# Set theme constants
#define('DEBUG', True);                  # Always on
#define('DEBUG', False);                 # Always off
define('DEBUG', isset($_GET['debug'])); # Enable via get parameter
define('THEME_URL', get_bloginfo('stylesheet_directory'));
define('THEME_ADMIN_URL', get_admin_url());
define('THEME_DIR', get_stylesheet_directory());
define('THEME_INCLUDES_DIR', THEME_DIR.'/includes');
define('THEME_STATIC_URL', THEME_URL.'/static');
define('THEME_IMG_URL', THEME_STATIC_URL.'/img');
define('THEME_JS_URL', THEME_STATIC_URL.'/js');
define('THEME_CSS_URL', THEME_STATIC_URL.'/css');
define('THEME_OPTIONS_GROUP', 'settings');
define('THEME_OPTIONS_NAME', 'theme');
define('THEME_OPTIONS_PAGE_TITLE', 'Theme Options');

$theme_options = get_option(THEME_OPTIONS_NAME);
define('GA_ACCOUNT', $theme_options['ga_account']);
define('CB_UID', $theme_options['cb_uid']);
define('CB_DOMAIN', $theme_options['cb_domain']);

require_once('functions-base.php');     # Base theme functions
require_once('custom-taxonomies.php');  # Where per theme taxonomies are defined
require_once('custom-post-types.php');  # Where per theme post types are defined
require_once('shortcodes.php');         # Per theme shortcodes
require_once('functions-admin.php');    # Admin/login functions


/**
 * Set config values including meta tags, registered custom post types, styles,
 * scripts, and any other statically defined assets that belong in the Config
 * object.
 **/
Config::$custom_post_types = array(
	'FrontPageCenterpiece',
	'Program',
	'Person',
	'Post',
	'Document'
);

Config::$custom_taxonomies = array(
	'OrganizationalGroups',
	'DocumentGroups'
);


Config::$body_classes = array('default',);

/**
 * Configure theme settings, see abstract class Field's descendants for
 * available fields. -- functions-base.php
 **/
Config::$theme_settings = array(
	'Analytics' => array(
		new TextField(array(
			'name'        => 'Google WebMaster Verification',
			'id'          => THEME_OPTIONS_NAME.'[gw_verify]',
			'description' => 'Example: <em>9Wsa3fspoaoRE8zx8COo48-GCMdi5Kd-1qFpQTTXSIw</em>',
			'default'     => null,
			'value'       => $theme_options['gw_verify'],
		)),
		new TextField(array(
			'name'        => 'Yahoo! Site Explorer',
			'id'          => THEME_OPTIONS_NAME.'[yw_verify]',
			'description' => 'Example: <em>3236dee82aabe064</em>',
			'default'     => null,
			'value'       => $theme_options['yw_verify'],
		)),
		new TextField(array(
			'name'        => 'Bing Webmaster Center',
			'id'          => THEME_OPTIONS_NAME.'[bw_verify]',
			'description' => 'Example: <em>12C1203B5086AECE94EB3A3D9830B2E</em>',
			'default'     => null,
			'value'       => $theme_options['bw_verify'],
		)),
		new TextField(array(
			'name'        => 'Google Analytics Account',
			'id'          => THEME_OPTIONS_NAME.'[ga_account]',
			'description' => 'Example: <em>UA-9876543-21</em>. Leave blank for development.',
			'default'     => null,
			'value'       => $theme_options['ga_account'],
		)),
		new TextField(array(
			'name'        => 'Chartbeat UID',
			'id'          => THEME_OPTIONS_NAME.'[cb_uid]',
			'description' => 'Example: <em>1842</em>',
			'default'     => null,
			'value'       => $theme_options['cb_uid'],
		)),
		new TextField(array(
			'name'        => 'Chartbeat Domain',
			'id'          => THEME_OPTIONS_NAME.'[cb_domain]',
			'description' => 'Example: <em>some.domain.com</em>',
			'default'     => null,
			'value'       => $theme_options['cb_domain'],
		)),
	),
	'Events' => array(
		new RadioField(array(
			'name'        => 'Enable Events Below the Fold',
			'id'          => THEME_OPTIONS_NAME.'[enable_events]',
			'description' => 'Display events in the bottom page content, appearing on most pages.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => $theme_options['enable_events'],
		)),
		new RadioField(array(
			'name'        => 'Enable Events on Search Page',
			'id'          => THEME_OPTIONS_NAME.'[enable_search_events]',
			'description' => 'Display events on the search results page.',
			'value'       => $theme_options['enable_search_events'],
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
		)),
		new SelectField(array(
			'name'        => 'Events Max Items',
			'id'          => THEME_OPTIONS_NAME.'[events_max_items]',
			'description' => 'Maximum number of events to display whenever outputting event information.',
			'value'       => $theme_options['events_max_items'],
			'default'     => 4,
			'choices'     => array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
			),
		)),
		new TextField(array(
			'name'        => 'Events Calendar URL',
			'id'          => THEME_OPTIONS_NAME.'[events_url]',
			'description' => 'Base URL for the calendar you wish to use. Example: <em>http://events.ucf.edu/mycalendar</em>',
			'value'       => $theme_options['events_url'],
			'default'     => 'http://events.ucf.edu',
		)),
	),
	'News' => array(
		new RadioField(array(
			'name'        => 'Enable News Below the Fold',
			'id'          => THEME_OPTIONS_NAME.'[enable_news]',
			'description' => 'Display UCF Today news in the bottom page content, appearing on most pages.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => $theme_options['enable_news'],
		)),
		new SelectField(array(
			'name'        => 'News Max Items',
			'id'          => THEME_OPTIONS_NAME.'[news_max_items]',
			'description' => 'Maximum number of articles to display when outputting news information.',
			'value'       => $theme_options['news_max_items'],
			'default'     => 2,
			'choices'     => array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
			),
		)),
		new TextField(array(
			'name'        => 'News Feed',
			'id'          => THEME_OPTIONS_NAME.'[news_url]',
			'description' => 'Use the following URL for the news RSS feed <br />Example: <em>http://today.ucf.edu/feed/</em>',
			'value'       => $theme_options['news_url'],
			'default'     => 'http://today.ucf.edu/feed/',
		)),
	),
	'Search' => array(
		new RadioField(array(
			'name'        => 'Enable Google Search',
			'id'          => THEME_OPTIONS_NAME.'[enable_google]',
			'description' => 'Enable to use the google search appliance to power the search functionality.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => $theme_options['enable_google'],
	    )),
		new TextField(array(
			'name'        => 'Search Domain',
			'id'          => THEME_OPTIONS_NAME.'[search_domain]',
			'description' => 'Domain to use for the built-in google search.  Useful for development or if the site needs to search a domain other than the one it occupies. Example: <em>some.domain.com</em>',
			'default'     => null,
			'value'       => $theme_options['search_domain'],
		)),
		new TextField(array(
			'name'        => 'Search Results Per Page',
			'id'          => THEME_OPTIONS_NAME.'[search_per_page]',
			'description' => 'Number of search results to show per page of results',
			'default'     => 10,
			'value'       => $theme_options['search_per_page'],
		)),
	),
	'Site' => array(
		new TextareaField(array(
			'name'        => 'Organization Name',
			'id'          => THEME_OPTIONS_NAME.'[organization_name]',
			'description' => 'Your organization\'s name',
			'value'       => $theme_options['organization_name'],
		)),
		new TextareaField(array(
			'name'        => 'Mailing Address',
			'id'          => THEME_OPTIONS_NAME.'[mailing_address]',
			'description' => 'Your organization\'s mailing address',
			'value'       => $theme_options['mailing_address'],
		)),
		new TextareaField(array(
			'name'        => 'Contact Information',
			'id'          => THEME_OPTIONS_NAME.'[contact_information]',
			'description' => 'Your organization\'s contact information.',
			'value'       => $theme_options['contact_information'],
		)),
		new TextField(array(
			'name'        => 'Donate Link Text',
			'id'          => THEME_OPTIONS_NAME.'[donate_link_text]',
			'description' => 'The text of the donate link in the page footer.',
			'default'     => 'Click here to donate now.',
			'value'       => $theme_options['donate_link_text'],
		)),
		new TextField(array(
			'name'        => 'Donate Link URL',
			'id'          => THEME_OPTIONS_NAME.'[donate_link_url]',
			'description' => 'The URL of the donate link in the page footer.',
			'default'     => 'http://www.ucffoundation.org',
			'value'       => $theme_options['donate_link_url'],
		)),
		new TextField(array(
			'name'        => 'Pagination Page Size',
			'id'          => THEME_OPTIONS_NAME.'[pagination_page_size]',
			'description' => 'How many items will appear on each paginated page.',
			'default'     => '9',
			'value'       => $theme_options['pagination_page_size'],
		)),
	),
	'Social' => array(
		new RadioField(array(
			'name'        => 'Enable OpenGraph',
			'id'          => THEME_OPTIONS_NAME.'[enable_og]',
			'description' => 'Turn on the opengraph meta information used by Facebook.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => $theme_options['enable_og'],
	    )),
		new TextField(array(
			'name'        => 'Facebook Admins',
			'id'          => THEME_OPTIONS_NAME.'[fb_admins]',
			'description' => 'Comma seperated facebook usernames or user ids of those responsible for administrating any facebook pages created from pages on this site. Example: <em>592952074, abe.lincoln</em>',
			'default'     => null,
			'value'       => $theme_options['fb_admins'],
		)),
		new TextField(array(
			'name'        => 'Facebook URL',
			'id'          => THEME_OPTIONS_NAME.'[facebook_url]',
			'description' => 'URL to the Facebook page you would like to direct visitors to.',
			'default'     => 'http://www.facebook.com/ucf',
			'value'       => $theme_options['facebook_url'],
		)),
		new TextField(array(
			'name'        => 'Twitter URL',
			'id'          => THEME_OPTIONS_NAME.'[twitter_url]',
			'description' => 'URL to the Twitter user account you would like to direct visitors to.',
			'default'     => 'http://www.twitter.com/ucf',
			'value'       => $theme_options['twitter_url'],
		)),
		new TextField(array(
			'name'        => 'LinkedIn URL',
			'id'          => THEME_OPTIONS_NAME.'[linkedin_url]',
			'description' => 'URL to the LinkedIn user account you would like to direct visitors to.>',
			'default'     => 'http://www.linkedin.com/company/university-of-central-florida',
			'value'       => $theme_options['linkedin_url'],
		)),
		new TextField(array(
			'name'        => 'Youtube URL',
			'id'          => THEME_OPTIONS_NAME.'[youtube_url]',
			'description' => 'URL to the Youtube user account you would like to direct visitors to.',
			'default'     => 'http://www.youtube.com/user/UCF',
			'value'       => $theme_options['youtube_url'],
		)),
		new TextField(array(
			'name'        => 'Foursquare URL',
			'id'          => THEME_OPTIONS_NAME.'[foursquare_url]',
			'description' => 'URL to the Foursquare user account you would like to direct visitors to.',
			'default'     => 'http://www.foursquare.com/',
			'value'       => $theme_options['foursquare_url'],
		)),
		/*
		new RadioField(array(
			'name'        => 'Enable Flickr',
			'id'          => THEME_OPTIONS_NAME.'[enable_flickr]',
			'description' => 'Automatically display flickr images throughout the site',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => $theme_options['enable_flickr'],
		)),
		new TextField(array(
			'name'        => 'Flickr Photostream ID',
			'id'          => THEME_OPTIONS_NAME.'[flickr_id]',
			'description' => 'ID of the flickr photostream you would like to show pictures from.  Example: <em>65412398@N05</em>',
			'default'     => '36226710@N08',
			'value'       => $theme_options['flickr_id'],
		)),
		new SelectField(array(
			'name'        => 'Flickr Max Images',
			'id'          => THEME_OPTIONS_NAME.'[flickr_max_items]',
			'description' => 'Maximum number of flickr images to display',
			'value'       => $theme_options['flickr_max_items'],
			'default'     => 12,
			'choices'     => array(
				'6'  => 6,
				'12' => 12,
				'18' => 18,
			),
		)),
		*/
	),
);

Config::$links = array(
	array('rel' => 'shortcut icon', 'href' => THEME_IMG_URL.'/favicon.ico',),
	array('rel' => 'alternate', 'type' => 'application/rss+xml', 'href' => get_bloginfo('rss_url'),),
);

Config::$styles = array(
	array('admin' => True, 'src' => THEME_CSS_URL.'/admin.css',),
	'http://universityheader.ucf.edu/bar/css/bar.css',
	THEME_STATIC_URL.'/bootstrap/build/css/bootstrap.css',
	THEME_CSS_URL.'/webcom-base.css',
	get_bloginfo('stylesheet_url'),
);


Config::$scripts = array(
	array('admin' => True, 'src' => THEME_JS_URL.'/admin.js',),
	'http://universityheader.ucf.edu/bar/js/university-header.js',
	array('name' => 'jquery', 'src' => 'http://code.jquery.com/jquery-1.7.1.min.js',),
	THEME_STATIC_URL.'/bootstrap/build/js/bootstrap.js',
	//THEME_JS_URL.'/jquery-extras.js',
	array('name' => 'base-script',  'src' => THEME_JS_URL.'/webcom-base.js',),
	array('name' => 'theme-script', 'src' => THEME_JS_URL.'/script.js',),
);

Config::$metas = array(
	array('charset' => 'utf-8',),
);
if ($theme_options['gw_verify']){
	Config::$metas[] = array(
		'name'    => 'google-site-verification',
		'content' => htmlentities($theme_options['gw_verify']),
	);
}
if ($theme_options['yw_verify']){
	Config::$metas[] = array(
		'name'    => 'y_key',
		'content' => htmlentities($theme_options['yw_verify']),
	);
}
if ($theme_options['bw_verify']){
	Config::$metas[] = array(
		'name'    => 'msvalidate.01',
		'content' => htmlentities($theme_options['bw_verify']),
	);
}

/**
 * Truncates a string based on word count
 *
 * @return string
 * @author Chris Conover
 **/
function truncate($string, $word_count=30) {
	$parts = explode(' ', $string, $word_count);
	return implode(' ', array_slice($parts, 0, count($parts) - 1)).'...';
}

/**
 * Get menu and format for bootstrap
 *
 * @return string
 * @author Chris Conover
 **/
function get_bootstrap_menu($name, $classes=null, $id=null, $callback=null) {
	$locations = get_nav_menu_locations();
	$menu      = @$locations[$name];

	$items           = wp_get_nav_menu_items($menu);
	$top_level_items = array();

	foreach($items as $index => $item) {
		if($item->menu_item_parent != '0') {
			foreach($items as $_index => $parent_item) {
				if($item->menu_item_parent == $parent_item->ID) {
					if(isset($parent_item->children)) {
						$parent_item->children[] = $item;
					} else {
						$parent_item->children = array($item);
					}
				}
			}
		} else {
			$top_level_items[] = $item;
		}
	}
	ob_start();?>
	<ul class="nav nav-pills pull-right<?=!is_null($classes) ? ' '.$classes : ''?>" <?=!is_null($id) ? ' id="'.$id.'"' : ''?>>
		<? foreach($top_level_items as $item) {
			if(count($item->children) > 0) { ?>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?=$item->title?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<? foreach($item->children as $child_item) {?>
						<li><a href="<?=$child_item->url?>"><?=$child_item->title?></a></li>
						<? } ?>
					</ul>
				</li>
			<? } else { ?>
				<li><a href="<?=$item->url?>"><?=$item->title?></a></li>
			<? } ?>
		<? } ?>
	</ul>
	<?
	return ob_get_clean();
}

/**
 * Return featured image url if it exists, otherwise false
 *
 * @return string/false
 * @author Chris Conover
 **/
function get_featured_image_url($post) {
	if( ($thumbnail = get_featured_image($post)) !== False && ($image = wp_get_attachment_image_src($thumbnail->ID, 'full')) !== False) {
		return $image[0];
	}
	return False;
}

/**
 * Return featured image post
 *
 * @return object/false
 * @author Chris Conover
 **/
function get_featured_image($post) {
	if(has_post_thumbnail($post->ID) && ($thumbnail_id = get_post_thumbnail_id($post->ID)) !== False && ($post = get_post($thumbnail_id)) !== False) {
		return $post;
	}
	return False;
}



/**
 * Remove some unused menus in the dashboard.
 *
 * @author Chris Conover
 **/
function remove_menus() {
	global $menu;

	$removals = array('Links', 'Comments');

	reset($menu);
	while(next($menu)) {
		$item = current($menu);
		if(isset($item[0]) && $item[0] != '') {
			foreach($removals as $removal) {
				// Comments needs special handling for some reason
				if($removal == 'Comments' && stripos($item[0], 'Comments') === 0) {
					unset($menu[key($menu)]);
				} else if($item[0] == $removal) {
					unset($menu[key($menu)]);
				}
			}
		}
	}
}
add_action('admin_menu', 'remove_menus');


/**
 * Returns the object of an additional image attached to a post
 * if the image is not the not designated as the featured image. Otherwise false
 *
 * @return string/false
 * @author Chris Conover
 **/
function get_additional_image($post) {
	if( ($featured_image = get_featured_image($post)) !== False ) {
		$attachments = get_posts(array('numberposts'=>-1, 'post_type'=>'attachment', 'post_parent'=>$post->ID));

		foreach($attachments as $attachment) {
			if($attachment->ID != $featured_image->ID && strpos($attachment->post_mime_type, 'image/') == 0) {
				return $attachment;
			}
		}
	}
	return False;
}

/**
 *
 * Get Post post type archive years 
 *
 * @return array
 * @author Chris Conover
 **/
function get_archive_years(){

	#Fetch post dates for objects in this category
	global $wpdb;

	$sql  = "
		SELECT DISTINCT post.post_date
		FROM $wpdb->posts post
		WHERE
			post.post_type = 'post'
			AND post.post_status = 'publish'";
	$rows = $wpdb->get_results($sql);
	
	#Find unique years and return
	$years = array();
	foreach ($rows as $row){
		$date = $row->post_date;
		$year = date("Y", strtotime($date));
		$years[] = $year;
	}
	if (count($years)){
		rsort($years);
		$years = array_unique($years);
		return $years;
	}else{
		return array();
	}
}

/**
 * Returns a theme option value or NULL if it doesn't exist
 *
 * @return string/null
 * @author Chris Conover
 **/
function get_theme_option($key) {
	global $theme_options;
	return isset($theme_options[$key]) ? $theme_options[$key] : NULL;
}

/**
 * Returns pagination page size
 *
 * @return integer
 * @author Chris Conover
 **/
function get_pagination_page_size() {
	$page_size = get_theme_option('pagination_page_size');

	if(!is_numeric($page_size) || (int)$page_size < 1) {
		$page_size = 9;
	} else {
		$page_size = (int)$page_size;
	}
	return $page_size;
}

/**
 * Adds Featured Images to the site's own RSS feed for News
 * @author Jonathan Hendricker
 **/
function featuredtoRSS($content) {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ){
		$content = '' . get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'style' => 'float:left; margin:0 15px 15px 0;' ) ) . '' . $content;
	}
	return $content;
}
 
add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');
?>