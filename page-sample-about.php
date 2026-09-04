<?php
/**
 * 固定ページ「About」を表示するためのデフォルトテンプレートです。
  * Template Name: About(Sample)
 *
 * @package DESIGN:D
 */
get_header(); ?>


<article id="member" class="page-member" style="padding: 300px 100px; box-sizing: border-box;">
	<h1>Member Of DESIGND</h1>
	<section class="member-list">
		<?php
			$order = 0;
			$param = array(
				'post_type' => 'member',
				'posts_per_page' => -1,
				'post_status'  => 'publish',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'paged' => $paged,
			);
			$the_query = new WP_Query( $param );
			$wp_query->query($param);
			if($wp_query->have_posts()): while($wp_query->have_posts()) : $wp_query->the_post();
		?>
		<?php
			$order = intval($order) + intval(1);
			$post_id = get_the_ID();
			$page_ttl = get_the_title($post_id);
			$name_en = SCF::get('name_en',$post_id);
			$date = get_the_date('Y.m.d');
			$profile_img01 = SCF::get('profile_img01',$post_id);
			$profile_img01_url = wp_get_attachment_image_src($profile_img01, 'medium_large');
			$profile_img02 = SCF::get('profile_img02',$post_id);
			$profile_img02_url = wp_get_attachment_image_src($profile_img02, 'medium_large');
			$role01 = SCF::get('role01',$id);
			$role02 = SCF::get('role02',$post_id);
			$sns_instagram = SCF::get('sns_instagram',$post_id);
			$sns_facebook = SCF::get('sns_facebook',$post_id);
			$sns_x = SCF::get('sns_x',$post_id);
			$profile_txt = SCF::get('profile_txt',$post_id);

			$client_logo_url = wp_get_attachment_image_src($client_logo, 'medium_large');
			/* カテゴリー */
			$terms = get_the_terms($post->ID, 'news-category');
			if ($terms) :
				foreach ($terms as $term) {
					$category_name = $term->name;
					$category_slug = $term->slug;
				}

			endif;
		 ?>
		<div class="member_item" style="border-bottom: 1px solid #d8d8d8; padding-bottom: 40px; margin-bottom: 40px;">
			<div class="member_img_wrap" style="display: flex; flex-wrap: wrap;">
				<div class="img_item" style="max-width: 300px;">
					<img loading="lazy" class="portrait" src="<?= $profile_img01_url[0] ?>" style="width: 100%;">
				</div>
				<div class="img_item" style="max-width: 300px;">
					<img loading="lazy" class="portrait" src="<?= $profile_img02_url[0] ?>" style="width: 100%;">
				</div>
			</div>
			<div class="member_detail">
				<?php if ($page_ttl):?><h3 class="member_detail_name"><?= $page_ttl;?></h3><?php endif; ?>
				<?php if ($name_en):?><p class="name_en"><?= $name_en;?></p><?php endif; ?>
				<div class="member_detail_role">
					<?php if ($role01):?><div class="role01"><?= $role01;?></div><?php endif; ?>
					<?php if ($role01):?><div class="role02"><?= $role02;?></div><?php endif; ?>
				</div>
				<div class="detail_lower">
					<div class="sns_wrap">
						<?php if ($sns_instagram):?><a target="_blank" href="<?=$sns_instagram;?>">Instagram</a><?php endif; ?>
						<?php if ($sns_facebook):?><a target="_blank" href="<?=$sns_facebook;?>">Facebook</a><?php endif; ?>
						<?php if ($sns_x):?><a target="_blank" href="<?=$sns_x;?>">X</a><?php endif; ?>
					</div>
					<?php if ($profile_txt):?>
					<div class="profile_detail_button">
						<button>Profile</button>
					</div>
					<?php endif; ?>
				</div>
				<?php if ($profile_txt):?>
				<div class="profile_description">
					<p style="white-space:pre-line"><?= $profile_txt;?></p>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endwhile; else : endif; wp_reset_postdata();?>
	</section>
</article>

<?php get_footer(); ?>
