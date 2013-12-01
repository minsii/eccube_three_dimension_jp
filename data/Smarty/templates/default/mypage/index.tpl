<!--{*
/*
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
 */
*}-->

<div id="mypagecolumn">
    <h2 class="title_s2"><!--{$tpl_title|h}--></h2>
    <!--{if $tpl_navi != ""}-->
        <!--{include file=$tpl_navi}-->
    <!--{else}-->
        <!--{include file=`$smarty.const.TEMPLATE_REALDIR`mypage/navi.tpl}-->
    <!--{/if}-->

    <!--{* 新着情報 ▼ *}--> 
    <h3><!--{$CustomerName1|h}--> <!--{$CustomerName2|h}-->様へ、お知らせです。</h3>
    <ul>
    <!--{section name=data loop=$arrNews}-->
      <!--{assign var=news_no value="`$smarty.section.data.iteration`"}-->
      <li <!--{if $news_no % 2 == 0}--> class="row" <!--{/if}-->>
          <!--{if $arrNews[data].news_url}-->
          <a href="<!--{$arrNews[data].news_url}-->"
            <!--{if $arrNews[data].link_method eq "2"}-->
            target="_blank"
            <!--{/if}-->>
          <!--{/if}-->
          >><!--{$arrNews[data].news_date_disp|date_format:"%Y.%m.%d"}-->&nbsp;<!--{$arrNews[data].news_title|escape|nl2br}-->
          <!--{if $arrNews[data].news_url}-->
          </a>
          <!--{/if}-->
      </li>
    <!--{/section}-->
    </ul>
    <!--{* 新着情報 ▲ *}--> 
    
    <br />
    <br />
    <!--{* 最近の購入履歴 ▼ *}-->
    <h3>最近の購入履歴
        <span class="order_detail"><a href="#">購入履歴詳細はこちら</a></span>
    </h3>
    
    <!--{section name=cnt loop=$arrLatestOrder}-->
    <!--{assign var=lastOrder value=$arrLatestOrder[cnt]}-->
    <!--{assign var=shipping value=$arrLatestOrder[cnt].shipping}-->
    <table>
        <colgroup>
        	<col width="20%" />
            <col />
            <col width="30%" />
        </colgroup>
        <tr>
            <th>購入日時</th>
            <td><!--{$lastOrder.create_date|date_format:"%Y/%m/%d"}--></td>
            <td rowspan="5" class="alignC">
              <form action="order.php" method="post">
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                <input type="hidden" name="order_id" value="<!--{$lastOrder.order_id|h}-->">
                <input type="image" src="<!--{$TPL_URLPATH}-->img/page/mypage/btn_copyorder.png" alt="この購入内容で再注文する" name="submit" value="この購入内容で再注文する" width="178" height="33"/>
              </form>
            </td>
        </tr>
        <tr>
            <th>注文番号</th>
            <td><!--{$lastOrder.order_id|h}--></td>
        </tr>
        <tr>
            <th>お支払い方法</th>
            <td><!--{$arrPayment[$lastOrder.payment_id]|h}--></td>
        </tr>
        <!--{foreach item=shippingItem name=shippingItem from=$shipping}-->
        <tr>
            <th>お届け先<!--{if $lastOrder.isMultiple}--><!--{$smarty.foreach.shippingItem.iteration}--><!--{/if}--></th>
            <td>
                〒<!--{$shippingItem.shipping_zip01}-->-<!--{$shippingItem.shipping_zip02}-->&nbsp;&nbsp;
                <!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01|h}--><!--{$shippingItem.shipping_addr02|h}--> &nbsp;&nbsp;
                <!--{$shippingItem.shipping_name01|h}-->&nbsp;<!--{$shippingItem.shipping_name02|h}--> 様
            </td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <th>注文状況</th>
            <td><!--{$arrCustomerOrderStatus[$lastOrder.status]|h}--></td>
        </tr>
    </table>
    
    <table summary="購入商品詳細">
    <colgroup>
    <col width="10%">
    <col width="15%">
    <col width="25%">
    <col width="15%">
    <col width="10%">
    <col width="10%">
    <col width="15%">
    </colgroup>
    <tbody>
      <tr>
        <th class="alignC">カテゴリ番号</th>
        <th class="alignC">商品画像</th>
        <th class="alignC">商品名</th>
        <th class="alignC">単価</th>
        <th class="alignC">数量</th>
        <th class="alignC">詳細</th>
        <th class="alignC">小計</th>
      </tr>
      <!--{foreach from=$lastOrder.detail item=orderDetail}-->
      <tr>
        <td><!--{$orderDetail.product_code|h}--></td>
        <td class="alignC">
          <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$orderDetail.product_id}-->">
            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$orderDetail.main_list_image|sfNoImageMainList|h}-->" alt="<!--{$orderDetail.product_name|h}-->" width="60">
          </a>
        </td>
        <td>
          <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$orderDetail.product_id}-->">
          <!--{$orderDetail.product_name|h}-->
          </a>
          <!--{if $orderDetail.classcategory_name1 != ""}-->
              <br /><!--{$orderDetail.classcategory_name1|h}-->
          <!--{/if}-->
          <!--{if $orderDetail.classcategory_name2 != ""}-->
              /<!--{$orderDetail.classcategory_name2|h}-->
          <!--{/if}-->
          <br>
        </td>
        <!--{assign var=price value=`$orderDetail.price`}-->
        <!--{assign var=quantity value=`$orderDetail.quantity`}-->
        <td class="alignC">
          <!--{*## 商品非課税 MDF BEGIN ##*}-->
          <!--{if $smarty.const.USE_TAXFREE_PRODUCT === true && $orderDetail.taxfree == 1}-->
              <!--{$price|number_format}-->円（税抜）
          <!--{else}-->
              <!--{$price|sfCalcIncTax|number_format}-->円（税込）
          <!--{/if}-->
          <!--{*## 商品非課税 MDF END ##*}-->
        </td>
        <td class="alignC"><!--{$quantity|h}--></td>
        <td class="alignC"><a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$orderDetail.product_id}-->">商品詳細</a></td>
        <td class="alignC">
          <!--{*## 商品非課税 MDF BEGIN ##*}-->
          <!--{if $smarty.const.USE_TAXFREE_PRODUCT === true && $orderDetail.taxfree == 1}-->
              <!--{$price|sfMultiply:$quantity|number_format}-->円
          <!--{else}-->
              <!--{$price|sfCalcIncTax|sfMultiply:$quantity|number_format}-->円
          <!--{/if}-->
          <!--{*## 商品非課税 MDF END ##*}-->
        </td>
      </tr>
      <!--{/foreach}-->
      <tr>
        <th colspan="6" class="alignR">小計</th>
        <td class="alignC"><!--{$lastOrder.subtotal|number_format}-->円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">送料</th>
        <td class="alignC"><!--{$lastOrder.deliv_fee|number_format}-->円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">手数料</th>
        <td class="alignC"><!--{$lastOrder.charge|number_format}-->円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">合計</th>
        <td class="alignC"><span class="price"><!--{$lastOrder.payment_total|number_format}-->円</span></td>
      </tr>
    </tbody>
    </table>
    <!--{/section}-->
    <!--{* 最近の購入履歴 ▲ *}-->
    
    <br />
    <br />
    <!--{* 最近お気に入り商品 ▼ *}-->
    <h3>最近のお気に入り商品
        <span class="order_detail"><a href="<!--{$smarty.const.TOP_URLPATH}-->mypage/favorite.php">お気に入り一覧はこちら</a></span>
    </h3>

    <!--{if is_array($arrLatestFavorite) && count($arrLatestFavorite) > 0}-->
    <div class="review_product_list">
      <div class="body pure-g-r" id="scrollbox">
      <!--{foreach from=$arrLatestFavorite item=arrProduct}-->
      <!--{assign var=id value=$arrProduct.product_id}-->
      <!--{assign var=price01_min value=`$arrProduct.price01_min`}-->
      <!--{assign var=price01_max value=`$arrProduct.price01_max`}-->
      <!--{assign var=price02_min value=`$arrProduct.price02_min`}-->
      <!--{assign var=price02_max value=`$arrProduct.price02_max`}-->
      <!--{assign var=point_rate value=`$arrProduct.point_rate`}-->
    	<section class="pure-u-1-4">
      <form name="product_form<!--{$id|h}-->" action="?" onsubmit="return false;" method="POST">
      <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
      <input type="hidden" name="product_id" value="<!--{$id|h}-->" />
      <input type="hidden" name="product_class_id" id="product_class_id<!--{$id|h}-->" value="<!--{$arrProduct.product_class_id|h}-->" />
      <input type="hidden" name="mode" value="cart" />
            <div class="warp">
                <div class="heightLine">
                  <h3><!--{if $arrProduct.product_code_min == $arrProduct.product_code_max}-->
                                <!--{$arrProduct.product_code_min|h}-->
                            <!--{else}-->
                                <!--{$arrProduct.product_code_min|h}-->～<!--{$arrProduct.product_code_max|h}-->
                            <!--{/if}--></h3>
                  <p class="icon">
                    <!--▼商品ステータス-->
                    <!--{assign var=ps value=$arrProduct.product_status}-->
                    <!--{foreach from=$ps item=status}-->
                      <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE[$status]}-->" width="46" alt="<!--{$arrSTATUS[$status]}-->"/>
                    <!--{/foreach}-->
                    <!--▲商品ステータス-->
                  </p>
                  <div class="img">
                    <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrProduct.product_id}-->">
                      <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$arrProduct.main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrProduct.name|h}-->" width="165" />
                    </a><!--{* 商品画像 *}-->
                  </div>
                  <p class="content">
                    <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrProduct.product_id}-->"><!--{$arrProduct.name|h}--></a>
                  </p>
                </div>
                <p class="price">一般価格　
                <!--{if $arrProduct.taxfree == 1}-->
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
                  <div class="member_price">
                      <p><em>会員特別価格</em></p>
                      <p>
                      <!--{if $arrProduct.taxfree == 1}-->
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
                      <!--{if $smarty.const.USE_POINT === true}-->
                      <p>ポイント:<!--{if $price02_min|sfPrePoint:$point_rate == $price02_max|sfPrePoint:$point_rate}-->
                              <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->
                          <!--{else}-->
                              <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->～<!--{$price02_max|sfPrePoint:$point_rate|number_format}-->
                          <!--{/if}-->pt</p>
                      <!--{/if}-->
                  </div>
                    <div class="count">
                      <!--{if $arrProduct.stock_min != 0 || $arrProduct.stock_max != 0 || 
                          $arrProduct.stock_unlimited_min == 1 || $arrProduct.stock_unlimited_max == 1}-->
                      <!--{if $arrErr.quantity != ""}-->
                      <span class="attention"><!--{$arrErr.quantity}--></span><br />
                      <!--{/if}-->
                      <!--{if $arrProduct.product_class_id == -1}--><!--{ *バリエーション一覧へ* }-->
                        <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrProduct.product_id}-->">バリエーション一覧へ</a>
                      <!--{else}-->
                        <span>数量:<input type="text"  name="quantity" class="box30" value="<!--{$smarty.post.quantity|default:1|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->"/></span>
                          <input type="image" onclick="this.form.submit();" src="<!--{$TPL_URLPATH}-->img/page/list/productlist/btn_incart.png" width="96" height="23" alt="カゴへ入れる" />
                      <!--{/if}-->
                      <!--{else}-->
                      <span class="attention">申し訳ございませんが、只今品切れ中です。</span>
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
        <!--{/foreach}-->
      </div>
    </div>
