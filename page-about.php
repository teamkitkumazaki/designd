<?php
/**
 * 固定ページ「About」を表示するためのデフォルトテンプレートです。
  * Template Name: About
 *
 * @package DESIGN:D vdr2
 */
get_header(); ?>

<section id="aboutus__msg" class="dflt maincontents">
	<div class="section_inner two-column">
		<div class="first_block">
			<div class="fix_inner">
				<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger" data-text="私たちについて">About Us</h3>
				<div class="mv_container">
					<video loop muted playsinline autoplay preload="metadata" poster="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/video_about1_poster.jpg">
						<source src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/video/DESIGN-D_about_1.mp4" type="video/mp4">
					</video>
				</div>
			</div>
		</div>
		<div class="contents_block font-sub">
			<h4><span>私たちが考える「デザイン」</span></h4>
			<p class="anim-trigger font-sub">
				<span>
					つべこべ言わなくても伝わる<br>
					「言葉がいらないデザイン」は素晴らしい。<br>
					デザインは引き算の美学でもあり、<br>
					それを目指すことは厭わない。<br>
				</span>
				<span>でも、<br></span>
				<span>
					美しいと感じるものが人それぞれ違うように、<br>
					デザインされたモノやコトの解釈は<br>
					受け手に委ねられ、ひとり歩きしていく。<br>
					ましてやAIでデザインしてもいい時代。<br>
					だからこそ、デザインにはストーリーが必要だ。
				</span>
			</p>
		</div>
	</div>
</section>

