
  <!--{if $tpl_title }-->
  <div class="page_path">
  	<span><a href="<!--{$smarty.const.TOP_URLPATH}-->">トップ</a></span>
    <!--{if $tpl_mainno == "products" }-->
      <!--{* 商品一覧・詳細画面のページパス *}-->
      <!--{section name=cnt loop=$tpl_navis }-->
        /　<!--{if $tpl_navis[cnt].url}--><a href="<!--{$tpl_navis[cnt].url}-->"><!--{/if}--><!--{$tpl_navis[cnt].label}--><!--{if $tpl_navis[cnt].url}--></a><!--{/if}-->
      <!--{/section}-->
    <!--{else}-->
      <!--{* その他画面のページパス *}-->
      <!--{if $tpl_title}-->
        <!--{if $tpl_subtitle && $tpl_subtitle != $tpl_title}-->
        / <a href="<!--{$smarty.const.TOP_URLPATH}--><!--{$tpl_mainno}-->"><!--{$tpl_title}--></a> <!--{* 1階層目ページパス *}-->
        / <!--{$tpl_subtitle}--> <!--{* 2階層目ページパス *}-->
        <!--{else}-->
        / <!--{$tpl_title}--> <!--{* 1階層目ページパス *}-->
        <!--{/if}-->
      <!--{/if}-->
    <!--{/if}-->
  </div>
  <!--{/if}-->
  <!--{if $tpl_is_login == 0}-->
  <div class="unmember_box">
  	<p>ユーザ登録後にご注文可能です。ログイン後商品価格も表示されます</p><span><a href="<!--{$smarty.const.TOP_URLPATH}-->entry/kiyaku.php"><img src="<!--{$TPL_URLPATH}-->img/page/navi/btn_member_regist.png" width="387" height="45" alt="指定事業者様ユーザ登録はこちら" /></a></span>
  </div>
  <!--{/if}-->