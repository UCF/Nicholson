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
				<div class="pagination pull-right">
					<? if($max_num_pages > 1) { ?>
					<ul>
						<? if($has_previous) { ?>
						<li>
							<a href="<?=add_query_arg(array('pp' => $current_page - 1, 'yy' => $specific_year))?>">
								Prev
							</a>
						</li>
						<? } ?>
						<? if($has_next) { ?>
						<li>
							<a href="<?=add_query_arg(array('pp' => $current_page + 1, 'yy' => $specific_year))?>">
								Next
							</a>
						</li>
						<? } ?>
						<li class="status">
							<a>
								<?=$current_page?> of <?=$max_num_pages?>
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
					if(function_exists('archive_year')) {
						remove_filter('posts_where', 'archive_where');
					}
					if($html == '') {?>
						<p><strong>There are no archived posts for this year</strong></p>
					<? } else { ?>
						<?=$html?>
					<? } ?>
				<?php get_template_part('includes/below-the-fold'); ?>
			</div>
		</div>
	</div>
<?php get_footer();?>