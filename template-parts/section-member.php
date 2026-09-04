<?php
/**
 * Project Team セクション
 * 呼び出し例: get_template_part('template-parts/section-member', null, ['post_id' => $post_id]);
 *
 * @package DESIGN:D ver2
 */
$post_id       = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$staff_inner   = SCF::get('staff_inner', $post_id);
$staff_partner = SCF::get('staff_partner', $post_id);
?>
<section class="section-member dflt anim-trigger">
	<div class="section_inner two-column">
		<div class="first_block">
			<div class="sticky_inner">
				<p class="sholder font-en">Project Team</p>
			</div>
		</div>
		<div class="contents_block">
			<?php
				$valid_staff_inner = array_filter($staff_inner, function($d) {
					return !empty($d['related_member']);
				});
			?>
			<?php if ($valid_staff_inner): ?>
			<p class="sholder font-en anim-trigger-fade">DESIGND:</p>
			<ul class="member_list anim-trigger">
				<?php foreach ($valid_staff_inner as $d):?>
					<?php
						$post_ids          = $d['related_member'];
						$member_role       = $d['member_role'];
						$profile_img01     = SCF::get('profile_img01', $post_ids[0]);
						$profile_img01_url = wp_get_attachment_image_src($profile_img01, 'medium_large');
						$name_en           = SCF::get('name_en', $post_ids[0]);
					?>
					<?php if ($profile_img01_url):?>
					<li class="member_item">
						<div class="img_item">
							<img loading="lazy" decoding="async" class="portrait" src="<?= $profile_img01_url[0] ?>">
						</div>
						<div class="member_detail">
							<?php if ($name_en):?><p class="name_en name font-en"><?= $name_en;?></p><?php endif; ?>
							<?php if ($member_role):?><p class="name_en role font-en"><?= $member_role;?></p><?php endif; ?>
						</div>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<?php
				$valid_staff_partner = array_filter($staff_partner, function($d) {
					return !empty($d['partner_name']);
				});
			?>
			<?php if ($valid_staff_partner): ?>
			<p class="sholder font-en anim-trigger-fade">Design Partner</p>
			<ul class="partner_list anim-trigger">
				<?php foreach ($valid_staff_partner as $d):?>
					<?php
						$partner_name = $d['partner_name'];
						$partner_role = $d['partner_role'];
					?>
					<li class="partner_item">
						<p class="name font-en"><?= $partner_name;?></p>
						<p class="role font-en"><?= $partner_role;?></p>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
