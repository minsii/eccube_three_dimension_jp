
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
  <section class="search_navi_box">
  	<div>
        <label>カタログ品番検索</label>
    	<span>
        <input type="text" name="catalog_inputbox" value="カタログ品番検索"/>
        <input type="image" src="<!--{$TPL_URLPATH}-->img/page/common/btn_search_navi_btn.png"/>
        </span>
        <label>キーワード検索</label>
        <span>
        <input type="text" name="keyword_inputbox"  value="キーワード検索"/>
        <input type="image" src="<!--{$TPL_URLPATH}-->img/page/common/btn_search_navi_btn.png"/>
        </span>
        <label>メーカー検索</label>
        <span>
        <input type="text" name="maker_inputbox"  value="メーカー検索"/>
        <input type="image" src="<!--{$TPL_URLPATH}-->img/page/common/btn_search_navi_btn.png"/>
        </span>
    </div>
  </section>