<?php
/**
 * Related Works セクション
 * 呼び出し例: get_template_part('template-parts/section-related-works', null, ['post_id' => $post_id]);
 *
 * @package DESIGN:D ver2
 */
$post_id  = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$taxonomy = 'client-category';
$terms    = get_the_terms($post_id, $taxonomy);

if (!$terms || is_wp_error($terms)) return;

$term_ids  = wp_list_pluck($terms, 'term_id');
$the_query = new WP_Query(array(
	'post_type'      => get_post_type($post_id),
	'posts_per_page' => 2,
	'orderby'        => 'rand',
	'post__not_in'   => array($post_id),
	'tax_query'      => array(
		array(
			'taxonomy' => $taxonomy,
			'field'    => 'term_id',
			'terms'    => $term_ids,
		),
	),
));

if (!$the_query->have_posts()) {
	wp_reset_postdata();
	return;
}
?>
<section class="section-related-works dflt anim-trigger">
	<div class="section_inner two-column">
		<div class="first_block">
			<div class="sticky_inner">
				<p class="sholder font-en">Related Works</p>
			</div>
		</div>
		<div class="contents_block">
			<div class="archives__container">
				<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
					<?php
						$movie    = get_post_meta($post->ID, 'thumbnai_movie', true);
						$up_movie = '';
						if ($movie) {
							foreach (get_post_meta($post->ID, 'thumbnai_movie', false) as $up_file) {
								$up_movie = wp_get_attachment_url($up_file);
							}
						}
					?>
					<a href="<?php the_permalink(); ?>" class="anim-trigger animsition-link cursor-trigger">
						<dl class="archiveMovie">
							<dt>
								<?php if ($up_movie): ?>
									<video class="video pc" loop muted preload="metadata">
										<source src="<?= esc_url($up_movie); ?>">
									</video>
								<?php else:
									$alternative_image_id = get_field('thumbnai_movie_alternative');
									if ($alternative_image_id):
										$alternative_image_url = wp_get_attachment_image_src($alternative_image_id, 'medium_large')[0];
								?>
									<figure class="alternative_image">
										<img src="<?= esc_url($alternative_image_url); ?>" alt="" loading="lazy" decoding="async">
									</figure>
								<?php endif; endif; ?>

								<figure class="thumbnail"><?php
									if (has_post_thumbnail()) {
										the_post_thumbnail('medium_large', array('loading' => 'lazy'));
									} else {
										echo '<img src="' . esc_url(get_template_directory_uri()) . '/assets/images/alternative_image.jpg" alt="" loading="lazy" decoding="async">';
									}
								?></figure>
							</dt>
							<dd>
								<h3 class="title font-en"><?php the_title(); ?></h3>
							</dd>
						</dl>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>

				<div class="more_btn all-interview anim-trigger-fade">
					<a href="<?php echo esc_url(home_url()); ?>/works/" class="font-en animsition-link cursor-trigger hover_dflt">Achives
						<sup class="total_post_count">(<?php echo wp_count_posts('works')->publish; ?>)</sup>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