<section id="aboutus__sec2" class="dflt">
	<div class="section_inner two-column dark">
		<div class="first_block sticky_container">
			<div class="sticky_inner">
				<div class="h3_stack">
					<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger white h3_1" data-text="私たちができること">
						What We Can Do
					</h3>
					<h3 class="font-en deco_w-dot tip-trigger txt_split white h3_2 pc" data-text="仕事の流れ">
						Work Flow
					</h3>
				</div>

				<div class="mv_container anim-trigger" data-trigger-timing="50">
					<video loop muted playsinline autoplay preload="metadata" poster="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/video_about2_poster.jpg">
						<source src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/video/DESIGN-D_about_2.mp4" type="video/mp4">
					</video>
				</div>
			</div>
		</div>
		<div class="contents_block">
			<div class="block_inner inner1">
				<h4 class="anim-trigger font-sub"><span>私たちの強み</span></h4>
				<p class="anim-trigger-fade cont1 font-sub" data-trigger-timing="50">
					企業やサービスの課題解決を軸に、<br>
					ディレクション・製作を行う<br class="sp">デザインエージェンシーです。<br>
					受注作業として最終アウトプットの<br>
					狭義のデザインだけではなく、<br>
					ブランディングや経営課題など<br class="sp">上流の課題に対しても<br>
					広義のデザインで向き合います。
				</p>
				<p class="anim-trigger-fade cont1 font-sub" data-trigger-timing="50">
					デザインによる課題解決に漠然とでも可能性を<br>
					感じていらっしゃるクライアントさまとは、<br>
					高いクオリティかつ<br class="sp">課題解決につながるアウトプットを<br>
					生み出すことができます。<br>
					弊社は企画・ディレクションだけでなく、<br>
					常にデザインでプロトタイピングした<br>
					カタチを元に対話を重ねていきます。
				</p>
				<p class="anim-trigger-fade cont1 font-sub" data-trigger-timing="50">
					ビジョンやコンセプトなどのワーディングから、<br>
					アウトプットのディテールの手触りまで、<br>
					同時並行で検証を進めることが可能です。<br>
					デザインで向き合わせて頂いた<br class="sp">クライアントさまから、<br>
					アウトプットもさることながら、<br>
					プロセス自体に価値を感じたという<br class="sp">お声も頂いています。
				</p>
			</div>
			<div class="block_inner inner2">
				<h3 class="font-en deco_w-dot anim-trigger tip-trigger txt_split white h3_2 sp" data-text="仕事の流れ">
					Work Flow
				</h3>
				<h5 class="font-en anim-trigger txt_split">DESIGND:BRANDING</h5>
				<dl class="anim-trigger" data-trigger-timing="50">
					<dt class="font-sub"><span class="num font-en">0</span>
						ご依頼・ご相談
					</dt>
					<dd>
						<p class="">
							ブランディングに関する課題感を気軽にご相談ください
						</p>
					</dd>
				</dl>
				<dl class="anim-trigger" data-trigger-timing="100">
					<dt class="font-sub"><span class="num font-en">1</span>
						制作物の御見積とスケジュールのご提案
					</dt>
					<dd>
						<p class="">
							ご相談内容に基づいたプロジェクト設計書（スコープ・スケジュール・コスト）をご提案いたします
						</p>
						<p class="attention">
							※0〜1の段階では費用が発生いたしません
						</p>
					</dd>
				</dl>
				<dl class="anim-trigger" data-trigger-timing="100">
					<dt class="font-sub"><span class="num font-en">2</span>
						ご契約
					</dt>
					<dd>
						<p class="" data-trigger-timing="100">
							1で合意した内容に基づいて契約を締結いたします
						</p>
					</dd>
				</dl>
				<dl class="anim-trigger" data-trigger-timing="100">
					<dt class="font-sub"><span class="num font-en">3</span>
						ブランディングプロジェクト実施
					</dt>
					<dd>
						<p class="attention">
							※期間は1で設計した期間で実施いたします<br>
							※ブランディングプロジェクトの費用は基本毎月末ご請求いたします<br>
							※アウトプットのクリエイティブ制作費も含んでいます
						</p>
					</dd>
				</dl>
				<dl class="anim-trigger" data-trigger-timing="100">
					<dt class="font-sub"><span class="num font-en">4</span>
						プロジェクトクローズ
					</dt>
					<dd>
						<p class="">
							初期設定したアウトプットの納品を全て完了してプロジェクトを終了いたします
						</p>
					</dd>
				</dl>
			</div>
			<div class="block_inner inner3">
				<h5 class="font-en anim-trigger txt_split">DESIGND:PRODUCTION</h5>
				<dl class="anim-trigger" data-trigger-timing="50">
					<dt class="font-sub"><span class="num font-en">0</span>
						ご依頼・ご相談
					</dt>
					<dd>
						<p class="">
							クリエイティブ制作の具体的なご要望を元に制作物を擦り合わせいたします
						</p>
					</dd>
				</dl>
				<dl class="anim-trigger" data-trigger-timing="100">
					<dt class="font-sub"><span class="num font-en">1</span>
						制作物の御見積とスケジュールのご提案
					</dt>
					<dd>
						<p class="font-sub">
							0で擦り合わせをした制作に関わるお見積と制作進行スケジュールをご提案いたします
						</p>
						<p class="attention">
							※0〜1の段階では費用が発生いたしません
						</p>
					</dd>
				</dl>
				<dl class="anim-trigger" data-trigger-timing="100">
					<dt class="font-sub"><span class="num font-en">2</span>
						ご契約
					</dt>
					<dd>
						<p class="">
							1で合意した内容に基づいて契約を締結いたします
						</p>
					</dd>
				</dl>
				<dl class="anim-trigger" data-trigger-timing="100">
					<dt class="font-sub"><span class="num font-en">3</span>
						クリエイティブ制作実施
					</dt>
					<dd>
						<p class="">
							1で合意した内容に基づいて契約を締結いたします
						</p>
						<p class="attention">
							※1で設計したスケジュールに基づいて実施いたします
						</p>
					</dd>
				</dl>
				<dl class="anim-trigger test" data-trigger-timing="100">
					<dt class="font-sub"><span class="num font-en">4</span>
						クリエイティブ納品＆ご請求
					</dt>
					<dd>
						<p class="">
							納品物の検収が完了次第費用をご請求いたします
						</p>
					</dd>
				</dl>
			</div>
		</div>
	</div>
