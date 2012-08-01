<?php disallow_direct_load('category.php');?>
<?php get_header(); ?>
	<div class="page-content">
		<div class="row">
			<div class="span9">
				<h2><?=$wp_query->queried_object->name?></h2>
			</div>
			<div class="span3">
				<? $pagination_details = get_pagination_details(
						array('tax_query'=>
							array(
								'taxonomy'=>'category',
								'field'=>'slug',
								'terms'=> $wp_query->queried_object->slug
							)
						),
						2
					);
				?>
				<? if($pagination_details['num_pages'] > 0) { ?>
				<div class="pagination pull-right">
					<ul>
						<? if($pagination_details['has_previous']) { ?>
						<li><a href="?page=<?=$pagination_details['page'] - 1?>">Prev</a></li>
						<? } ?>
						<? if($pagination_details['has_next']) { ?>
						<li><a href="?page=<?=$pagination_details['page'] + 1?>">Next</a></li>
						<? } ?>
						<li><a><?=$pagination_details['page']?> of <?=$pagination_details['num_pages']?></a></li>
					</ul>
				</div>
				<? } ?>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<?
					var_dump($pagination_details);
					echo sc_object_list(
						array(
							'categories'  =>$wp_query->queried_object->slug,
							'type'        =>'post')
					);
				?>
			</div>
		</div>
	</div>
<?php get_footer();?>