<?php
/**
 * D:Logアーカイブ：サムネイル1件分のHTMLテンプレート
 * archive-dlog.php とAJAXハンドラの両方から使用
 *
 * @package DESIGN:D ver2
 */
global $post;

$image = get_the_post_thumbnail_url($post->ID, 'medium_large');
if (!$image) {
	$image = get_template_directory_uri() . '/assets/images/alternative_image.jpg';
}
$date = get_the_date('Y.m.d');
?>
<a href="<?php the_permalink(); ?>" class="anim-trigger init animsition-link cursor-trigger">
	<dl class="imgOnly">
		<dt>
			<figure class="thumbnail">
				<img loading="lazy" decoding="async" src="<?php echo esc_url($image); ?>" alt="">
			</figure>
		</dt>
		<dd>
			<h3 class="title font-sub"><?php the_title(); ?></h3>
			<p class="description font-en"><?php echo esc_html($date); ?></p>
		</dd>
	</dl>
</a>
