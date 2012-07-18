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

function sc_person_picture_list($attrs) {
	$attrs['type'] = 'person';
	$row_size      = isset($attrs['row-size']) ? (int)$attrs['row-size'] : 5;
	$people        = sc_object_list($attrs, array('objects_only' => True));

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
			<? if($link) {?><a href="<?=get_permalink($person->ID)?>"><?}?>
			<img src="<?=$image_url ? $image_url : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg'?>" />
			<div class="name"><?=Person::get_name($person)?></div>
			<div class="title"><?=get_post_meta($person->ID, 'person_jobtitle', True)?></div>
			<? if($link) {?></a><?}?>
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

function sc_program_well($attrs, $content) {
	return sprintf('<div class="row"><div class="span12"><div class="program-well">%s</div></div></div>', do_shortcode($content));
}
add_shortcode('program-well', 'sc_program_well');

function sc_program_section($attrs, $content) {
	$title = isset($attrs['title']) ? '<span>'.$attrs['title'].'</span>' : '';
	return sprintf('
		<div class="program-section">
			<div class="row">
				<div class="span12">
					<h3 class="title">%s</h3>
				</div>
			</div>
			<div class="row">
				<div class="span12">
					%s
				</div>
			</div>
		</div>', $title, do_shortcode($content));
}
add_shortcode('program-section', 'sc_program_section');
?>