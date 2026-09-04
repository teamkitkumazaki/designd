<?php
/**
 * カスタム投稿名「Works」（post_type: 'works'）のアーカイブページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>

	<section class="archives">
		<div id="ajax_container" class="archives__container">

<?php
	$paged = get_query_var('paged');
	$args = array(
		'post_type' => 'works',		// 投稿タイプ
		'posts_per_page' => 10,		// 最大表示数
		'paged' => $paged,
		'orderby' => 'date',		// 日付でソート
		'order' => 'DESC',			// 降順でソート
		'post_status' => 'publish'
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
?>

			<a href="<?php the_permalink(); ?>" class="work-post">
				<dl class="archiveMovie">
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
						<video class="video" loop muted>
							<source src="<?php echo $up_movie;?>">
						</video>
<?php
			} else {
				$alternative_image_id = get_field( 'thumbnai_movie_alternative' );
				if( $alternative_image_id ){
					$alternative_image = wp_get_attachment_image_src( $alternative_image_id, 'full' );
					$alternative_image_url = $alternative_image[0];
?>
						<figure class="alternative_image">
							<img src="<?php echo $alternative_image_url; ?>" alt="">
						</figure>
<?php
				}
			}
?>

						<figure class="thumbnail"><?php
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( 'large' );
	} else {
		echo '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/alternative_image.jpg" alt="">';
	}
?></figure>
					</dt>
					<dd>
						<h3 class="title"><?php the_title(); ?></h3>
						<p class="description"><?php
	$field_value = get_field( 'work_type' );
	if( $field_value ) {
		echo $field_value;
	}
?></p>
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

<?php
	// ページネーションの出力
	if( function_exists( 'create_pagination' ) ) create_pagination();
?>

<?php
	global $paged;
	if( empty( $paged ) ) $paged = 1;

	global $wp_query;
	$pages = $wp_query -> max_num_pages;
	if( !$pages ) {
		$pages = 1;
	}
	// 1ページしかない or 最後のページでは出力しない
	if( $pages != 1 && $paged < $pages ) {
?>
	<div class="archives__more more-works">
		<a id="ajax_more" class="archives__more__button view-more-button" href="javascript void(0);">More Works <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/more_arrow.svg" alt="" class="archives__more__arrow"></a>
	</div>
<?php
	}
?>

	</section>

<?php get_footer(); ?>