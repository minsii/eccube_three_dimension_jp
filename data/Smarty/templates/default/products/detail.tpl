<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *}-->


<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/Designer.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/products.js"></script>
<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.facebox/facebox.js"></script>
<link rel="stylesheet" type="text/css" href="<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.facebox/facebox.css" media="screen" />
<script type="text/javascript">//<![CDATA[
    // 規格2に選択肢を割り当てる。
    function fnSetClassCategories(form, classcat_id2_selected) {
        var $form = $(form);
        var product_id = $form.find('input[name=product_id]').val();
        var $sele1 = $form.find('select[name=classcategory_id1]');
        var $sele2 = $form.find('select[name=classcategory_id2]');
        setClassCategories($form, product_id, $sele1, $sele2, classcat_id2_selected);
    }
    $(document).ready(function() {
        $('a.expansion').facebox({
            loadingImage : '<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.facebox/loading.gif',
            closeImage   : '<!--{$smarty.const.ROOT_URLPATH}-->js/jquery.facebox/closelabel.png'
        });
    });
//]]></script>

<SCRIPT language=javascript type=text/javascript>
		var scrollPic_02 = new ScrollPic();
		scrollPic_02.scrollContId   = "scrollbox";
		scrollPic_02.arrLeftId      = "arrLeft";
		scrollPic_02.arrRightId     = "arrRight";
		scrollPic_02.frameWidth     = 900;
		scrollPic_02.pageWidth      = 900;
		scrollPic_02.speed          = 8;
		scrollPic_02.space          = 8;
		scrollPic_02.autoPlay       = false;
		scrollPic_02.autoPlayTime   = 3;
		scrollPic_02.initialize();
</SCRIPT>
<div id="undercolumn">
    <form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    
      <!-- ▼詳細画面 -->
      <!--{assign var=price01_min value=`$arrProduct.price01_min`}-->
      <!--{assign var=price01_max value=`$arrProduct.price01_max`}-->
      <!--{assign var=price02_min value=`$arrProduct.price02_min`}-->
      <!--{assign var=price02_max value=`$arrProduct.price02_max`}-->
      <!--{assign var=point_rate value=`$arrProduct.point_rate`}-->
      
      <h2 class="title_s2"><!--{$arrProduct.name|h}--><!--{if $arrProductOther.taxfree == 1}-->【非課税】<!--{/if}--></h2>
      <div>
      <section class="osusume_point_box mb10">
        <!--{$arrProductOther.comment6}--><!--{*詳細コメント3*}-->
      </section>
      </div>
      <div class="product_detail_box pure-g">
        <div class="pure-u-1-2">
          <div class="main_img mb10">
          <!--★画像★-->
          <!--{if $arrProduct.main_large_image|strlen >= 1}--><a href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_large_image|h}-->" class="lightbox" id="mainimage_a"><!--{/if}-->
            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_large_image|h}-->" alt="<!--{$arrProduct.name|h}-->" width="360" name="mainimage" id="mainimage"/>
          <!--{if $arrProduct.main_large_image|strlen >= 1}--></a><!--{/if}-->
          </div>
          <div class="sub_img pure-g">
            <ul>
              <!--{section name=cnt loop=$smarty.const.PRODUCTSUB_MAX}-->
              <!--★サブ画像<!--{$smarty.section.cnt.iteration}-->-->
              <!--{assign var=key_title value="sub_title`$smarty.section.cnt.index+1`"}-->
              <!--{assign var=key value="sub_image`$smarty.section.cnt.iteration`"}-->
              <!--{assign var=key1 value="sub_large_image`$smarty.section.cnt.iteration`"}-->
              <!--{if $arrProductOther[$key]|strlen >= 1}-->
                <li class="pure-u-1-4 img-hidden"><div>
                  <!--{if $arrProductOther[$key1]|strlen >= 1}--><a href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProductOther[$key1]|h}-->" class="lightbox"><!--{/if}-->
                  <img src="<!--{$arrFile[$key].filepath|h}-->" width="84" alt="<!--{$arrProductOther[$key_title]|h}-->"/>
                  <!--{if $arrProductOther[$key1]|strlen >= 1}--></a><!--{/if}-->
                </div></li>
              <!--{/if}-->
              <!--{/section}-->
            </ul>
          </div>
        </div>
        <div class="pure-u-1-2 product_detail_info">
        	<div class="warp">
            <!--▼商品ステータス-->
            <!--{assign var=ps value=$productStatus[$tpl_product_id]}-->
            <!--{if count($ps) > 0}-->
        	    <p class="icon">
                <!--{foreach from=$ps item=status}-->
                <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE[$status]}-->" width="64" height="18" alt="<!--{$arrSTATUS[$status]}-->" id="icon<!--{$status}-->"/>
                <!--{/foreach}-->
              </p>
            <!--{/if}-->
            <!--▲商品ステータス-->
            <h3><!--{$arrProduct.name|h}--><!--{if $arrProductOther.taxfree == 1}-->【非課税】<!--{/if}--></h3>
            <ul>
                <!--▼商品ステータス2-->
                <!--{assign var=ps value=$productStatus2[$tpl_product_id]}-->
                <!--{if count($ps) > 0}-->
            	    <li class="icon">
                    <!--{foreach from=$ps item=status}-->
                    <div class="item l-box">
