<?php
/**
 * ヘッダーのグローバルメニューを表示するテンプレートです。
 *
 * @package DESIGN:D ver2
 */
?>
			<nav id="js-navigation" class="header__navigation" data-lenis-prevent>
				<ul>
					<li class="navigation-about"><a href="<?php echo esc_url( home_url() ); ?>/about/" class="animsition-link" data-animsition-out-class="fade-out-up-sm">About</a></li>
					<li class="navigation-works"><a href="<?php echo esc_url( home_url() ); ?>/works/" class="animsition-link" data-animsition-out-class="fade-out-up-sm">Works</a></li>
					<li class="navigation-dialogue"><a href="<?php echo esc_url( home_url() ); ?>/dialogue/" class="animsition-link" data-animsition-out-class="fade-out-up-sm">Dialogue</a></li>
					<li class="navigation-abuout"><a href="<?php echo esc_url( home_url() ); ?>/about/#aboutus__member" class="animsition-link" data-animsition-out-class="fade-out-up-sm">Member</a></li>
					<li class="navigation-contact"><a href="<?php echo esc_url( home_url() ); ?>/contact/" class="animsition-link" data-animsition-out-class="fade-out-up-sm">Contact</a></li>
					<li class="navigation-dlog"><a href="<?php echo esc_url( home_url() ); ?>/dlog/" class="animsition-link" data-animsition-out-class="fade-out-up-sm">D<i class="colon--mark">:</i>Log</a></li>
				</ul>

				<div class="navi_container2">
					<dl class="navi_container_inner1">
						<dt class="font-en">
							Location
						</dt>
						<dd>
							<a class="location_jp hover_dflt" href="https://maps.app.goo.gl/F4WYSFbRom6VFdFu7" target="_blank">
								151-0013<br>
								東京都渋谷区神宮前5-13-10<br>
								ググランドメゾン神宮前401
							</a>
						</dd>
					</dl>
					<dl class="navi_container_inner2">
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
				<div class="navi_container3">
					<div class="footer_util">
						<a href="#privacy-modal" class="hover_dflt font-en inline-modal">Privacy Policy</a>
						<p class="copyright">
							&copy;Designd Est.2020
						</p>
					</div>
				</div>
			</nav>