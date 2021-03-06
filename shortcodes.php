<?php


/**
 * Create a javascript slideshow of each top level element in the
 * shortcode.  All attributes are optional, but may default to less than ideal
 * values.  Available attributes:
 * 
 * height     => css height of the outputted slideshow, ex. height="100px"
 * width      => css width of the outputted slideshow, ex. width="100%"
 * transition => length of transition in milliseconds, ex. transition="1000"
 * cycle      => length of each cycle in milliseconds, ex cycle="5000"
 * animation  => The animation type, one of: 'slide' or 'fade'
 *
 * Example:
 * [slideshow height="500px" transition="500" cycle="2000"]
 * <img src="http://some.image.com" .../>
 * <div class="robots">Robots are coming!</div>
 * <p>I'm a slide!</p>
 * [/slideshow]
 **/
function sc_slideshow($attr, $content=null){
	$content = cleanup(str_replace('<br />', '', $content));
	$content = DOMDocument::loadHTML($content);
	$html    = $content->childNodes->item(1);
	$body    = $html->childNodes->item(0);
	$content = $body->childNodes;
	
	# Find top level elements and add appropriate class
	$items = array();
	foreach($content as $item){
		if ($item->nodeName != '#text'){
			$classes   = explode(' ', $item->getAttribute('class'));
			$classes[] = 'slide';
			$item->setAttribute('class', implode(' ', $classes));
			$items[] = $item->ownerDocument->saveXML($item);
		}
	}
	
	$animation = ($attr['animation']) ? $attr['animation'] : 'slide';
	$height    = ($attr['height']) ? $attr['height'] : '100px';
	$width     = ($attr['width']) ? $attr['width'] : '100%';
	$tran_len  = ($attr['transition']) ? $attr['transition'] : 1000;
	$cycle_len = ($attr['cycle']) ? $attr['cycle'] : 5000;
	
	ob_start();
	?>
	<div 
		class="slideshow <?=$animation?>"
		data-tranlen="<?=$tran_len?>"
		data-cyclelen="<?=$cycle_len?>"
		style="height: <?=$height?>; width: <?=$width?>;"
	>
		<?php foreach($items as $item):?>
		<?=$item?>
		<?php endforeach;?>
	</div>
	<?php
	$html = ob_get_clean();
	
	return $html;
}
add_shortcode('slideshow', 'sc_slideshow');


function sc_search_form() {
	ob_start();
	?>
	<div class="search">
		<?get_search_form()?>
	</div>
	<?
	return ob_get_clean();
}
add_shortcode('search_form', 'sc_search_form');

/**
 * Person-list with a list format including pictures
 * instead of a table format. If the person has no profile
 * picture, a default piture will be outputted instead.
 * 
 * @author Chris Conover
 * @return string
 **/
function sc_person_picture_list($attrs) {
	$attrs['type'] = 'person';
	$row_size      = isset($attrs['row-size']) ? (int)$attrs['row-size'] : 5;
	$people        = sc_object_list($attrs, array('objects_only' => True));

	if(count($people) == 0) return '';

	ob_start();
	?><div class="person-picture-list"><?
	$count = 0;
	foreach($people as $person) {
		$image_url = get_featured_image_url($person);
		$link      = ($person->post_content != '') ? True : False;
		if( ($count % $row_size) == 0) {
			if($count > 0) {
				?></div><?
			}
			?><div class="row"><?
		}
		?>
		<div class="span2">
			<a href="<?=get_permalink($person->ID)?>">
				<img src="<?=$image_url ? $image_url : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg'?>" />
				<div class="name"><?=Person::get_name($person)?></div>
				<div class="title"><?=get_post_meta($person->ID, 'person_jobtitle', True)?></div>
			</a>
		</div>
		<?
		$count++;
	}
	?>	</div> 
	</div>
	<?
	return ob_get_clean();
}
add_shortcode('person-picture-list', 'sc_person_picture_list');

