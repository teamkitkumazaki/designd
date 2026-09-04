<?php
/**
 * ページのフッター部分を表示するテンプレートです。
 *
 * @package DESIGN:D ver2
 */
?>
	<section class="get-in-touch">
		<div class="get-in-touc__bg">
			<video loop muted playsinline autoplay preload="metadata">
				<source src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/video/movie_footer.mp4" type="video/mp4">
			</video>
		</div>
	</section>

	<footer class="footer" role="contentinfo">
		<div class="footer__container">
			<div class="footer_block1">
				<dl>
					<dt class="font-en">Contact</dt>
					<dd>
						<a href="<?php echo esc_url( home_url() ); ?>/contact/" class="hover_dflt animsition-link cursor-trigger">ブランディングのご相談や取材等の<br>
						お問い合わせはこちらからお願いします。</a>
					</dd>
				</dl>
			</div>
			<div class="footer_block2">
				<dl class="footer_block2_inner">
					<dt class="font-en">
						Location
					</dt>
					<dd>
						<p class="location_ttl font-en">Tokyo Office</p>
						<a class="location_en font-en hover_dflt" href="https://maps.app.goo.gl/F4WYSFbRom6VFdFu7" target="_blank">
							#401 Grand-Masion Jinguumae,<br>
							5-13-10, Shibuya-Ku, Tokyo<br>
							150-0001 Japan
						</a>
						<a class="location_jp hover_dflt" href="https://maps.app.goo.gl/F4WYSFbRom6VFdFu7" target="_blank">
							150-0001<br>
							東京都渋谷区神宮前5-13-10<br>
							グランドメゾン神宮前401
						</a>
					</dd>
				</dl>
				<dl class="footer_block2_inner">
					<dt class="font-en">
						Social Media
					</dt>
					<dd>
						<a class="hover_dflt font-en" href="https://note.com/designd_saito" target="_blank">note</a>
						<a class="hover_dflt font-en" href="https://www.facebook.com/DESIGNDINC" target="_blank">Facebook</a>
						<a class="hover_dflt font-en" href="https://www.instagram.com/designd__official/" target="_blank">Instagram</a>
					</dd>
				</dl>
			</div>
			<div class="footer_block3">
				<a href="<?php echo esc_url( home_url() )?>" class="footer_logo animsition-link">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo.svg" alt="<?php bloginfo( 'name' ); ?>">
				</a>
				<div class="footer_util">
					<a href="#privacy-modal" class="hover_dflt font-en inline-modal">Privacy Policy</a>
					<p class="copyright font-en">
						&copy;Designd Est.2020
					</p>
				</div>
			</div>
			<div class="footer__logomark">
				<div class="logomark">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo_mark.svg" alt="">
				</div>
			</div>
		</div>
	</footer>

</div>
<div class="cursor_tip">
	<p class="tip"></p>
	<div class="hover_cursor">
		<span class="arrow"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/cursor_arrow.svg" alt=""></span>
	</div>
</div>
<div class="cover"></div>
<div id="js-opening" class="opening">
	<h2 class="opening__logo"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo_opening.svg" alt=""></h2>
</div>


<?php
	/* カスタムテンプレート「privacy.php」をインクルードします。 */
	get_template_part( 'template-parts/privacy' );
	/* カスタムテンプレート「script.php」をインクルードします。 */
	get_template_part( 'template-parts/script' );
?>
<?php wp_footer(); ?>
</body>
</html>