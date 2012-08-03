<?php disallow_direct_load('page-archive.php');?>
<?php get_header(); ?>
	<?
		$archive_years = get_archive_years();
		# Handle archive year if present
		
		if(isset($_GET['yy']) && is_numeric($_GET['yy'])) {
			$specific_year = $_GET['yy'];
		} else {
			$date = getdate();
			$specific_year = $date['year'];
		}

		$start = mktime(0, 0, 0, 1, 1, $specific_year);
		$end   = mktime(23, 59, 59, 12, 31, $specific_year);


		if($start !== False and $end != False) {
			function archive_where($where = '') {
				global $start, $end;

				$where .= " AND post_date >= '".date("Y-m-d H:i:s", $start)."' AND post_date <= '".date("Y-m-d H:i:s", $end)."'";
				return $where;
			}

			add_filter('posts_where', 'archive_where');
		}
	?>
	<div class="page-content">
		<div class="row">
			<div class="span6">
				<h2>News Archive</h2>
			</div>
			<div class="span3">
				<? $pagination_details = get_pagination_details(
						array('tax_query'=>
							array(
								'taxonomy'=>'category',
								'field'   =>'slug',
								'terms'   => $wp_query->queried_object->slug
							),
						),
						9,
						'archive_where'
					);
				?>
				<div class="pagination pull-right">
					<? if($pagination_details['num_pages'] > 1) { ?>
					<ul>
						<? if($pagination_details['has_previous']) { ?>
						<li>
							<a href="<?=add_query_arg(array('pp' => $pagination_details['page'] - 1, 'yy' => $specific_year))?>">
								Prev
							</a>
						</li>
						<? } ?>
						<? if($pagination_details['has_next']) { ?>
						<li>
							<a href="<?=add_query_arg(array('pp' => $pagination_details['page'] + 1, 'yy' => $specific_year))?>">
								Next
							</a>
						</li>
						<? } ?>
						<li class="status">
							<a>
								<?=$pagination_details['page']?> of <?=$pagination_details['num_pages']?>
							</a>
						</li>
					</ul>
					<? } ?>
				</div>
			</div>
			<div class="span3">
				<form class="form-horizontal pull-right" style="margin-top:18px;" action="." method="get">
					<select class="input-small" name="yy">
						<? foreach($archive_years as $year) { ?>
						<option value="<?=$year?>"<?=$specific_year == $year ? ' selected="selected"':''?>>
							<?=$year?>
						</option>
						<? } ?>
					</select>
					<button class="btn">Change Year</button>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<?
					$content = sc_object_list(
						array(
							'categories'  =>$wp_query->queried_object->slug,
							'type'        =>'post',
							'limit'       => $pagination_details['page_size'],
							'offset'      => $pagination_details['offset'])
					);
					if($content == '') {?>
						<p><strong>There are no archived posts for this year</strong></p>
					<? } else { ?>
						<?=$content?>
					<? } ?>
			</div>
		</div>
	</div>
	<?
	if(function_exists('archive_year')) {
		remove_filter('posts_where', 'archive_where');
	}
	?>
<?php get_footer();?>