<?php
  global $wp;
  $url = home_url( add_query_arg( array(), $wp->request ) );
;?>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="format-detection" content="telephone=no">

<!-- METAS -->
<?php
if ( is_home() || is_front_page() ) {
  $site_title = 'デザインで株式会社｜ブランドの課題をデザインで解決するクリエイティブカンパニー';
  $site_permalink = home_url( '/' );
  $thumnail = get_template_directory_uri().'/assets/img/ogp/ogp2.jpg';
  $description = "企業やサービスの課題解決を軸に、ディレクション・制作を行うデザインエージェンシー。伴走型のブランディングプロジェクトでブランディングや経営課題など上流の課題に対しても広義のデザインで向き合います。";
}else if( is_404()){
  $site_title = 'ページがみつかりません | デザインで株式会社';
  $site_permalink = get_the_permalink();
  $thumnail = get_template_directory_uri().'/assets/img/ogp/ogp2.jpg';
  $description = "企業やサービスの課題解決を軸に、ディレクション・制作を行うデザインエージェンシー。伴走型のブランディングプロジェクトでブランディングや経営課題など上流の課題に対しても広義のデザインで向き合います。";
}	else if( is_tax()) {
  $page_title = single_term_title("", false).' | デザインで株式会社';
  $site_title = single_term_title("", false).' | デザインで株式会社';
  $site_permalink = get_the_permalink();
  $description = strip_tags(term_description());
}	else if( is_search()) {
  $page_title = '「'.get_search_query( $escaped ).'」の検索結果 | デザインで株式会社';
  $site_title = '「'.get_search_query( $escaped ).'」の検索結果 | デザインで株式会社';
  $site_permalink = get_the_permalink();
  $description = 'デザインで株式会社のキーワード検索結果ページです。';
} else if( is_category() || is_tag() ){
  $page_title = '「'.single_cat_title('',false).'」に関する記事一覧 | デザインで株式会社';
  $site_title = '「'.single_cat_title('',false).'」に関する記事一覧 | デザインで株式会社';
  $site_permalink = get_the_permalink();
  $cat_desc = category_description();
  if($cat_desc){
    $description = strip_tags($cat_desc);
  }else{
    $description = 'デザインで株式会社の'.single_cat_title('',false).'に関する記事一覧ページです。';
  }
} else if( is_archive() ){
  if(strstr($url,'dialogue')){
    $page_title = 'Dialogue | デザインで株式会社';
    $site_title = 'Dialogue | デザインで株式会社';
    $site_permalink = get_the_permalink();
    $description = 'デザインで株式会社がデザインで向き合わせて頂いた企業様との対談コンテンツを定期的に配信します。';
  }
  if(strstr($url,'works')){
    $page_title = 'Works | デザインで株式会社';
    $site_title = 'Works | デザインで株式会社';
    $site_permalink = get_the_permalink();
    $description = 'デザインで株式会社がデザインで向き合う、さまざまな課題解決の事例や、ブランディング、リブランディング、領域を超えたクリエイティブのプロジェクトをご紹介します。';
  }
  if(strstr($url,'dlog')){
    $page_title = 'Dlog | デザインで株式会社';
    $site_title = 'Dlog | デザインで株式会社';
    $site_permalink = get_the_permalink();
    $description = 'デザインで株式会社からのお知らせ。';
  }
} else if( is_single()) {
  $site_title = get_the_title($post->ID).' | デザインで株式会社';
  $site_permalink = get_the_permalink($post->ID);
  if(strstr($url,'dialogue')){
    $description = strip_tags(get_post_meta($post->ID, 'interview_lead', true));
  }
  if(strstr($url,'works')){
    $repeat_group = SCF::get('article_contents');
    $first_description = $repeat_group[0]['article_description'];
    $first_description = str_replace(["\r\n", "\r", "\n"], '', $first_description);
    $description = strip_tags($first_description);
  }
  if(strstr($url,'dlog')){
    $description = get_the_excerpt($post->ID);
  }

  if (!empty(get_the_post_thumbnail_url($post->ID, 'large'))) {
    $image = get_the_post_thumbnail_url($post->ID, 'large');
    $thumnail = $image;  // サムネイル画像を出力
  } else if ($first_image != 'no_image') {
    $thumnail = $first_image; // function.php定義した投稿1枚目の画像を出力
  } else {
    $thumnail = get_template_directory_uri().'/assets/img/ogp/ogp.jpg'; // デフォルトのサムネイル画像を出力
  }
}else if(is_page()){
  $site_title = get_the_title($post->ID).' | デザインで株式会社';
  $site_permalink = get_the_permalink($post->ID);
  $description = strip_tags(get_post_meta($post->ID, 'description', true));
  if (!empty(get_the_post_thumbnail_url($post->ID, 'large'))) {
    $image = get_the_post_thumbnail_url($post->ID, 'large');
    $thumnail = $image;  // サムネイル画像を出力
  } else {
    $thumnail = get_template_directory_uri().'/assets/img/ogp/ogp.jpg'; // デフォルトのサムネイル画像を出力
  }
} else{
  $page_title = 'デザインで株式会社';
  $site_title = 'デザインで株式会社';
  $site_permalink = get_the_permalink();
  $description = "企業やサービスの課題解決を軸に、ディレクション・制作を行うデザインエージェンシー。伴走型のブランディングプロジェクトでブランディングや経営課題など上流の課題に対しても広義のデザインで向き合います。";
  $thumbnail_id = get_post_thumbnail_id($post->ID);
  $site_image_attach = wp_get_attachment_image_src( $thumbnail_id, 'large' );
  if (!empty($site_image_attach)) {
    $site_image = $site_image_attach[0];
  }
}

  if (empty($description)) {
    $description = "企業やサービスの課題解決を軸に、ディレクション・制作を行うデザインエージェンシー。伴走型のブランディングプロジェクトでブランディングや経営課題など上流の課題に対しても広義のデザインで向き合います。";
  }

  /* is_tax() / is_search() / is_category() / is_tag() / is_archive() など、
     上記の分岐で $thumnail が設定されないケースのフォールバック。
     未定義変数のままだとPHPの警告文がページに出力されてしまうため必ず定義する。 */
  if (empty($thumnail)) {
    $thumnail = get_template_directory_uri().'/assets/img/ogp/ogp2.jpg';
  }

  if (empty($site_title)) {
    $site_title = 'デザインで株式会社';
  }

  if (empty($site_permalink)) {
    $site_permalink = home_url( '/' );
  }

  $site_image = "";