<!--{*
                      <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE2[$status]}-->" width="34" height="34" alt="<!--{$arrSTATUS2[$status]}-->"/>
*}-->
<span><!--{$arrSTATUS2[$status]}--></span>
                    </div>
                    <!--{/foreach}-->
                  </li>
                <!--{/if}-->
                <!--▲商品ステータス2-->
            	<li class="info">
                <span style="width:70px">商品コード</span>
                <!--{if $arrProduct.product_code_min == $arrProduct.product_code_max}-->
                  <!--{$arrProduct.product_code_min|h}-->
                <!--{else}-->
                  <!--{$arrProduct.product_code_min|h}-->～<!--{$arrProduct.product_code_max|h}-->
                <!--{/if}-->
              </li>
            	<li class="info">
                <span style="width:70px">お届け目安</span>
                <!--{$arrDELIVERYDATE[$arrProduct.deliv_date_id]|h}-->
              </li>
              <!--{if $smarty.const.USE_POINT === true}-->
            	<li class="info">
                <span style="width:70px">ポイント</span>
                <!--{if $price02_min|sfPrePoint:$point_rate == $price02_max|sfPrePoint:$point_rate}-->
                    <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->
                <!--{else}-->
                    <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->～<!--{$price02_max|sfPrePoint:$point_rate|number_format}-->
                <!--{/if}-->pt
              </li>
              <!--{/if}-->
            	<li class="info">
                <span style="width:70px">一般価格</span>
                <!--{if $arrProductOther.taxfree == 1}-->
                  ￥<!--{if $price01_min == $price01_max}-->
                      <!--{$price01_min|number_format}-->
                  <!--{else}-->
                      <!--{$price01_min|number_format}-->～<!--{$price01_max|number_format}-->
                  <!--{/if}-->(税抜)
                <!--{else}-->
                  ￥<!--{if $price01_min == $price01_max}-->
                      <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                  <!--{else}-->
                      <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price01_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                  <!--{/if}-->(税込)
                <!--{/if}-->
              </li>
              <!--{if $tpl_is_login}-->
            	<li class="price">
                <span style="width:80px">会員価格</span>
                <!--{if $arrProductOther.taxfree == 1}-->
                  ￥<!--{if $price02_min == $price02_max}-->
                      <!--{$price02_min|number_format}-->
                  <!--{else}-->
                      <!--{$price02_min|number_format}-->～<!--{$price02_max|number_format}-->
                  <!--{/if}-->(税抜)
                <!--{else}-->
                  ￥<!--{if $price02_min == $price02_max}-->
                      <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                  <!--{else}-->
                      <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price02_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                  <!--{/if}-->（税込）
                <!--{/if}-->
              </li>
              <!--{else}-->
              <div class="osusume_shouhin_box">
              <div class="btn">
                <p>お得な価格は会員のみ公開</p>
                <a href="<!--{$smarty.const.TOP_URLPATH}-->entry/kiyaku.php">
                  <img src="<!--{$TPL_URLPATH}-->img/page/list/osusumeshouhin/btn_regist.png" width="308" height="44" alt="会員登録" />
                </a>
              </div>
              </div>
              <!--{/if}-->
            	<li>
                <!--{if $arrProduct.comment1}--><h4>メーカー名：　<span style="font-weight:normal;"><!--{$arrProduct.comment1|h}--><span></h4><!--{/if}-->
                <!--{if $arrProduct.comment2}--><h4>生産国：　<span style="font-weight:normal;"><!--{$arrProduct.comment2|h}--><span></h4><!--{/if}-->
                <!--{if $arrProduct.comment4}--><h4>TAISコード：　<span style="font-weight:normal;"><!--{$arrProduct.comment4|h}--><span></h4><!--{/if}-->
                <!--{if $arrProductOther.jis_code}--><h4>JISコード：　<span style="font-weight:normal;"><!--{$arrProductOther.jis_code|h}--><span></h4><!--{/if}-->
              </li>
            	<li>
                <!--{$arrProductOther.main_comment}-->
              </li>
            </ul>
            <div class="icon_bottom">
              <!--▼商品ステータス3-->
              <!--{assign var=ps value=$productStatus3[$tpl_product_id]}-->
              <!--{foreach from=$ps item=status}-->
              <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE3[$status]}-->" width="54" height="54" alt="<!--{$arrSTATUS3[$status]}-->" id="icon<!--{$status}-->"/>
              <!--{/foreach}-->
              <!--▲商品ステータス3-->
            </div>
            
            </div>
        </div>
      </div>
      
      <!--▼買い物かご-->
      <input type="hidden" name="mode" value="cart" />
      <input type="hidden" name="product_id" value="<!--{$tpl_product_id}-->" />
      <input type="hidden" name="product_class_id" value="<!--{$tpl_product_class_id}-->" id="product_class_id" />
      <input type="hidden" name="favorite_product_id" value="" />

      <section class="product_style_box">
        <!--▼規格リスト-->
        <div class="classlist">
          <div class="cart_area clearfix">
          <!--{if $tpl_stock_find}-->
              <!--{if $tpl_classcat_find1}-->
                  <div>
                      <!--▼規格1-->
                      <ul class="clearfix">
                          <li><!--{$tpl_class_name1|h}-->：</li>
                          <li>
                              <select name="classcategory_id1" style="<!--{$arrErr.classcategory_id1|sfGetErrorColor}-->">
                              <!--{html_options options=$arrClassCat1 selected=$arrForm.classcategory_id1.value}-->
                              </select>
                              <!--{if $arrErr.classcategory_id1 != ""}-->
                              <br /><span class="attention">※ <!--{$tpl_class_name1}-->を入力して下さい。</span>
                              <!--{/if}-->
                          </li>
                      </ul>
                      <!--▲規格1-->
                      <!--{if $tpl_classcat_find2}-->
                      <!--▼規格2-->
                      <ul class="clearfix">
                          <li><!--{$tpl_class_name2|h}-->：</li>
                          <li>
                              <select name="classcategory_id2" style="<!--{$arrErr.classcategory_id2|sfGetErrorColor}-->">
                              </select>
                              <!--{if $arrErr.classcategory_id2 != ""}-->
                              <br /><span class="attention">※ <!--{$tpl_class_name2}-->を入力して下さい。</span>
                              <!--{/if}-->
                          </li>
                      </ul>
                      <!--▲規格2-->
                      <!--{/if}-->
                  </div>
              <!--{/if}-->
          <!--{/if}-->
          </div>
        </div>
        <!--▲規格リスト-->
        
        <div class="pure-g-r">
          <div class="pure-u-1-4 allbuy"></div>
          <div class="pure-u-1-4"></div>
          <div class="pure-u-1-4"></div>
          <div class="pure-u-1-4 r-btn">
          
            <!--{if $tpl_is_login === true}-->
              <!--{if $tpl_stock_find}-->
              <!--★数量★-->
              <dl class="quantity">
                  <dt>数量：</dt>
                  <dd><input type="text" class="box60" name="quantity" value="<!--{$arrForm.quantity.value|default:1|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->" />
                      <!--{if $arrErr.quantity != ""}-->
                          <br /><span class="attention"><!--{$arrErr.quantity}--></span>
                      <!--{/if}-->
                  </dd>
              </dl>

              <a href="javascript:void(document.form1.submit())"><img src="<!--{$TPL_URLPATH}-->img/page/detail/btn_incart.png" width="212" height="45" alt="買い物カゴへ入れる" /></a>
              <p>※お見積り用紙もプリントできます。</p>
              <!--{else}-->
              <div class="attention">申し訳ございませんが、只今品切れ中です。</div>
              <!--{/if}-->
            <!--{/if}-->
            
            <!--★お気に入り登録★-->
            <!--{if $smarty.const.OPTION_FAVORITE_PRODUCT == 1 && $tpl_login === true}-->
              <!--{assign var=add_favorite value="add_favorite`$product_id`"}-->
              <!--{if $arrErr[$add_favorite]}-->
                  <div class="attention"><!--{$arrErr[$add_favorite]}--></div>
              <!--{/if}-->
              <!--{if !$is_favorite}-->
                  <a href="javascript:fnChangeAction('?product_id=<!--{$arrProduct.product_id|h}-->');　fnModeSubmit('add_favorite','favorite_product_id','<!--{$arrProduct.product_id|h}-->');">
                   <img src="<!--{$TPL_URLPATH}-->img/page/detail/btn_favorite.png" width="212" height="29" alt="お気にいり" />
                   </a>
              <!--{else}-->
                  <img src="<!--{$TPL_URLPATH}-->img/page/detail/btn_favorite.png" width="212" height="29" alt="お気に入り登録済" />
              <!--{/if}-->
            <!--{/if}-->
            <!--{if $arrProduct.product_code_min == $arrProduct.product_code_max}-->
                <p><a href="<!--{$smarty.const.TOP_URLPATH}-->contact/index.php?product_name=<!--{$arrProductOther.name|h}-->【<!--{$arrProduct.product_code_min|h}-->】">
            <!--{else}-->
                <a href="<!--{$smarty.const.TOP_URLPATH}-->contact/index.php?product_name=<!--{$arrProductOther.name|h}-->【<!--{$arrProduct.product_code_min|h}-->～<!--{$arrProduct.product_code_max|h}-->】">
            <!--{/if}-->
            <img src="<!--{$TPL_URLPATH}-->img/page/detail/btn_abouttheproduct.fw.png" alt="この商品について問い合わせる" /></a></p>
          </div>
        </div>
      </section>
      <!--▲買い物かご-->
      
      <section class="product_description">
      <h2>製品特徴</h2>
