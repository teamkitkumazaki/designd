<?php
/**
 * カスタム投稿名「制作事例」（post_type: 'works'）の詳細ページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>


<?php
	$post_id = $post->ID; //ポストID
	$authorID = $post->post_author; // 著者のID
	$meta = get_post_meta($post_id); //ポストID
	$page_ttl = get_the_title($post_id);
	$client_name = SCF::get('client_name',$post_id);
	$periods = SCF::get('periods',$post_id);
	$work_type = SCF::get('work_type',$post_id);
	$image = get_the_post_thumbnail_url($id, 'full');
	$image_sp = get_the_post_thumbnail_url($id, 'medium_large');
	$sp_main_url = wp_get_attachment_image_src($image_sp, 'medium_large');
	$article = SCF::get('article_contents',$post_id);

?>

<article id="worksDetail" class="page-works-detail single-page">
	<section class="section-works-header dflt maincontents">
		<div class="section_inner">
			<h1 class="works_ttl font-en anim-trigger-fade"><?= $page_ttl;?></h1>
			<div class="works_detail_wrapper">
				<div class="detail_item font-en anim-trigger">
					<?php if ($client_name):?>
					<p><span class="sholder">Client</span><br><span><?= $client_name; ?></span></p>
					<?php endif; ?>
					<?php if ($periods):?>
					<p><span class="sholder">Periods</span><br><span><?= $periods; ?></span></p>
					<?php endif; ?>
					<?php if ($work_type):?>
					<p><span class="sholder">Works Category</span><br><span><?= $work_type; ?></span></p>
					<?php endif; ?>
				</div>
				<div class="thumnail_wrap anim-trigger-fade">
					<img loading="lazy" decoding="async" class="portrait" src="<?= $image ?>" srcset="<?= $image ?> 1440w, <?= $image_sp ?> 768w, <?= $image ?> 2048w">
				</div>
			</div>

			<div id="articleContent" class="article_contents">
				<?php $contents_length = 0;?>
				<?php foreach ($article as $d):?>
					<div class="article_item">
						<?php if ($d['title_jp'] || $d['article_description']):?>
						<div class="article_text one-column anim-trigger">
							<?php if ($d['title_jp']):?>
							<hgroup id="section<?= $contents_length;?>" class="first_block">
								<div class="fix_inner">
									<p class="sholder ttl_en font-en"><?= $d['title_en'];?></p>
									<h2 class="article_ttl font-sub" style="white-space: pre-line;"><?= $d['title_jp'];?></h2>
								</div>
							</hgroup>
							<?php $contents_length = $contents_length + (int)1;?>
							<?php endif; ?>
							<?php if ($d['article_description']):?>
							<div class="article_description contents_block text_block txt-justify">
								<p style="white-space: pre-line;"><?= $d['article_description'];?></p>
							</div>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<?php if ($d['article_movie']):?>
							<div class="movie_wrap anim-trigger-fade">
								<video src="<?=  wp_get_attachment_image_src($d['article_movie']);?>" autoplay="autoplay" loop="loop" muted="" controls="controls" width="300" height="150"></video>
							</div>
						<?php endif; ?>
						<?php if ($d['artivle_movie_code']):?>
							<div class="movie_wrap embed_movie anim-trigger-fade">
								<?php $artivle_movie_code =  str_replace("/cms/", "/", $d['artivle_movie_code']);?>
								<?= $artivle_movie_code; ?>
							</div>
						<?php endif; ?>
						<?php if ($d['article_image']):?>
							<div class="img_wrap anim-trigger-fade">
								<?php
									$img_medium = wp_get_attachment_image_src($d['article_image'], 'medium_large');
									$img_large  = wp_get_attachment_image_src($d['article_image'], 'large');
									$img_full   = wp_get_attachment_image_src($d['article_image'], 'full');
								?>
								<img
									src="<?= $img_full[0] ?>"
									srcset="<?= $img_medium[0] ?> <?= $img_medium[1] ?>w,
											<?= $img_large[0] ?> <?= $img_large[1] ?>w,
											<?= $img_full[0] ?> <?= $img_full[1] ?>w"
									sizes="(max-width: 768px) 100vw, 50vw"
									decoding="async"
								>
							</div>
							<?php endif; ?>
							<?php if ($d['article_image_src']):?>
								<?php $article_img_src =  str_replace("/cms/", "/", $d['article_image_src']);?>
								<img src="<?= $article_img_src; ?>" class="anim-trigger-fade" loading="lazy" decoding="async">
							<?php endif; ?>
						</div>
				<?php endforeach; ?>
			</div><!-- article_contents -->
		</div>
	</section>

	<!-- Member -->
	<?php get_template_part('template-parts/section-member', null, ['post_id' => $post_id]); ?>

	<!-- Dialogue -->
	<?php get_template_part('template-parts/section-dialogue', null, ['post_id' => $post_id]); ?>

	<!-- Related Works -->
	<?php get_template_part('template-parts/section-related-works', null, ['post_id' => $post_id]); ?>

</article>


<?php get_footer(); ?>
