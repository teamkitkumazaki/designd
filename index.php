<?php
/**
 * トップページを表示するテンプレートです。
 *
 * @package DESIGN:D ver2
 */
get_header(); ?>

	<section id="parallax" class="introduction">
		<div class="introduction__container">
			<div class="introduction__copy-jp init">
				<h2>
					<span class="c1">
						<span>
							<picture>
								<source media="(max-width: 960px)" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top_kv_copy01-sp.svg">
								<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top_kv_copy01-pc.svg" alt="デザインで、超えていく。" decoding="async">
							</picture>
						</span>
					</span>
					<span class="c2">
						<span>
							<picture>
								<source media="(max-width: 960px)" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top_kv_copy02-sp.svg">
								<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top_kv_copy02-pc.svg" alt="デザインで、世界を動かす。" decoding="async">
							</picture>
						</span>
					</span>
					<span class="c3">
						<span>
							<picture>
								<source media="(max-width: 960px)" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top_kv_copy03-sp.svg">
								<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/top_kv_copy03-pc.svg" alt="デザインで、未来をつくる。" decoding="async">
							</picture>
						</span>
					</span>
				</h2>
				<div class="more_btn">
					<a href="<?php echo esc_url( home_url() ); ?>/about/" class="more_detail font-en animsition-link cursor-trigger hover_dflt">About Us</a>
				</div>
			</div>
			<div class="introduction__bg init">
				<video class="js-lazy-video" loop muted playsinline autoplay preload="none" poster="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/video_top_poster.jpg">
					<source data-src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/video/DESIGN-D_Top.mp4" type="video/mp4">
				</video>
			</div>
		</div>
	</section>


	<!-- DIALOGUE ARCHIVES -->
	<section class="top-dialogue">
		<div class="top-dialogue__container">
			<div class="title-block">
				<div class="title-block__inner sticky">
					<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger" data-text="最新の対談">Latest Dialogue</h3>
				</div>
			</div>
			<div class="content-block archives">
				<div class="archives__container">
				<?php
					$args = array(
						'post_type' => 'interview',		// 投稿タイプ
						'posts_per_page' => 2,		// 最大表示数
						'orderby' => 'date',		// 日付でソート
						'order' => 'DESC',			// 降順でソート
						'post_status' => 'publish'
					);

					$the_query = new WP_Query( $args );

					if ( $the_query->have_posts() ) :
						while ( $the_query->have_posts() ) : $the_query->the_post();
							$interview_member = SCF::get('interview_member', get_the_ID());
							$date             = get_the_date('Y.m.d');
				?>

				<a href="<?php the_permalink(); ?>" class="anim-trigger animsition-link cursor-trigger">
					<dl class="imgOnly">
						<dt>
							<figure class="thumbnail"><?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) );
					} else {
						echo '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/alternative_image.jpg" alt="" loading="lazy" decoding="async">';
					}
				?></figure>
						</dt>
						<dd>
							<h3 class="title font-sub"><?php the_title(); ?></h3>
							<p class="interviewer font-sub"><?= $interview_member;?></p>
							<p class="description font-en"><?= $date;?></p>
						</dd>
					</dl>
				</a>

				<?php
						endwhile;
					else :
				?>
							<p>現在、登録された投稿はありません。</p>
				<?php
					endif;
					wp_reset_postdata();
				?>
				</div>
				<div class="more_btn all-interview anim-trigger-fade">
					<a href="<?php echo esc_url( home_url() ); ?>/dialogue/" class="font-en animsition-link cursor-trigger hover_dflt">Achives
						<sup class="total_post_count">(<?php echo wp_count_posts('interview')->publish; ?>)</sup>
					</a>
				</div>
			</div>
		</div>

		

	</section>

	<!-- WORKS ARCHIVES -->
	<section class="top-archives bg_dark">
		<div class="top-archives__container">
			<div class="title-block">
				<div class="title-block__inner sticky">
					<h3 class="font-en deco_w-dot txt_split anim-trigger white_dot tip-trigger" data-text="最新の事例">Latest Works</h3>
				</div>
			</div>
			<div class="content-block archives">
				<div class="archives__container">
				<?php
					$args = array(
						'post_type' => 'works',		// 投稿タイプ
						'posts_per_page' => 6,		// 最大表示数
						'orderby' => 'date',		// 日付でソート
						'order' => 'DESC',			// 降順でソート
						'post_status' => 'publish'
					);

					$the_query = new WP_Query( $args );

					if ( $the_query->have_posts() ) :
						while ( $the_query->have_posts() ) : $the_query->the_post();
				?>

							<a href="<?php the_permalink(); ?>" class="anim-trigger animsition-link cursor-trigger">
								<dl class="archiveMovie animsition-link">
									<dt>

				<?php
							$movie = get_post_meta( $post->ID, 'thumbnai_movie', true );
							$up_movie = '';
							if ( $movie ) {
								$up_files = get_post_meta( $post->ID, 'thumbnai_movie', false );
								foreach( $up_files as $up_file ) {
									$up_movie = wp_get_attachment_url( $up_file );
								}
				?>
										<video class="video pc" loop muted preload="metadata">
											<source src="<?php echo $up_movie;?>">
										</video>
				<?php
							} else {
								$alternative_image_id = get_field( 'thumbnai_movie_alternative' );
								if( $alternative_image_id ){
									$alternative_image = wp_get_attachment_image_src( $alternative_image_id, 'medium_large' );
									$alternative_image_url = $alternative_image[0];
				?>
										<figure class="alternative_image">
											<img src="<?php echo $alternative_image_url; ?>" alt="" loading="lazy" decoding="async">
										</figure>
				<?php
								}
							}
				?>

										<figure class="thumbnail"><?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) );
					} else {
						echo '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/alternative_image.jpg" alt="" loading="lazy" decoding="async">';
					}
				?></figure>
									</dt>
									<dd>
										<h3 class="title font-en"><?php the_title(); ?></h3>
									</dd>
								</dl>
							</a>

				<?php
						endwhile;
					else :
				?>
							<p>現在、登録された投稿はありません。</p>
				<?php
					endif;
					wp_reset_postdata();
				?>
				</div>
				<div class="more_btn all-works anim-trigger-fade">
					<a href="<?php echo esc_url( home_url() ); ?>/works/" class="font-en animsition-link cursor-trigger hover_dflt">Archives
						<sup class="total_post_count">(<?php echo wp_count_posts('works')->publish; ?>)</sup>
					</a>
				</div>
			</div>
		</div>
	</section>

	<!-- What We Can Do -->
	<section class="top-wwcd bg_light">
		<div class="top-wwcd__container">
			<div class="title-block">
				<div class="title-block__inner">
					<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger" data-text="私たちについて">About Us</h3>
				</div>
			</div>
			<div class="content-block">
				<p class="msg font-sub anim-trigger">
					<span><span>企業やサービスの課題解決を軸に、</span></span><br>
					<span><span>ディレクション・制作を行う</span></span><br class="tab"><span><span>デザインエージェンシーです。</span></span><br>
					<span><span>受注作業として最終アウトプットの</span></span><br class="tab"><span><span>狭義のデザインだけでなく、</span></span><br>
					<span><span>ブランディングや経営課題など</span></span><br>
					<span><span>上流の課題に対しても</span></span><br class="tab"><span><span>広義のデザインで向き合います。</span></span>
				</p>
				<div class="more_btn anim-trigger-fade">
					<a href="<?php echo esc_url( home_url() ); ?>/about/" class="font-en animsition-link cursor-trigger hover_dflt">More Details</a>
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>