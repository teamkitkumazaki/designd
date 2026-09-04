<?php
/**
 * カスタム投稿名「Works」（post_type: 'works'）の詳細ページを表示するテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>


	<div class="contents pb0">

		<header class="work__header">
			<div class="work__header__container">
				<h3 class="work__header__title"><span><?php the_title(); ?></span></h3>
			</div>
		</header>

	</div>

	<div class="work__contents">
		<div class="work__container">

			<?php the_content(); ?>

		</div>
	</div>

	<section class="section-member" style="padding: 0 100px 100px; box-sizing: border-box;">
		<h2>Project Team</h2>
		<h3>DESIGND:</h3>
		<?php
			$post_id = $post->ID; //ポストID
			$staff_inner = SCF::get('staff_inner',$post_id);
			?>

		<?php foreach ($staff_inner as $d):?>
			<?php
				$post_ids = $d['related_member'];
				$member_role = $d['member_role'];
				$profile_img01 = SCF::get('profile_img01',$post_ids[0]);
				$profile_img01_url = wp_get_attachment_image_src($profile_img01, 'medium_large');
				$name_en = SCF::get('name_en',$post_ids[0]);
			?>

			<div class="member_item" style="border-bottom: 1px solid #d8d8d8; padding-bottom: 40px; margin-bottom: 40px;">
				<div class="img_item" style="max-width: 300px;">
					<img loading="lazy" class="portrait" src="<?= $profile_img01_url[0] ?>" style="width: 100%;">
				</div>
				<div class="member_detail">
					<?php if ($name_en):?><p class="name_en"><?= $name_en;?></p><?php endif; ?>
					<?php if ($member_role):?><p><?= $member_role;?></p><?php endif; ?>
				</div>
			</div>

		<?php endforeach; ?>

		<h3>Design Partner</h3>
		<?php $staff_partner = SCF::get('staff_partner',$post_id);?>

		<?php foreach ($staff_partner as $d):?>
			<?php
				$partner_name = $d['partner_name'];
				$partner_role = $d['partner_role'];
			?>

			<div class="partner_item">
				<h3 class="partner_name"><?= $partner_name;?></h3>
				<p class="partner_role"><?= $partner_role;?></p>
			</div>

		<?php endforeach; ?>

	</section>

	<section class="section-dialogue" style="padding: 0 100px 100px; box-sizing: border-box;">
		<h3>Dialogue</h3>

		<?php $dialogue = SCF::get('dialogue',$post_id);?>
		<?php foreach ($dialogue as $d):?>
			<?php
				$post_ids = $d['related_dialogue'];

				$page_ttl = get_the_title($post_ids);
				$date = get_the_date('Y.m.d');
				$image = get_the_post_thumbnail_url($post_ids, 'full');
				$image_sp = get_the_post_thumbnail_url($post_ids, 'medium_large');
				$interview_member = SCF::get('interview_member',$post_ids);
			?>

			<div class="interview_item" style="border-bottom: 1px solid #d8d8d8; padding-bottom: 40px; margin-bottom: 40px;">
				<a style="font-size: 16px;" href="<?php the_permalink();?>">
					<span style="display: block;" class="img_wrap"><img loading="lazy" class="portrait" src="<?= $image_sp ?>"></span>
					<span class="interview_ttl" style="display: block;"><?= $page_ttl;?></span>
					<span class="interview_detail">
						<span class="interviewee" style="display: block;"><?= $interview_member;?></span>
						<span class="date" style="display: block;"><?= $date;?></span>
					</span>
				</a>
			</div>
		<?php endforeach; ?>
	</section>


<?php
	/* カスタムテンプレート「recommend.php」をインクルードします。 */
	get_template_part( 'template-parts/recommend' );
?>

<?php get_footer(); ?>
