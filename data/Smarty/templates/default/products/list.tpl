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

<script type="text/javascript" src="<!--{$smarty.const.ROOT_URLPATH}-->js/products.js"></script>
<script type="text/javascript">//<![CDATA[
    function fnSetClassCategories(form, classcat_id2_selected) {
        var $form = $(form);
        var product_id = $form.find('input[name=product_id]').val();
        var $sele1 = $form.find('select[name=classcategory_id1]');
        var $sele2 = $form.find('select[name=classcategory_id2]');
        setClassCategories($form, product_id, $sele1, $sele2, classcat_id2_selected);
    }
    // 並び順を変更
    function fnChangeOrderby(orderby) {
        fnSetVal('orderby', orderby);
        fnSetVal('pageno', 1);
        fnSubmit();
    }
    // 表示件数を変更
    function fnChangeDispNumber(dispNumber) {
        fnSetVal('disp_number', dispNumber);
        fnSetVal('pageno', 1);
        fnSubmit();
    }
    // カゴに入れる
    function fnInCart(productForm_name) {
        var searchForm = $("#form1");
        var cartForm = $("form[name="+productForm_name+"]");
        
        // 検索条件を引き継ぐ
        var hiddenValues = ['mode','category_id','maker_id','name','orderby','disp_number','pageno','rnd'];
        $.each(hiddenValues, function(){
            // 商品別のフォームに検索条件の値があれば上書き
            if (cartForm.has('input[name='+this+']').length != 0) {
                cartForm.find('input[name='+this+']').val(searchForm.find('input[name='+this+']').val());
            }
            // なければ追加
            else {
                cartForm.append($('<input type="hidden" />').attr("name", this).val(searchForm.find('input[name='+this+']').val()));
            }
        });
        // 商品別のフォームを送信
        cartForm.submit();
    }
//]]></script>