</section>
<section id="aboutus__member" class="dflt">
	<div class="section_inner one-column">
		<div class="h3_wrapper">
			<h3 class="font-en deco_w-dot txt_split anim-trigger tip-trigger" data-text="デザインでメンバー">Member Of DESIGND:</h3>
		</div>
		<div class="contents_block">
			<div class="member_container">
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
					if($the_query->have_posts()): while($the_query->have_posts()) : $the_query->the_post();
				?>
				<?php
					$order = intval($order) + intval(1);
					$post_id = get_the_ID();
					$page_ttl = get_the_title($post_id);
					$name_en = SCF::get('name_en',$post_id);
					$date = get_the_date('Y.m.d');
					$profile_img01 = SCF::get('profile_img01',$post_id);
					$profile_img01_url = wp_get_attachment_image_src($profile_img01, 'medium');
					$profile_img02 = SCF::get('profile_img02',$post_id);
					$profile_img02_url = wp_get_attachment_image_src($profile_img02, 'medium');
					$role01 = SCF::get('role01',$id);
					$role02 = SCF::get('role02',$post_id);
					$sns_instagram = SCF::get('sns_instagram',$post_id);
					$sns_facebook = SCF::get('sns_facebook',$post_id);
					$sns_x = SCF::get('sns_x',$post_id);
					$profile_txt = SCF::get('profile_txt',$post_id);

				?>
				<div class="member_item anim-trigger" data-trigger-timing="100">
					<div class="member_img_wrap">
						<div class="img_item">
							<img loading="lazy" decoding="async" class="portrait" src="<?= $profile_img01_url[0] ?>">
						</div>
						<div class="img_item">
							<img loading="lazy" decoding="async" class="portrait" src="<?= $profile_img02_url[0] ?>">
						</div>
					</div>
					<div class="member_detail accordion-parent">
						<?php $name_jp = ($page_ttl)? $page_ttl : '';?><p class="name_en tip-trigger font-en" data-text="<?= $name_jp;?>"><?= $name_en;?></p>
						<div class="member_detail_role font-en">
							<?php if ($role01):?><div class="role01"><?= $role01;?></div><?php endif; ?>
							<?php if ($role02):?><div class="role02"><?= $role02;?></div><?php endif; ?>
						</div>
						<div class="detail_lower">
							<div class="sns_wrap font-en">
								<?php if ($sns_x):?><a target="_blank" href="<?=$sns_x;?>" class="hover_dflt">X</a><?php endif; ?>
								<?php if ($sns_facebook):?><a target="_blank" href="<?=$sns_facebook;?>" class="hover_dflt">Fb</a><?php endif; ?>
								<?php if ($sns_instagram):?><a target="_blank" href="<?=$sns_instagram;?>" class="hover_dflt">Ig</a><?php endif; ?>
							</div>
							<?php if ($profile_txt):?>
							<a href="" class="profile_btn accordion-trigger hover_dflt">Profile<span class="icon"></span></a>
							<?php endif; ?>
						</div>
						<?php if ($profile_txt):?>
						<div class="profile_description accordion-target">
							<div class="accordion-inner">
								<p><?= $profile_txt;?></p>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php endwhile; else : endif; wp_reset_postdata();?>
			</div>
		</div>

		<div class="contents_block" id="partners">
			<h4 class="font-en tip-trigger anim-trigger" data-text="パートナー"><span>Partners</span></h4>
			<div class="list_wrapper anim-trigger">
				<ul class="font-en anim-trigger">
					<li>Venture Capitalist</li>
					<li>Motion Graphics Designer</li>
					<li>Film Director</li>
					<li>Photographer</li>
					<li>Retoucher</li>
					<li>CG Artist</li>
					<li>Stylist &amp; Hair and Makeup Artist</li>
					<li>Prop Stylist</li>
					<li>Food Stylist</li>
					<li>Frontend Engineer</li>
					<li>Strategic Planner</li>
					<li>PR Planner</li>
				</ul>
				<ul class="font-en anim-trigger">
					<li>Creative Director</li>
					<li>Art Director</li>
					<li>Copywriter</li>
					<li>Editor</li>
					<li>Writer</li>
					<li>Product Designer</li>
					<li>Graphic Designer</li>
					<li>Web Designer</li>
					<li>Artist</li>
					<li class="blank">&nbsp;</li>
					<li class="blank">&nbsp;</li>
					<li>And More...</li>
				</ul>
			</div>
		</div>
		<div class="contents_block" id="awards">
			<h4 class="font-en tip-trigger anim-trigger" data-text="受賞歴"><span>Awards</span></h4>
			<div class="list_wrapper anim-trigger">
				<ul class="font-en anim-trigger">
					<li>D&amp;AD</li>
					<li>NY ADC</li>
					<li>One Show</li>
					<li>Cannes Lions</li>
					<li>Spikes Asia</li>
					<li>ADFEST</li>
					<li>GOOD DESIGN AWARD</li>
					<li>Tokyo Midtown Award</li>
					<li>Asahi Advertising Award</li>
					<li>And More…</li>
				</ul>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
