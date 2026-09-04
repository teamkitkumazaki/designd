<?php
/**
 * Dialogue セクション
 * 呼び出し例: get_template_part('template-parts/section-dialogue', null, ['post_id' => $post_id]);
 *
 * @package DESIGN:D ver2
 */
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();

$dialogue       = SCF::get('dialogue', $post_id);
$valid_dialogue = array_filter($dialogue, function($d) {
	return !empty($d['related_dialogue']);
});

if (!$valid_dialogue) return;
?>
<section class="section-dialogue dflt anim-trigger">
	<div class="section_inner two-column">
		<div class="first_block">
			<div class="sticky_inner">
				<p class="sholder">Dialogue</p>
			</div>
		</div>
		<div class="contents_block">

			<?php foreach ($valid_dialogue as $d):?>
				<?php
					$post_ids = $d['related_dialogue'];
					if (!is_array($post_ids)) $post_ids = [$post_ids];
				?>
				<div class="archives__container">
				<?php foreach ($post_ids as $post_id_item):?>
					<?php
						$page_ttl         = get_the_title($post_id_item);
						$date             = get_the_date('Y.m.d', $post_id_item);
						$image_sp         = get_the_post_thumbnail_url($post_id_item, 'medium_large');
						$interview_member = SCF::get('interview_member', $post_id_item);
					?>
					
						<a href="<?= get_permalink($post_id_item); ?>" class="anim-trigger animsition-link cursor-trigger">
							<dl class="animsition-link imgOnly">
								<dt>
									<figure class="thumbnail"><img loading="lazy" decoding="async" class="portrait" src="<?= $image_sp ?>"></figure>
								</dt>
								<dd>
									<h3 class="title font-sub"><?= $page_ttl; ?></h3>
									<p class="interviewer font-sub"><?= $interview_member;?></p>
									<p class="description font-en"><?= $date;?></p>
								</dd>
							</dl>
						</a>
				<?php endforeach; ?>
				</div>
			<?php endforeach; ?>

			<div class="more_btn all-interview anim-trigger-fade">
				<a href="<?php echo esc_url( home_url() ); ?>/dialogue/" class="font-en animsition-link cursor-trigger hover_dflt">Achives
					<sup class="total_post_count">(<?php echo wp_count_posts('interview')->publish; ?>)</sup>
				</a>
			</div>
		</div>
	</div>
</section>
