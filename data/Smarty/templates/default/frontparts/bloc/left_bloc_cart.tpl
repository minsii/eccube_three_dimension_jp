
        <!--▼カゴ-->
        <section class="cart_box">
        	<h2>お買い物カゴ</h2>
            <dl>
            	<dt><strong>合計</strong>（税込）</dt>
            	<dd class="price">\<!--{$arrCartList.0.ProductsTotal|number_format|default:0}--></dd>
            </dl>
            <dl>
            	<dt>商品名</dt>
            	<dd>数 販売価格</dd>
            </dl>
            <dl>
            	<dt><a href="#">商品名</a></dt>
            	<dd>1 \22,222</dd>
            </dl>
            <div class="btn"> <a href="<!--{$smarty.const.CART_URLPATH}-->"><img src="<!--{$TPL_URLPATH}-->img/page/bloc/cart_box/btn_kagokakunin.png" width="192" height="41" alt="カゴの確認" /></a> </div>
        </section>
    
<!--現在のカゴの中ここから-->
<div class="left_box">
 <div class="left_inner">
  <div class="left_cont cartarea">
    <p class="item">商品数：<!--{$arrCartList.0.TotalQuantity|number_format|default:0}-->点</p>
    <p>合計：<span class="price"><!--{$arrCartList.0.ProductsTotal|number_format|default:0}-->円</span><br />
    <!-- カゴの中に商品がある場合にのみ表示 -->
    <!--{if $arrCartList.0.TotalQuantity > 0 and $arrCartList.0.free_rule > 0}-->
      <!--{if $arrCartList.0.deliv_free > 0}-->
      送料手数料無料まであと<!--{$arrCartList.0.deliv_free|number_format|default:0}-->円（税込）です。
      <!--{else}-->
      <br>現在、送料は「<span class="price">無料</span>」です。
      <!--{/if}-->
    <!--{/if}-->
    </p>
  </div>
 </div>
</div>
<!--現在のカゴの中ここまで-->