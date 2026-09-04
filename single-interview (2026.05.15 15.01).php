<?php
/**
 * カスタム投稿名「D:Log」（post_type: 'dlog'）の詳細ページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>


<?php
	$post_id = $post->ID; //ポストID
	$authorID = $post->post_author; // 著者のID
	$meta = get_post_meta($post_id); //ポストID
	$image = get_the_post_thumbnail_url($id, 'medium_large');
	$image_sp = get_the_post_thumbnail_url($id, 'medium_large');
	$main_caption = SCF::get('main_caption',$post_id);
	$sp_main_url = wp_get_attachment_image_src($image_sp, 'medium_large');
	$page_ttl = get_the_title($post_id);
	$interview_member = SCF::get('interview_member',$post_id);
	$interview_lead = SCF::get('interview_lead',$post_id);
	$article = SCF::get('article',$post_id);
	$related_works = SCF::get('related_works',$post_id);
	$relatedID = SCF::get('related_work_id',$post_id);

	/* カテゴリー */
	$terms = get_the_terms($post->ID, 'news-category');

?>

<article id="dialogueDetail" class="page-dialogue-detail single-page">
	<section id="articleContent" class="section-dialogue-header dflt maincontents">
		<div class="section_inner">
			<h1 class="dialogue_ttl font-sub anim-trigger-fade"><?= $page_ttl;?></h1>
			<div class="dialogue_description anim-trigger">
				<p class="sholder">対談参加者</p>
				<p class="interviewer"><?= $interview_member;?></p>
			</div>
			
			<div class="dialogue_firstview two-column anim-trigger">
				<div class="first_block text_block">
					<p class="dialogue_lead txt-justify">
						<?= $interview_lead;?>
					</p>
				</div>
				<div class="contents_block">
					<img loading="lazy" class="portrait" src="<?= $image ?>" srcset="<?= $image ?> 1440w, <?= $image_sp ?> 768w, <?= $image ?> 2048w">
					<?php if ($main_caption):?>
						<p class="caption"><?= $main_caption;?></p>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="article_contents two-column">
				<div class="first_block">
					<div class="dialogue_index sticky_inner">
						<div class="dialogue_index_item">
							<p class="sholder font-en">Index</p>
							<ul class="index_list">
							<?php $contents_length = 0;?>
							<?php foreach ($article as $d):?>
								<?php if ($d['article_ttl']):?>
									<li><a href="#section<?= $contents_length;?>" class="font-sub"><?= $d['article_ttl'];?></a></li>
									<?php $contents_length = $contents_length + (int)1;?>
								<?php endif; ?>
							<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="contents_block text_block">
					<?php $contents_length = 0;?>
					<?php foreach ($article as $d):?>
						<div class="article_item">
							<?php if ($d['article_ttl']):?>
							<h2 id="section<?= $contents_length;?>" class="article_ttl font-sub anim-trigger-fade"><?= $d['article_ttl'];?></h2>
							<?php $contents_length = $contents_length + (int)1;?>
							<?php endif; ?>
							<?php if ($d['article_contents']):?>
							<div class="article_description txt-justify anim-trigger-fade">
								<p><?= $d['article_contents'];?></p>
							</div>
							<?php endif; ?>
							<?php if ($d['article_img']):?>
								<div class="img_wrap anim-trigger-fade">
									<img
										src="<?= wp_get_attachment_image_src($d['article_img'], 'full')[0] ?>"
										srcset="<?= wp_get_attachment_image_src($d['article_img'], 'medium')[0] ?> 1440w, <?= wp_get_attachment_image_src($d['article_img'], 'medium_large')[0] ?> 768w, <?= wp_get_attachment_image_src($d['article_img'], 'full')[0] ?> 2048w"
									>
									<?php if ($d['article_img_caption']):?>
									<p class="caption anim-trigger-fade"><?= $d['article_img_caption'];?>
									</p><?php endif; ?>
								</div>
								<?php endif; ?>
							</div>
					<?php endforeach; ?>
				</div>
			</div><!-- article_contents -->
		</div>
	</section>

	<!-- Member -->
	<?php get_template_part('template-parts/section-member', null, ['post_id' => $relatedID]); ?>

	<!-- Dialogue -->
	<?php
		$prev_post = get_previous_post();
		$next_id   = !empty($prev_post) ? $prev_post->ID : null;

		if (!$next_id) {
			$latest  = get_posts(array(
				'post_type'      => 'interview',
				'posts_per_page' => 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post__not_in'   => array($post_id),
			));
			$next_id = !empty($latest) ? $latest[0]->ID : null;
		}
	?>
	<?php if ($next_id) :
		$next_ttl    = get_the_title($next_id);
		$next_date   = get_the_date('Y.m.d', $next_id);
		$next_img_sp = get_the_post_thumbnail_url($next_id, 'medium_large');
		$next_member = SCF::get('interview_member', $next_id);
	?>
	<section class="section-dialogue dflt anim-trigger">
		<div class="section_inner two-column">
			<div class="first_block">
				<div class="sticky_inner">
					<p class="sholder font-en">Dialogue</p>
				</div>
			</div>
			<div class="contents_block">
				<div class="archives__container">
					<a href="<?= get_permalink($next_id); ?>" class="anim-trigger animsition-link cursor-trigger">
						<dl class="animsition-link imgOnly">
							<dt>
								<figure class="thumbnail"><img loading="lazy" class="portrait" src="<?= esc_url($next_img_sp); ?>"></figure>
							</dt>
							<dd>
								<h3 class="title font-sub"><?= esc_html($next_ttl); ?></h3>
								<p class="interviewer font-sub"><?= esc_html($next_member); ?></p>
								<p class="description font-en"><?= esc_html($next_date); ?></p>
							</dd>
						</dl>
					</a>
				</div>

				<div class="more_btn all-interview anim-trigger-fade">
					<a href="<?php echo esc_url(home_url()); ?>/dialogue/" class="font-en animsition-link cursor-trigger hover_dflt">Achives
						<sup class="total_post_count">(<?php echo wp_count_posts('interview')->publish; ?>)</sup>
					</a>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- Related Works -->
	<?php if ($relatedID) : global $post; $post = get_post($relatedID); setup_postdata($post); ?>
	<section class="section-related-works dflt anim-trigger">
		<div class="section_inner two-column">
			<div class="first_block">
				<div class="sticky_inner">
					<p class="sholder font-en">Related Works</p>
				</div>
			</div>
			<div class="contents_block">
				<div class="archives__container">
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
										<img src="<?= esc_url($alternative_image_url); ?>" alt="" loading="lazy">
									</figure>
								<?php endif; endif; ?>

								<figure class="thumbnail"><?php
									if (has_post_thumbnail()) {
										the_post_thumbnail('medium_large', array('loading' => 'lazy'));
									} else {
										echo '<img src="' . esc_url(get_template_directory_uri()) . '/assets/images/alternative_image.jpg" alt="" loading="lazy">';
									}
								?></figure>
							</dt>
							<dd>
								<h3 class="title font-en"><?php the_title(); ?></h3>
							</dd>
						</dl>
					</a>

					<div class="more_btn all-interview anim-trigger-fade">
						<a href="<?php echo esc_url(home_url()); ?>/works/" class="font-en animsition-link cursor-trigger hover_dflt">Achives
							<sup class="total_post_count">(<?php echo wp_count_posts('works')->publish; ?>)</sup>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php wp_reset_postdata(); endif; ?>

</article>


<?php get_footer(); ?>
