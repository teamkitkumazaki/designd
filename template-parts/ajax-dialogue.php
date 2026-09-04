<?php
/**
 * Dialogueアーカイブ：サムネイル1件分のHTMLテンプレート
 * archive-dialogue.php とAJAXハンドラの両方から使用
 *
 * @package DESIGN:D ver2
 */
global $post;

$image_sp         = get_the_post_thumbnail_url($post->ID, 'medium_large');
$interview_member = SCF::get('interview_member', $post->ID);
$date             = get_the_date('Y.m.d');
?>
<a href="<?php the_permalink(); ?>" class="anim-trigger init animsition-link cursor-trigger">
	<dl class="imgOnly">
		<dt>
			<figure class="thumbnail">
				<?php if ($image_sp) : ?>
					<img loading="lazy" decoding="async" class="portrait" src="<?php echo esc_url($image_sp); ?>" alt="">
				<?php else : ?>
					<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/alternative_image.jpg" alt="" loading="lazy" decoding="async">
				<?php endif; ?>
			</figure>
		</dt>
		<dd>
			<h3 class="title font-sub"><?php the_title(); ?></h3>
			<?php if ($interview_member) : ?>
				<p class="interviewer font-sub"><?php echo esc_html($interview_member); ?></p>
			<?php endif; ?>
			<p class="description font-en"><?php echo esc_html($date); ?></p>
		</dd>
	</dl>
</a>
