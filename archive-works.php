<?php
/**
 * カスタム投稿名「Works」（post_type: 'works'）のタクソノミーアーカイブページを表示するテンプレートです。
 *
 * @package DESIGN:D ver2
 */

// URLパラメータからフィルター条件を取得
$filter_taxonomy = isset($_GET['works-category']) ? 'works-category' : (isset($_GET['client-category']) ? 'client-category' : '');
$filter_term     = $filter_taxonomy ? sanitize_text_field($_GET[$filter_taxonomy]) : '';

get_header(); ?>

	<section id="aboutus__works-archive" class="dflt maincontents dark">
		<div class="section_inner two-column">
			<div class="first_block fix_container">
				<div class="fix_inner">
					<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger" data-text="事例">Works <sup class="total_post_count">(<?php echo wp_count_posts('works')->publish; ?>)</sup></h3>
					<div class="menu_container accordion-parent">
						<a href="" class="filter_btn accordion-trigger font-en">Filter</a>
						<div class="list_wrapper accordion-target">
							<div class="list_archive accordion-inner">
								<!------WORKS CATEGORY------>
								<?php /*
								<div class="flex_inner">
									<h4 class="menu_title font-en">Works Category</h4>
									<ul class="category-list">
										<li>
											<a href="<?php echo esc_url( get_post_type_archive_link('works') ); ?>"
											   class="filter-link<?php echo (!$filter_taxonomy || $filter_taxonomy === 'works-category') && !$filter_term ? ' active' : ''; ?>"
											   data-taxonomy=""
											   data-term="">
												ALL<sup class="count">(<?php echo wp_count_posts('works')->publish; ?>)</sup>
											</a>
										</li>
										<?php
											$terms = get_terms(array(
												'taxonomy'   => 'works-category',
												'hide_empty' => false,
											));
											if (!empty($terms) && !is_wp_error($terms)) :
												foreach ($terms as $term) :
										?>
										<li>
											<a href="<?php echo esc_url( get_post_type_archive_link('works') . '?works-category=' . $term->slug ); ?>"
											   class="filter-link<?php echo ($filter_taxonomy === 'works-category' && $filter_term === $term->slug) ? ' active' : ''; ?>"
											   data-taxonomy="works-category"
											   data-term="<?php echo esc_attr($term->slug); ?>">
												<?php echo esc_html($term->name); ?>
												<span class="count">(<?php echo $term->count; ?>)</span>
											</a>
										</li>
										<?php endforeach; endif; ?>
									</ul>
								</div>
								*/ ?>
								<!------CLIENT CATEGORY------>
								<div class="flex_inner">
									<h4 class="menu_title font-en">Client Category</h4>
									<ul class="category-list">
										<li class="all">
											<a href="<?php echo esc_url( get_post_type_archive_link('works') ); ?>"
											   class="font-en filter-link<?php echo (!$filter_taxonomy || $filter_taxonomy === 'client-category') && !$filter_term ? ' active' : ''; ?>"
											   data-taxonomy=""
											   data-term="">
												ALL<sup class="count">(<?php echo wp_count_posts('works')->publish; ?>)</sup>
											</a>
										</li>
										<?php
											$terms = get_terms(array(
												'taxonomy'   => 'client-category',
												'hide_empty' => false,
											));
											if (!empty($terms) && !is_wp_error($terms)) :
												foreach ($terms as $term) :
										?>
										<li>
											<a href="<?php echo esc_url( get_post_type_archive_link('works') . '?client-category=' . $term->slug ); ?>"
											   class="font-en filter-link<?php echo ($filter_taxonomy === 'client-category' && $filter_term === $term->slug) ? ' active' : ''; ?>"
											   data-taxonomy="client-category"
											   data-term="<?php echo esc_attr($term->slug); ?>">
												<?php echo esc_html($term->name); ?>
												<sup class="count">(<?php echo $term->count; ?>)</sup>
											</a>
										</li>
										<?php endforeach; endif; ?>
									</ul>
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
								'post_type'      => 'works',
								'posts_per_page' => 12,
								'orderby'        => 'date',
								'order'          => 'DESC',
								'post_status'    => 'publish',
								'paged'          => 1,
							);

							if ($filter_taxonomy && $filter_term) {
								$args['tax_query'] = array(
									array(
										'taxonomy' => $filter_taxonomy,
										'field'    => 'slug',
										'terms'    => $filter_term,
									),
								);
							}

							$the_query = new WP_Query($args);

							if ($the_query->have_posts()) :
								while ($the_query->have_posts()) : $the_query->the_post();
									get_template_part('template-parts/ajax-works');
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
					<div class="more_btn all-interview anim-trigger-fade<?php echo !$has_more ? ' is-hidden-btn' : ''; ?>"
						 data-paged="1"
						 data-taxonomy="<?php echo esc_attr($filter_taxonomy); ?>"
						 data-term="<?php echo esc_attr($filter_term); ?>">
						<a href="" class="font-en cursor-trigger hover_dflt">More Works
							<sup class="next_load_count">(<?php echo $next_count; ?>)</sup>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>