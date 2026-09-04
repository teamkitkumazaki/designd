<?php
/**
 * プライバシーポリシーを表示するテンプレートです。
 *
 * @package DESIGN:D ver2
 */
?>
<div id="privacy-modal" class="modal__container font-en" style="display: none;">
	<div class="privacy__container">
		<?php
		$privacy = get_page_by_path('privacy');
		if ($privacy) echo apply_filters('the_content', $privacy->post_content);
		?>
	</div>
</div>