/**
 * Wraps the specified content in the shadow-well CSS class.
 * width - optional - in columns. default to 12
 * wrap - optional - include row wrapper
 * gold - optional - add gold CSS class
 *
 * @author Chris Conover
 * @return string
 **/
function sc_shadow_well($attrs, $content) {
	$width = isset($attrs['width']) ? $attrs['width'] : '12';
	$wrap  = isset($attrs['wrap']) ? (bool)$attrs['wrap'] : True;
	$gold  = isset($attrs['gold']) ? ' gold' : '';
	if($wrap) {
		return sprintf('<div class="row"><div class="span%s"><div class="shadow-well%s">%s</div></div></div>', $width, $gold, do_shortcode($content));
	} else {
		return sprintf('<div class="span%s"><div class="shadow-well%s">%s</div></div>', $width, $gold, do_shortcode($content));
	}
}
add_shortcode('shadow-well', 'sc_shadow_well');

/**
 * Wrap the specified content in the titled-section CSS class.
 * title - required - title of the section
 * width - optional - default 
 * The CSS id of the wrapping div will be the sluggified version of 
 * the title.
 *
 * @author Chris Conover
 * @return string
**/
function sc_titled_section($attrs, $content) {
	if(!isset($attrs['title']) || $attrs['title'] == '') {
		print 'The `title` attribute is required for the `program-section` shortcode.';
	} else {
		$width = isset($attrs['width']) ? $attrs['width'] : '12';
		$title = $attrs['title'];
		$id    = sanitize_title($title);

		return sprintf('
			<div class="titled-section" id="%s">
				<div class="row">
					<div class="span%s">
						<h3 class="title"><span>%s</span></h3>
					</div>
				</div>
				<div class="row">
					<div class="span%s">
						%s
					</div>
				</div>
			</div>', $id, $width, $title, $width, do_shortcode($content));
	}
}
add_shortcode('titled-section', 'sc_titled_section');

/**
 * List of categories split up into a specified number of columns
 * columns - number of columns. default is 3
 * width - width of each column. default is 2
 * @author Chris Conover
 * @return string
**/
function sc_category_list($attrs, $content) {
	if(isset($attrs['columns']) && is_numeric($attrs['columns']) && $attrs['columns'] > 0) {
		$number_columns = $attrs['columns'];
	} else {
		$number_columns = 3;
	}

	if(isset($attrs['width']) && is_numeric($attrs['width']) && $attrs['width'] > 0) {
		$column_width = $attrs['width'];
	} else {
		$column_width = 2;
	}

	$categories = get_categories();

	$columns = array();
	$count   = 1;
	foreach($categories as $category) {
		$columns[$count][] = $category;
		if($count == 3) {
			$count = 1;
		} else {
			$count++;
		}
	}
	ob_start();?>
	<div class="row">
	<? foreach($columns as $column) {?>
		<div class="span<?=$column_width?>">
			<ul class="unstyled">
				<? foreach($column as $category) { ?>
				<li><a href="<?=get_category_link($category->term_id)?>"><?=$category->name?></a></li>
				<? } ?>
			</ul>
		</div>
	<? } ?>
	</div><?
	return ob_get_clean();
}
add_shortcode('category-list', 'sc_category_list');

/**
 * List of archive years as links to archive pages
 *
 * @author Chris Conover
 * @return string
**/
function sc_archive_year_list($attrs, $content) {
	$years = get_archive_years();
	$archive_page = get_page_by_title('Archive');
	if($archive_page === False) {
		print 'Archive page does not exist.';
	} else {
		$archive_page_url = get_permalink($archive_page->ID);
		ob_start();?>
		<ul class="unstyled">
			<? foreach($years as $year) { ?>
				<li><a href="<?=$archive_page_url?>?yy=<?=$year?>"><?=$year?></a></li>
			<? } ?>
		</ul><?
		return ob_get_clean();
	}
}
add_shortcode('archive-year-list', 'sc_archive_year_list');
?>