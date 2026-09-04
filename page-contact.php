<?php
/**
 * 固定ページ「Contact」（slug: contact）のテンプレートです。
 *
 * @package DESIGN:D ver2
 */
get_header(); ?>

<section id="contact" class="dflt anim-trigger maincontents">
	<div class="section_inner two-column">
		<div class="first_block sticky_container">
			<div class="sticky_inner">
				<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger" data-text="お問い合わせ">Contact</h3>
				<div class="util_box sp_inset">
					<p class="attention">
						以下のフォームに必要事項をご入力の上ご連絡ください。<br>
						<span class="red">※は全て入力必須です。</span>
					</p>
				</div>
			</div>
		</div>
		<div class="contents_block font-sub">
			<div class="contents_inner sp_inset">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
