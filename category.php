<?php disallow_direct_load('category.php');?>
<?php get_header(); ?>
	<div class="page-content">
		<div class="row">
			<div class="span9">
				<h2><?=$wp_query->queried_object->name?></h2>
			</div>
			<div class="span3">
				<? 
					$page_size = get_pagination_page_size();

					if(!isset($_GET['pp']) || !is_numeric($_GET['pp']) || (int)$_GET['pp'] < 1) {
						$current_page = 1;
					} else {
						$current_page = (int)$_GET['pp'];
					}

					$results = sc_object_list(
						array(
							'categories'  => $wp_query->queried_object->slug,
							'type'        =>'post',
							'limit'       => $page_size,
							'offset'      => ($current_page - 1) * $page_size
						),
						array('max_num_pages' => True)
					);
					$max_num_pages = $results['max_num_pages'];
					$html          = $results['html'];
					
					$has_next      = (($current_page + 1) > $max_num_pages) ? False : True;
					$has_previous  = ($current_page > 1) ? True : False;

				?>
				<? if($max_num_pages > 1) { ?>
				<div class="pagination pull-right">
					<ul>
						<? if($has_previous) { ?>
						<li><a href="?pp=<?=$current_page - 1?>">Prev</a></li>
						<? } ?>
						<? if($has_next) { ?>
						<li><a href="?pp=<?=$current_page + 1?>">Next</a></li>
						<? } ?>
						<li class="status"><a><?=$current_page?> of <?=$max_num_pages?></a></li>
					</ul>
				</div>
				<? } ?>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<?
					echo $html;
				?>
				<?php get_template_part('includes/below-the-fold'); ?>
			</div>
		</div>
	</div>
<?php get_footer();?>