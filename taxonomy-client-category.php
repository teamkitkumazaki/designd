<?php
/**
 * カスタム投稿名「Works」（post_type: 'works'）のタクソノミーアーカイブページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>

	<article id="worksList" class="page-works-list" style="padding: 300px 100px; box-sizing: border-box;">

		<?php if (is_tax()) : ?>
			<?php $queried_object = get_queried_object();?>
			<h1>WORKS (<?php echo esc_html($queried_object->name); ?>)</h1>
			<?php else : ?>
    	<h1>WORKS</h1>
			<?php endif; ?>

			<div class="list_wrapper" style="display: flex; flex-wrap: wrap; align-items: flex-start;">
				<div class="list_archive" style="width: 20%;">
					<h3>CLIENT CATEGORY</h3>
					<ul class="category-list">
					<?php
						$terms = get_terms(array(
							'taxonomy' => 'client-category', // タクソノミー名
							'hide_empty' => false,      // 投稿がないカテゴリーは隠す
						));

						if (!empty($terms) && !is_wp_error($terms)) :
							foreach ($terms as $term) :
							$term_link = get_term_link($term);
					?>
						<li>
							<a href="<?php echo esc_url($term_link); ?>">
								<?php echo esc_html($term->name); ?>
								<span class="count">(<?php echo $term->count; ?>)</span>
							</a>
						</li>
				<?php endforeach; endif;?>
				</ul>
					<ul class="category-list">
    			<?php
    				$terms = get_terms(array(
        			'taxonomy' => 'works-category', // タクソノミー名
        			'hide_empty' => false,      // 投稿がないカテゴリーは隠す
    				));

    				if (!empty($terms) && !is_wp_error($terms)) :
        			foreach ($terms as $term) :
            	$term_link = get_term_link($term);
    			?>
            <li>
							<a href="<?php echo esc_url($term_link); ?>">
								<?php echo esc_html($term->name); ?>
								<span class="count">(<?php echo $term->count; ?>)</span>
							</a>
            </li>
    		<?php endforeach; endif;?>
			</div>
				<div class="list_contents" style="width: 80%; display: flex; flex-wrap: wrap;">
					<?php
						$paged = get_query_var('paged') ? get_query_var('paged') : 1;
						$args = array(
    					'post_type'      => 'works',         // 投稿タイプ
    					'posts_per_page' => 12,             // 最大表示数
    					'paged'          => $paged,
    					'orderby'        => 'menu_order',   // メニュー順でソート
    					'order'          => 'DESC',         // 降順でソート
    					'post_status'    => 'publish'
						);

						// タクソノミーアーカイブ（カテゴリーページなど）を表示している場合
						if ( is_tax() ) {
    					$queried_obj = get_queried_object();
    					$args['tax_query'] = array(
        				array(
									'taxonomy' => $queried_obj->taxonomy, // 現在のタクソノミー名（categoryなど）
									'field'    => 'slug',
									'terms'    => $queried_obj->slug,     // 現在のタームのスラッグ
        				),
    					);
						}

						$the_query = new WP_Query($args);

						if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

						<?php
							$post_id = get_the_ID();
							$page_ttl = get_the_title($post_id);
							// サムネイルURLを取得
							$image = get_the_post_thumbnail_url($post_id, 'medium_large');
							// もしサムネイルが設定されていなければ、代替画像のパスを代入
							if ( ! $image ) {
								$image = get_template_directory_uri() . '/assets/images/default_thumb.jpg';
							}
						 ?>

						 <div class="dlog_item" style="width: 50%; margin-bottom: 50px;">
							<a style="font-size: 16px;" href="<?php the_permalink(); ?>">
								<span style="display: block;" class="img_wrap"><img loading="lazy" src="<?= esc_url($image) ?>" alt="<?= esc_attr($page_ttl) ?>" style="width: 100%;"></span>
								<span class="ttl_wrap" style="display: block;"><?= $page_ttl; ?></span>
								<span class=""><?= $date; ?></span>
							</a>
						</div>

						<?php endwhile; else :?>

						<p>現在、登録された投稿はありません。</p>

					<?php endif; wp_reset_postdata();?>
				</div>
			</div>
		</article>

<?php get_footer(); ?>
