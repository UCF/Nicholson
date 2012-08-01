<?php disallow_direct_load('category.php');?>
<?php get_header(); ?>
	<div class="page-content">
		<div class="row">
			<div class="span12">
				<h2><?=$wp_query->queried_object->name?></h2>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<?
					echo sc_object_list(
						array('categories'=>$wp_query->queried_object->slug, 'type'=>'post')
					);
				?>
			</div>
		</div>
	</div>
<?php get_footer();?>