<div id="undercolumn">
    <form name="form1" id="form1" method="get" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="<!--{$mode|h}-->" />
        <input type="hidden" name="favorite_product_id" value="<!--{$mode|h}-->" />
        <!--{* ▼検索条件 *}-->
        <input type="hidden" name="category_id" value="<!--{$arrSearchData.category_id|h}-->" />
        <input type="hidden" name="maker_id" value="<!--{$arrSearchData.maker_id|h}-->" />
        <input type="hidden" name="name" value="<!--{$arrSearchData.name|h}-->" />
        <!--{* ▲検索条件 *}-->
        <!--{* ▼ページナビ関連 *}-->
        <input type="hidden" name="orderby" value="<!--{$orderby|h}-->" />
        <input type="hidden" name="disp_number" value="<!--{$disp_number|h}-->" />
        <input type="hidden" name="pageno" value="<!--{$tpl_pageno|h}-->" />
        <!--{* ▲ページナビ関連 *}-->
        <input type="hidden" name="rnd" value="<!--{$tpl_rnd|h}-->" />
    </form>

      <!-- ▼一覧画面 -->
      <h2 class="title"><!--{$arrCategory.category_name|h}--></h2>
      
      <section class="sub_category">
      	<h2><!--{$arrCategory.category_name|h}--> カテゴリー</h2>
        <!--{if is_array($arrChildCats) && count($arrChildCats) > 0}-->
        <ul>
          <!--{section name=cnt loop=$arrChildCats}-->
        	<li>
            <a href="<!--{$smarty.const.P_LIST_URLPATH|sfGetFormattedUrl:$arrChildCats[cnt].category_id}-->">
              <!--{$arrChildCats[cnt].category_name|h}-->
            </a>
          </li>
          <!--{/section}-->
        </ul>
        <!--{/if}-->
      </section>
      
      <section class="osusume_point_box">
      	<div class="img"><img src="<!--{$TPL_URLPATH}-->img/page/list/osusumepoint/img_01.png" width="111" height="111" /></div>
        <h3><img src="<!--{$TPL_URLPATH}-->img/page/list/osusumepoint/title.png" width="293" height="19" alt="オススメポイント" /></h3>
        <p><!--{$arrCategory.category_info}--><!--{* カテゴリ説明1 *}--> </p>
        <div class="clear"></div>
      </section>

      <!-- ▼お勧め商品 -->
      <!--{if count($arrRecommend)}-->
      <section class="osusume_shouhin_box pure-g">
      	<h2><img src="<!--{$TPL_URLPATH}-->img/page/list/osusumeshouhin/title.png" width="742" height="48" alt="コンシェルジュオオススメ商品" /></h2>
        <!--{section name=cnt loop=$arrRecommend}-->
        <!--{assign var=price01_min value=`$arrRecommend[cnt].price01_min`}-->
        <!--{assign var=price01_max value=`$arrRecommend[cnt].price01_max`}-->
        <!--{assign var=price02_min value=`$arrRecommend[cnt].price02_min`}-->
        <!--{assign var=price02_max value=`$arrRecommend[cnt].price02_max`}-->
        <!--{assign var=point_rate value=`$arrRecommend[cnt].point_rate`}-->
        <div class="box">
       	  <div class="pure-u-1-2">
            <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrRecommend[cnt].product_id}-->"><img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrRecommend[cnt].main_image|h}-->" width="300" /></a><!--{* 商品画像 *}--> 
          </div>
          <div class="pure-u-1-2">
          	<h5><!--{if $arrRecommend[cnt].product_code_min == $arrRecommend[cnt].product_code_max}-->
                                  <!--{$arrRecommend[cnt].product_code_min|h}-->
                              <!--{else}-->
                                  <!--{$arrRecommend[cnt].product_code_min|h}-->～<!--{$arrRecommend[cnt].product_code_max|h}-->
                              <!--{/if}--></h5>
            <!--{if $arrRecommend[cnt].recommend_comment}-->
            <h3><!--{$arrRecommend[cnt].recommend_comment|h}--><!--{* キャッチコピー *}--></h3>
            <!--{/if}-->
            <p class="icon">
              <!--▼商品ステータス-->
              <!--{assign var=ps value=$arrRecommend[cnt].product_status}-->
              <!--{foreach from=$ps item=status}-->
                <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE[$status]}-->" width="64" height="18" alt="<!--{$arrSTATUS[$status]}-->"/>
              <!--{/foreach}-->
              <!--▲商品ステータス-->
            </p>
            <p><!--{$arrRecommend[cnt].name|h}--></p>
            <p>一般価格 ￥<!--{if $price01_min == $price01_max}-->
                                  <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                              <!--{else}-->
                                  <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price01_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                              <!--{/if}-->(税込)</p>
            <!--{if $tpl_is_login}-->
              <div class="member_price">
                  <p><em>会員特別価格</em>
                      <strong>￥<!--{if $price02_min == $price02_max}-->
                                  <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                              <!--{else}-->
                                  <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price02_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                              <!--{/if}--></strong><em>(税込)</em>
                  </p>
                  <p>ポイント:<!--{if $price02_min|sfPrePoint:$point_rate == $price02_max|sfPrePoint:$point_rate}-->
                                <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->
                            <!--{else}-->
                                <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->～<!--{$price02_max|sfPrePoint:$point_rate|number_format}-->
                            <!--{/if}-->pt</p>
                  <p><a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrRecommend[cnt].product_id}-->">詳細を見る</a></p>
              </div>
            <!--{else}-->
              <div class="btn">
              <p>お得な価格は会員のみ公開</p>
              <a href="<!--{$smarty.const.TOP_URLPATH}-->entry/kiyaku.php"><img src="<!--{$TPL_URLPATH}-->img/page/list/osusumeshouhin/btn_regist.png" width="308" height="44" alt="会員登録" /></a></div>
            <!--{/if}-->
          </div>
        </div>
        <!--{/section}-->
      </section>
      <!--{/if}-->
      <!-- ▲お勧め商品 -->
      
      <div class="product_list_box pure-form pure-form-stacked">
        <div class="paginator_box">
          <section class="sort_box pure-g">
                <span class="count pure-u-2-5"><!--{$tpl_linemax}-->件の商品がございます。</span>
                <span class="sort pure-u-2-5">
                <!--{if $orderby != 'price_up'}-->
                    <a href="javascript:fnChangeOrderby('price_up');">価格が安い順</a>
                <!--{else}-->
                    <strong>価格が安い順</strong>
                <!--{/if}-->
                <!--{if $orderby != 'price_down'}-->
                    <a href="javascript:fnChangeOrderby('price_down');">価格が高い順</a>
                <!--{else}-->
                    | <strong>価格が高い順</strong>
                <!--{/if}-->
                <!--{if $orderby != "date"}-->
                     | <a href="javascript:fnChangeOrderby('date');">新着順</a>
                <!--{else}-->
                     | <strong>新着順</strong>
                <!--{/if}-->
                </span>
              <span class="page_max">表示件数
                <select name="disp_number" onchange="javascript:fnChangeDispNumber(this.value);">
                    <!--{foreach from=$arrPRODUCTLISTMAX item="dispnum" key="num"}-->
                        <!--{if $num == $disp_number}-->
                            <option value="<!--{$num}-->" selected="selected" ><!--{$dispnum}--></option>
                        <!--{else}-->
                            <option value="<!--{$num}-->" ><!--{$dispnum}--></option>
                        <!--{/if}-->
                    <!--{/foreach}-->
                </select>
              </span>
          </section>

            <section class="paging pure-paginator pure-g-r">
                <span class="pure-u-1-2"><!--{$arrPagenavi.start_row|h}-->～<!--{$arrPagenavi.end_row|h}-->件/<!--{$tpl_linemax}-->件中</span>
                <!--{include file="list_pager.tpl"}-->
            </section>
        </div>
        
        <div class="body pure-g-r">
          <!--▼商品一覧-->
          <!--{foreach from=$arrProducts item=arrProduct name=arrProducts}-->
          <!--{assign var=id value=$arrProduct.product_id}-->
          <!--{assign var=price01_min value=`$arrProduct.price01_min`}-->
          <!--{assign var=price01_max value=`$arrProduct.price01_max`}-->
          <!--{assign var=price02_min value=`$arrProduct.price02_min`}-->
          <!--{assign var=price02_max value=`$arrProduct.price02_max`}-->
          <!--{assign var=point_rate value=`$arrProduct.point_rate`}-->
        	<section class="pure-u-1-4">
          <a name="product<!--{$id|h}-->">
          <form name="product_form<!--{$id|h}-->" action="?" onsubmit="return false;">
          <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
          <input type="hidden" name="product_id" value="<!--{$id|h}-->" />
          <input type="hidden" name="product_class_id" id="product_class_id<!--{$id|h}-->" value="<!--{$tpl_product_class_id[$id]}-->" />
                <div class="warp">
                    <h3><!--{if $arrProduct.product_code_min == $arrProduct.product_code_max}-->
                                  <!--{$arrProduct.product_code_min|h}-->
                              <!--{else}-->
                                  <!--{$arrProduct.product_code_min|h}-->～<!--{$arrProduct.product_code_max|h}-->
                              <!--{/if}--></h3>
                    <div class="img">
                      <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrProduct.product_id}-->">
                        <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$arrProduct.main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrProduct.name|h}-->" width="165" />
                      </a><!--{* 商品画像 *}-->
                    </div>
                    <p class="icon">
                      <!--▼商品ステータス-->
                      <!--{assign var=ps value=$productStatus[$id]}-->
                      <!--{foreach from=$ps item=status}-->
                        <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE[$status]}-->" width="50" height="14" alt="<!--{$arrSTATUS[$status]}-->"/>
                      <!--{/foreach}-->
                      <!--▲商品ステータス-->
                    </p>
                    <p class="content"><!--{$arrProduct.name|h}--></p>
                    <p class="price">一般価格　￥<!--{if $price01_min == $price01_max}-->
                                  <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                              <!--{else}-->
                                  <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price01_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                              <!--{/if}-->(税込)</p>
                    <!--{if $tpl_is_login}-->
                      <div class="member_price">
                          <p><em>会員特別価格</em></p>
                          <p><strong>￥<!--{if $price02_min == $price02_max}-->
                                    <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                                <!--{else}-->
                                    <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price02_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                                <!--{/if}--></strong><em>(税込)</em>
                          </p>
                          <p>ポイント:<!--{if $price02_min|sfPrePoint:$point_rate == $price02_max|sfPrePoint:$point_rate}-->
                                  <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->
                              <!--{else}-->
                                  <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->～<!--{$price02_max|sfPrePoint:$point_rate|number_format}-->
                              <!--{/if}-->pt</p>
                      </div>
                      <!--{if $arrCategory.hide_list_cart != 1}--><!--{* カゴ非表示 *}-->
                        <div class="count">
                          <!--{if $tpl_stock_find[$id]}-->
                          <!--{if $arrErr.quantity != ""}-->
                          <span class="attention"><!--{$arrErr.quantity}--></span><br />
                          <!--{/if}-->
                          <!--{if $tpl_classcat_find1[$id]}--><!--{ *バリエーション一覧へ* }-->
                            <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrProduct.product_id}-->">バリエーション一覧へ</a>
                          <!--{else}-->
                            <span>数量:<input type="text"  name="quantity" class="box30" value="<!--{$arrProduct.quantity|default:1|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->"/></span>
                            <a href="#" onclick="fnInCart('product_form<!--{$id|h}-->'); return false;">
                              <img src="<!--{$TPL_URLPATH}-->img/page/list/productlist/btn_incart.png" width="98" height="23" alt="カゴへ入れる" />
                            </a>
                          <!--{/if}-->
                          <!--{else}-->
                          <span class="attention">申し訳ございませんが、只今品切れ中です。</span>
                          <!--{/if}--><!--{* /カゴ非表示 *}-->
                        </div>
                      <!--{/if}-->
                      <!--{if $smarty.const.OPTION_FAVORITE_PRODUCT == 1}-->
                      <div class="btn_favorite">
                        <!--{if $arrFavorites[$id]}-->
                        お気に入りに登録済みです。
                        <!--{else}-->
                        <a href="#" onclick="fnModeSubmit('add_favorite','favorite_product_id','<!--{$arrProduct.product_id|h}-->'); return false;">
                          <img src="<!--{$TPL_URLPATH}-->img/page/list/productlist/btn_favorite.png" width="162" height="23" alt="お気に入り追加" />
                        </a>
                        <!--{/if}-->
                      </div>
                      <!--{/if}-->
                    <!--{else}-->
                      <div class="btn_regist">
                          <p>お得な価格は会員のみ公開</p>
                          <a href="<!--{$smarty.const.TOP_URLPATH}-->entry/kiyaku.php"><img src="<!--{$TPL_URLPATH}-->img/page/list/productlist/btn_regist.png" width="147" height="34" alt="会員登録" /></a>
                      </div>
                    <!--{/if}-->
                </div>
            </form>
            </section>
            <!--{foreachelse}-->
                <!--{include file="frontparts/search_zero.tpl"}-->
            <!--{/foreach}-->
            <!--▲商品一覧-->
        </div>
        
      <div class="product_list_box pure-form pure-form-stacked">
        <div class="paginator_box">
          <section class="paging pure-paginator pure-g-r">
              <span class="pure-u-1-2"><!--{$arrPagenavi.start_row|h}-->～<!--{$arrPagenavi.end_row|h}-->件/<!--{$tpl_linemax}-->件中</span>
              <!--{include file="list_pager.tpl"}-->
          </section>
          
          <section class="sort_box pure-g">
                <span class="count pure-u-2-5"><!--{$tpl_linemax}-->件の商品がございます。</span>
                <span class="sort pure-u-2-5">
                <!--{if $orderby != 'price_up'}-->
                    <a href="javascript:fnChangeOrderby('price_up');">価格が安い順</a>
                <!--{else}-->
                    <strong>価格が安い順</strong>
                <!--{/if}-->
                <!--{if $orderby != 'price_down'}-->
                    <a href="javascript:fnChangeOrderby('price_down');">価格が高い順</a>
                <!--{else}-->
                    | <strong>価格が高い順</strong>
                <!--{/if}-->
                <!--{if $orderby != "date"}-->
                     | <a href="javascript:fnChangeOrderby('date');">新着順</a>
                <!--{else}-->
                     | <strong>新着順</strong>
                <!--{/if}-->
                </span>
              <span class="page_max">表示件数
                <select name="disp_number" onchange="javascript:fnChangeDispNumber(this.value);">
                    <!--{foreach from=$arrPRODUCTLISTMAX item="dispnum" key="num"}-->
                        <!--{if $num == $disp_number}-->
                            <option value="<!--{$num}-->" selected="selected" ><!--{$dispnum}--></option>
                        <!--{else}-->
                            <option value="<!--{$num}-->" ><!--{$dispnum}--></option>
                        <!--{/if}-->
                    <!--{/foreach}-->
                </select>
              </span>
          </section>
        </div>
      </div>
  
  </div>
</div>
