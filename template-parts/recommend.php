<?php
/**
 * 「Woeks」のレコメンドサムネール表示テンプレートです。
 *
 * @package DESIGN:D
 */
?>
	<section class="recommend fadein ">
		<header class="recommend__header">
			<h3 class="recommend__header__title">Other Works</h3>
		</header>
		<div class="recommend__container">
			<ul>

<?php
	$args = array(
		'post_type' => 'works',		// 投稿タイプ
		'posts_per_page' => 4,		// 最大表示数
		'orderby' => 'rand',		// ランダムに表示
		'order' => 'DESC',			// 降順でソート
		'post_status' => 'publish'
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
?>

				<li class="recomMovie"><a href="<?php the_permalink(); ?>">

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
					$alternative_image = wp_get_attachment_image_src( $alternative_image_id, 'medium_large' );
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
				</a></li>

<?php
		endwhile;
	else :
?>
			<p>現在、登録された投稿はありません。</p>
<?php
	endif;
	wp_reset_postdata();
?>



			</ul>
		</div>
	</section>