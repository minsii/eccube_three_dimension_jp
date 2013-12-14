
<script language="javascript">
jQuery(document).ready(function(){

	//  テキストボックスの総数
	var inputLength = $(":text").length;
	//  テキストボックス初期値　を格納
	var inputBox = new Array();
	for (i=0; i<inputLength; i++) {
		inputBox[i] = $("input[type='text']").eq(i).val();
	}
});
</script>
  <!--検索ボックス-->
  <form name="product_search_form" id="product_search_form" method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
  <section class="search_navi_box">
  	<div>
        <label>カタログ品番検索</label>
      	<span>
          <input type="text" name="name" value="<!--{$smarty.get.name|escape}-->"/>
          <input type="image" src="<!--{$TPL_URLPATH}-->img/page/common/btn_search_navi_btn.png"/>
          </span>
          <label>キーワード検索</label>
          <span>
          <input type="text" name="keyword"  value="<!--{$smarty.get.keyword|escape}-->"/>
          <input type="image" src="<!--{$TPL_URLPATH}-->img/page/common/btn_search_navi_btn.png"/>
          </span>
          <label>メーカー検索</label>
          <span>
          <input type="text" name="maker"  value="<!--{$smarty.get.maker|escape}-->"/>
          <input type="image" src="<!--{$TPL_URLPATH}-->img/page/common/btn_search_navi_btn.png"/>
        </span>
    </div>
  </section>
  </form>