<!--{else}-->
    <!--{include file="frontparts/search_zero.tpl"}-->
<!--{/if}-->
<!--{* 最近お気に入り商品 ▲ *}-->
        
        
    <!--特集一覧-->
    <section class="special_list">
    <h3>特集一覧
    </h3>
    <ul class="pure-g">
        <li class="pure-u-1-3"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png" width="305" height="120" /></li>
        <li class="pure-u-1-3"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png" width="305" height="120" /></li>
        <li class="pure-u-1-3"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png" width="305" height="120" /></li>
        <li class="pure-u-1-3"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png" width="305" height="120" /></li>
        <li class="pure-u-1-3"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png" width="305" height="120" /></li>
        <li class="pure-u-1-3"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png" width="305" height="120" /></li>
        
    </ul>
    </section>

    <div id="mycontents_area">
        <form name="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="order_id" value="" />
        <input type="hidden" name="pageno" value="<!--{$objNavi->nowpage}-->" />
        <h3><!--{$tpl_subtitle|h}--></h3>

        <!--{if $objNavi->all_row > 0}-->

            <p><span class="attention"><!--{$objNavi->all_row}-->件</span>の購入履歴があります。</p>
            <div class="pagenumber_area">
                <!--▼ページナビ-->
                <!--{$objNavi->strnavi}-->
                <!--▲ページナビ-->
            </div>

            <table summary="購入履歴">
                <tr>
                    <th class="alignC">購入日時</th>
                    <th class="alignC">注文番号</th>
                    <th class="alignC">お支払い方法</th>
                    <th class="alignC">合計金額</th>
                    <!--{if $smarty.const.MYPAGE_ORDER_STATUS_DISP_FLAG }-->
                    <th class="alignC">ご注文状況</th>
                    <!--{/if}-->
                    <th class="alignC">詳細</th>
                </tr>
                <!--{section name=cnt loop=$arrOrder}-->
                    <tr>
                        <td class="alignC"><!--{$arrOrder[cnt].create_date|sfDispDBDate}--></td>
                        <td><!--{$arrOrder[cnt].order_id}--></td>
                        <!--{assign var=payment_id value="`$arrOrder[cnt].payment_id`"}-->
                        <td class="alignC"><!--{$arrPayment[$payment_id]|h}--></td>
                        <td class="alignR"><!--{$arrOrder[cnt].payment_total|number_format}-->円</td>
                        
                        <!--{if $smarty.const.MYPAGE_ORDER_STATUS_DISP_FLAG }-->
                            <!--{assign var=order_status_id value="`$arrOrder[cnt].status`"}-->
                            <!--{if $order_status_id != $smarty.const.ORDER_PENDING }-->
                            <td class="alignC"><!--{$arrCustomerOrderStatus[$order_status_id]|h}--></td>
                            <!--{else}-->
                            <td class="alignC attention"><!--{$arrCustomerOrderStatus[$order_status_id]|h}--></td>
                            <!--{/if}-->
                        <!--{/if}-->
                        <td class="alignC"><a href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/history.php?order_id=<!--{$arrOrder[cnt].order_id}-->">詳細</a></td>
                    </tr>
                <!--{/section}-->
            </table>

        <!--{else}-->
            <p>購入履歴はありません。</p>
        <!--{/if}-->
        </form>
    </div>
</div>
