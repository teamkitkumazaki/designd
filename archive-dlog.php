<?php
/**
 * カスタム投稿名「D:Log」（post_type: 'dlog'）のアーカイブページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>

<section id="dlog-archive" class="dflt maincontents">
	<div class="section_inner two-column">
		<div class="first_block fix_container">
			<div class="fix_inner">
				<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger" data-text="ディーログ">D:Log <sup class="total_post_count">(<?php echo wp_count_posts('dlog')->publish; ?>)</sup></h3>
				<div class="menu_container accordion-parent">
					<a href="" class="filter_btn accordion-trigger font-en sp">Year</a>
					<div class="list_wrapper accordion-target isOpen">
						<div class="list_archive accordion-inner">
							<!------D:LOG Years------>
							<div class="flex_inner">
								<h4 class="menu_title font-en pc">Archives</h4>
								<?php
								$filter_year = isset($_GET['dlog_year']) ? intval($_GET['dlog_year']) : 0;
								global $wpdb;
								// カスタム投稿タイプ名
								$post_type = 'dlog';

								// 投稿がある「年」と「その年の投稿数」をグループ化して取得
								$results = $wpdb->get_results( $wpdb->prepare(
								"SELECT YEAR(post_date) as post_year, COUNT(ID) as post_count
								FROM $wpdb->posts
								WHERE post_type = %s
								AND post_status = 'publish'
								GROUP BY post_year
								ORDER BY post_year DESC",
								$post_type
								) );

								if ( $results ) :
								?>
								<ul class="category-list">
									<li class="all">
										<a href="<?php echo esc_url( get_post_type_archive_link('dlog') ); ?>"
											class="font-en filter-link<?php echo !$filter_year ? ' active' : ''; ?>"
											data-year="">
											ALL<sup class="count">(<?php echo wp_count_posts('dlog')->publish; ?>)</sup>
										</a>
									</li>
									<?php foreach ( $results as $row ) :
										$year  = $row->post_year;
										$count = $row->post_count;
									?>
									<li>
										<a href="<?php echo esc_url( add_query_arg( 'post_type', $post_type, get_year_link( $year ) ) ); ?>"
											class="font-en filter-link<?php echo $filter_year == $year ? ' active' : ''; ?>"
											data-year="<?php echo esc_attr( $year ); ?>">
											<?php echo $year; ?>年
											<sup class="count">(<?php echo $count; ?>)</sup>
										</a>
									</li>
									<?php endforeach; ?>
								</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="contents_block font-sub">
			<div class="archives">
				<div class="works-spinner" style="display:none;"></div>
				<div class="archives__container">
					<?php
						$args = array(
							'post_type'      => 'dlog',
							'posts_per_page' => 12,
							'orderby'        => 'date',
							'order'          => 'DESC',
							'post_status'    => 'publish',
							'paged'          => 1,
						);

						if ($filter_year) {
							$args['year'] = $filter_year;
						}

						$the_query = new WP_Query($args);

						if ($the_query->have_posts()) :
							while ($the_query->have_posts()) : $the_query->the_post();
								get_template_part('template-parts/ajax-dlog');
							endwhile;
						else :
					?>
						<p>現在、登録された投稿はありません。</p>
					<?php
						endif;
						$has_more   = $the_query->max_num_pages > 1;
						$next_count = min(12, max(0, $the_query->found_posts - 12));
						wp_reset_postdata();
					?>
				</div>
				<div class="more_btn all-interview anim-trigger-fade<?php echo ($next_count == 0) ? ' is-hidden-btn' : ''; ?>"
						data-paged="1"
						data-year="<?php echo esc_attr($filter_year); ?>">
					<a href="" class="font-en cursor-trigger hover_dflt">More D:Log
						<sup class="next_load_count">(<?php echo $next_count; ?>)</sup>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>


<?php get_footer(); ?>
