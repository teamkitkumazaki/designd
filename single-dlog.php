<?php
/**
 * カスタム投稿名「D:Log」（post_type: 'dlog'）の詳細ページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>

<article id="dlogDetail" class="page-dialogue-detail single-page">
	<section class="section-dlog-header dflt maincontents">
		<div class="section_inner">
			<h1 class="dlog_ttl font-sub anim-trigger-fade"><?php the_title(); ?></h1>
			<div class="dlog_description">
				<p class="sholder font-en">Date</p>
				<p class="date font-en"><?php the_time('Y.m.d'); ?></p>
			</div>
			
			<div id="articleContent" class="article_contents two-column">
				<div class="first_block">
					
				</div>
				<div class="contents_block text_block">
					<div class="contents_inner">
						<?php the_content(); ?>
					</div>

					<div class="more_btn all-interview anim-trigger-fade">
					<a href="<?php echo esc_url( home_url() ); ?>/dlog/" class="font-en animsition-link cursor-trigger hover_dflt">Back to D:Log
						<sup class="total_post_count">(<?php echo wp_count_posts('dlog')->publish; ?>)</sup>
					</a>
				</div>
				</div>
			</div><!-- article_contents -->
		</div>
	</section>

</article>

<?php get_footer(); ?>