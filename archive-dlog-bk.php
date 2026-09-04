<?php
/**
 * カスタム投稿名「D:Log」（post_type: 'dlog'）のアーカイブページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>

	<main class="contents">

		<header class="contents__header">
<?php /*
			<h3 class="contents__header__title"><span class="in-shaffle"><?php echo get_the_archive_title(); ?></span></h3>
*/ ?>
			<h3 class="contents__header__title"><span class="in-shaffle">D<i class="colon--mark">:</i>Log</span></h3>
		</header>

		<div class="contents__container">

			<div class="blog-archive">
<?php
	$paged = get_query_var('paged');
	$args = array(
		'post_type' => 'dlog',		// 投稿タイプ
		'posts_per_page' => -1,		// 最大表示数
		'paged' => $paged,
		'orderby' => 'date',		// 日付でソート
		'order' => 'DESC',			// 降順でソート
		'post_status' => 'publish'
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
?>
				<a href="<?php the_permalink(); ?>">
					<dl>
						<dt><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time></dt>
						<dd><?php the_title(); ?><i><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/profile-up.svg" alt=""></i></dd>
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

		</div>

	</main>

<?php
	/* カスタムテンプレート「recommend.php」をインクルードします。 */
	get_template_part( 'template-parts/recommend' );
?>

<?php get_footer(); ?>