?>
<!-- 表示速度改善(第三段階・PJAX対応): data-pjax-meta を付けたタグは
     assets/js/pjax.js がページ遷移後に新しいページの内容へ差し替える対象です。 -->
<title data-pjax-meta="title"><?php echo $site_title; ?></title>
<?php if(strstr($url,'/selectbox') || strstr($url,'/subsingle') || strstr($url,'/subset') || strstr($url,'/subcustom')):?>
  <meta name="robots" content="noindex,nofollow" data-pjax-meta="robots" />
<?php endif;?>
<meta property="og:title" content="<?php echo $site_title; ?>" data-pjax-meta="og:title">
<meta property="og:type" content="article" data-pjax-meta="og:type">
<meta property="og:url" content="<?php echo $site_permalink; ?>" data-pjax-meta="og:url">
<meta property="og:image" content="<?php echo $thumnail; ?>" data-pjax-meta="og:image">
<meta name="description" content="<?php echo $description; ?>" data-pjax-meta="description">
<meta property="og:locale" content="ja_JP">
<meta property="og:description" content="<?php echo $description; ?>" data-pjax-meta="og:description">
<meta property="og:site_name" content="デザインで株式会社">
<meta name="twitter:card" content="summary">
<meta name="twitter:description" content="<?php echo $description; ?>" data-pjax-meta="twitter:description">
<meta name="twitter:title" content="<?php echo $site_title; ?>" data-pjax-meta="twitter:title">
<meta name="twitter:image" content="<?php echo $thumnail; ?>" data-pjax-meta="twitter:image">
