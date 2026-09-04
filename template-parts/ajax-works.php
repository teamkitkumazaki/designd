<?php
/**
 * Worksアーカイブ：サムネイル1件分のHTMLテンプレート
 * archive-works.php とAJAXハンドラの両方から使用
 *
 * @package DESIGN:D ver2
 */
global $post;

$movie    = get_post_meta($post->ID, 'thumbnai_movie', true);
$up_movie = '';

if ($movie) {
	$up_files = get_post_meta($post->ID, 'thumbnai_movie', false);
	foreach ($up_files as $up_file) {
		$up_movie = wp_get_attachment_url($up_file);
	}
}
?>
<a href="<?php the_permalink(); ?>" class="anim-trigger init animsition-link cursor-trigger">
	<dl class="archiveMovie">
		<dt>
			<?php if ($up_movie) : ?>
				<video class="video pc" loop muted preload="metadata">
					<source src="<?php echo esc_url($up_movie); ?>">
				</video>
			<?php else :
				$alternative_image_id = get_field('thumbnai_movie_alternative');
				if ($alternative_image_id) :
					$alternative_image     = wp_get_attachment_image_src($alternative_image_id, 'medium_large');
					$alternative_image_url = $alternative_image[0];
			?>
				<figure class="alternative_image">
					<img src="<?php echo esc_url($alternative_image_url); ?>" alt="" loading="lazy" decoding="async">
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