<div class="description-box">
      <!--{$arrProductOther.comment5}--><!--{*詳細コメント2*}-->
</div>
      </section>
      

      <!--▼関連商品-->
      <!--{if $arrRecommend}-->
      <div class="review_product_list">
        <h2>この商品を買った人は、こんな商品にも興味を持っています</h2>
        <div style="margin:0 auto; position:relative;">
            <!-- "previous page" action -->
            <a class="prev browse left">◀</a>
            <!-- root element for scrollable -->
            <div class="scrollable body" id="scrollable">
              <!-- root element for the items -->
              <div class="items">
                <!--{foreach from=$arrRecommend item=arrItem name="arrRecommend"}-->
                <!--{assign var=price01_min value=`$arrItem.price01_min`}-->
                <!--{assign var=price01_max value=`$arrItem.price01_max`}-->
                <!--{assign var=price02_min value=`$arrItem.price02_min`}-->
                <!--{assign var=price02_max value=`$arrItem.price02_max`}-->
                <!--{assign var=product_code_min value=`$arrItem.product_code_min`}-->
                <!--{assign var=product_code_max value=`$arrItem.product_code_max`}-->
                <!--{assign var=taxfree value=`$arrItem.taxfree`}-->
                <!--{math equation="x % y == 0" x=$smarty.foreach.arrRecommend.iteration y=4 assign=right}-->
                <!--{math equation="x % y == 1" x=$smarty.foreach.arrRecommend.iteration y=4 assign=left}-->
              
                <!--{if $left}--><div id="<!--{$smarty.foreach.arrRecommend.iteration}-->"><!-- <!--{$smarty.foreach.arrRecommend.iteration}--> start --><!--{/if}-->
                  <section class="">
                    <div class="warp heightLine">
                      <h3><!--{if $product_code_min == $product_code_max}--><!--{$product_code_min|h}-->
                          <!--{else}--><!--{$product_code_min|h}-->～<!--{$product_code_min|h}--><!--{/if}--></h3>
                      <div class="img">
                        <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrItem.product_id}-->">
                          <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrItem.main_list_image|h}-->" width="165" alt="<!--{$arrItem.name|h}-->" />
                        </a>
                      </div>
                      <p class="content"><a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrItem.product_id}-->"><!--{$arrItem.name|h}--><!--{if $taxfree == 1}-->【非課税】<!--{/if}--></a></p>
                      <p class="price">一般価格　
                        <!--{if $taxfree == 1}-->
                          ￥<!--{if $price01_min == $price01_max}-->
                              <!--{$price01_min|number_format}-->
                          <!--{else}-->
                              <!--{$price01_min|number_format}-->～<!--{$price01_max|number_format}-->
                          <!--{/if}-->(税抜)
                        <!--{else}-->
                          ￥<!--{if $price01_min == $price01_max}-->
                              <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                          <!--{else}-->
                              <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price01_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                          <!--{/if}-->(税込)
                        <!--{/if}-->
                      </p>
                      <!--{if $tpl_is_login}-->
                      <div class="member_price" style="text-align:left">
                          <p><em>会員価格</em></p>
                          <p>
                          <!--{if $taxfree == 1}-->
                            <strong>￥<!--{if $price02_min == $price02_max}-->
                                <!--{$price02_min|number_format}-->
                            <!--{else}-->
                                <!--{$price02_min|number_format}-->～<!--{$price02_max|number_format}-->
                            <!--{/if}--></strong><em>(税抜)</em>
                          <!--{else}-->
                            <strong>￥<!--{if $price02_min == $price02_max}-->
                                <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                            <!--{else}-->
                                <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price02_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                            <!--{/if}--></strong><em>(税込)</em>
                          <!--{/if}-->
                          </p>
                      </div>
                      <!--{else}-->
                      <div class="btn_regist">
                          <p>お得な価格は会員のみ公開</p>
                          <a href="<!--{$smarty.const.TOP_URLPATH}-->entry/kiyaku.php"><img src="<!--{$TPL_URLPATH}-->img/page/list/productlist/btn_regist.png" width="147" height="34" alt="会員登録" /></a>
                      </div>
                      <!--{/if}-->
                    </div>
                  </section>
                <!--{if $right || $smarty.foreach.arrRecommend.last}--></div><!--{/if}-->
                
                <!--{/foreach}-->
              </div><!-- /items -->
            </div><!-- /scrollable -->
            <!-- "next page" action -->
            <a class="next browse right">▶</a>
        </div>
      </div>

<SCRIPT language=javascript type=text/javascript>
$(function() {
  // initialize scrollable
  $(".scrollable").scrollable();
});
</SCRIPT>
  <!--{/if}-->
  <!--▲関連商品-->

</form>
</div>
