<?php
/**
 * カスタム投稿名「Interview」（post_type: 'interview'）のアーカイブページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>

<section id="dialogue-archive" class="dflt maincontents anim-trigger">
	<div class="section_inner two-column">
		<div class="first_block fix_container">
			<div class="fix_inner">
				<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger" data-text="対談">Dialogue <sup class="total_post_count">(<?php echo wp_count_posts('interview')->publish; ?>)</sup></h3>
			</div>
		</div>
		<div class="contents_block font-sub">
			<div class="archives">
				<div class="works-spinner" style="display:none;"></div>
				<div class="archives__container">
					<?php
						$args = array(
							'post_type'      => 'interview',
							'posts_per_page' => 12,
							'orderby'        => 'date',
							'order'          => 'DESC',
							'post_status'    => 'publish',
							'paged'          => 1,
						);


						$the_query = new WP_Query($args);

						if ($the_query->have_posts()) :
							while ($the_query->have_posts()) : $the_query->the_post();
								get_template_part('template-parts/ajax-dialogue');
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
					<a href="" class="font-en cursor-trigger hover_dflt">More Dialogue
						<sup class="next_load_count">(<?php echo $next_count; ?>)</sup>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
