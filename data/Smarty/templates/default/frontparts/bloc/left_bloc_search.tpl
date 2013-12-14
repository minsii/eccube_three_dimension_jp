
    	<!--▼検索ボックス-->
      <form name="product_search_form" id="product_search_form" method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
    	<section class="search_box">
        	<h2>カタログ品番検索</h2>
            <div class="box">
            	<img src="<!--{$TPL_URLPATH}-->img/page/bloc/search_box/icon_search.png" />
              <input type="image" src="<!--{$TPL_URLPATH}-->img/page/bloc/search_box/btn_search_product.png" />
            	<input type="text" name="name" value="<!--{$smarty.get.name|escape}-->"/>
            </div>
        </section>
    	<section class="search_box">
        	<h2>キーワード検索</h2>
            <div class="box">
            	<img src="<!--{$TPL_URLPATH}-->img/page/bloc/search_box/icon_search.png" />
              <input type="image" src="<!--{$TPL_URLPATH}-->img/page/bloc/search_box/btn_search_product.png" />
            	<input type="text" name="keyword"  value="<!--{$smarty.get.keyword|escape}-->"/>
            </div>
        </section>
        <section class="search_box">
        	<h2>メーカー名検索</h2>
            <div class="box">
            	<img src="<!--{$TPL_URLPATH}-->img/page/bloc/search_box/icon_search.png" />
              <input type="image" src="<!--{$TPL_URLPATH}-->img/page/bloc/search_box/btn_search_product.png" />
            	<input type="text" name="maker"  value="<!--{$smarty.get.maker|escape}-->"/>
            </div>
        </section>
        </form>
