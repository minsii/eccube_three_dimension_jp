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
<script>
function clearSearchDate(){
  $("#start_year").val("");
  $("#start_month").val("");
  $("#end_year").val("");
  $("#end_month").val("");
}
function clearSearchText(){
  $("#search_product_text").val("");
}
</script>
<div id="mypagecolumn">
    <h2 class="title_s2"><!--{$tpl_title|h}--></h2>
    <!--{if $tpl_navi != ""}-->
        <!--{include file=$tpl_navi}-->
    <!--{else}-->
        <!--{include file=`$smarty.const.TEMPLATE_REALDIR`mypage/navi.tpl}-->
    <!--{/if}-->
    
    <h3>購入履歴一覧</h3>
    <form name="form1" id="form1" action="?" method="POST">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="search" />
    <input type="hidden" name="order_id" value="" />
    <input type="hidden" name="pageno" value="<!--{$objNavi->nowpage}-->" />
    
    <section class="mypage_orderlist_condition">
        <h3>ご注文を検索する</h3>
        <div>
            <h4><font color="4B99E3" size="+3">■</font>ご注文時期を指定する</h4>
            <div>
            <!--{assign var=key1 value="start_year"}-->
            <!--{assign var=key2 value="start_month"}-->
            <!--{assign var=key3 value="end_year"}-->
            <!--{assign var=key4 value="end_month"}-->
            <div class="attention">
              <!--{$arrErr[$key1]}-->
              <!--{$arrErr[$key2]}-->
              <!--{$arrErr[$key3]}-->
              <!--{$arrErr[$key4]}-->
              <!--{$arrErr[$key5]}-->
              <!--{$arrErr[$key6]}-->
            </div>
            <select class="pure-menu box100" name="<!--{$key1}-->" id="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                <!--{html_options options=$arrYear selected=$arrSearchForm[$key1]|default:''}-->
            </select>年
            <select class="pure-menu box100" name="<!--{$key2}-->" id="<!--{$key2}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->">
                <!--{html_options options=$arrMonth selected=$arrSearchForm[$key2]|default:''}-->
            </select>月から
            &nbsp;&nbsp;
            <select class="pure-menu box100" name="<!--{$key3}-->" id="<!--{$key3}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->">
                <!--{html_options options=$arrYear selected=$arrSearchForm[$key3]|default:''}-->
            </select>年
            <select class="pure-menu box100" name="<!--{$key4}-->" id="<!--{$key4}-->" style="<!--{$arrErr[$key4]|sfGetErrorColor}-->">
                <!--{html_options options=$arrMonth selected=$arrSearchForm[$key4]|default:''}-->
            </select>月まで
            
            <span class="btn"><input class="pure-button" type="button" value="クリア" onclick="clearSearchDate();"/></span>
            </div>
            <h4><font color="4B99E3" size="+3">■</font>商品名/カタログ番号で検索する</h4>
            <div>
            <input class="" type="text" value="<!--{$arrSearchForm.search_product_text|h}-->" name="search_product_text" id="search_product_text"/>
            <span class="btn"><input class="pure-button" type="button" value="クリア"  onclick="clearSearchText();"/></span>
            </div>
        </div>
        <div class="alignC">
          <span>この条件で</span><input type="image" src="<!--{$TPL_URLPATH}-->img/page/mypage/btn_searchorders.png" 
            width="122" height="33" alt="検索" />
        </div>
    </section>
    
    <!--{if $objNavi->all_row > 0}-->
    <section class="paginator">
      <p><span class="attention2"><!--{$objNavi->all_row}-->件</span>の購入履歴があります。</p>
        <!--▼ページナビ-->
        <!--{include file="list_pager2.tpl"}-->
        <!--▲ページナビ-->
    </section>
    </form>

    <!--{section name=cnt loop=$arrOrder}-->
    <!--{assign var=payment_id value="`$arrOrder[cnt].payment_id`"}-->
    <!--{assign var=shipping value="`$arrOrder[cnt].shipping`"}-->
    <!--{assign var=detail value="`$arrOrder[cnt].detail`"}-->
    <!--{assign var=status value="`$arrOrder[cnt].status`"}-->
    <h3><!--{$arrOrder[cnt].create_date|date_format:"%Y"}-->年<!--{$arrOrder[cnt].create_date|date_format:"%m"}-->月<!--{$arrOrder[cnt].create_date|date_format:"%d"}-->日のご注文分
        <span class="order_detail"><a target="blank" href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/history.php?order_id=<!--{$arrOrder[cnt].order_id}-->">購入履歴詳細はこちら</a></span>
    </h3>
    
    <form name="order_form<!--{$arrOrder[cnt].order_id}-->" action="order.php" method="post">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="order_id" value="<!--{$arrOrder[cnt].order_id|h}-->">
    <input type="hidden" name="mode" value="" />
    <table>
            <colgroup>
            	<col width="20%" />
                <col />
                <col width="30%" />
            </colgroup>
        <tr>
            <th>購入日時</th>
            <td><!--{$arrOrder[cnt].create_date|date_format:"%Y/%m/%d"}--></td>
            <td rowspan="5" class="alignC">
              <input type="image" src="<!--{$TPL_URLPATH}-->img/page/mypage/btn_copyorder.png" width="178" height="33" alt="この購入内容で再発注" 
                  onclick="fnFormModeSubmit('order_form<!--{$arrOrder[cnt].order_id}-->', 'addcart', '', '');return false;"/><br /><br />
              <a href="#" onclick="fnFormModeSubmit('order_form<!--{$arrOrder[cnt].order_id}-->', 'pdf', '', '');return false;">納品書を印刷</a>
            </td>
        </tr>
        <tr>
            <th>注文番号</th>
            <td><!--{$arrOrder[cnt].order_id}--></td>
        </tr>
        <tr>
            <th>お支払い方法</th>
            <td><!--{$arrPayment[$payment_id]|h}--></td>
        </tr>
        <!--{foreach item=shippingItem name=shippingItem from=$arrOrder[cnt].shipping}-->
        <tr>
            <th>お届け先<!--{if $arrOrder[cnt].isMultiple}--><!--{$smarty.foreach.shippingItem.iteration}--><!--{/if}--></th>
            <td>
                〒<!--{$shippingItem.shipping_zip01}-->-<!--{$shippingItem.shipping_zip02}-->&nbsp;&nbsp;
                <!--{$arrPref[$shippingItem.shipping_pref]}--><!--{$shippingItem.shipping_addr01|h}--><!--{$shippingItem.shipping_addr02|h}--> &nbsp;&nbsp;
                <!--{$shippingItem.shipping_name01|h}-->&nbsp;<!--{$shippingItem.shipping_name02|h}--> 様
            </td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <th>注文状況</th>
            <td><!--{$arrCustomerOrderStatus[$status]|h}--></td>
        </tr>
    </table>
    </form>
    
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
      <!--{foreach from=$detail item=orderDetail}-->
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
        <td class="alignC"><!--{$arrOrder[cnt].subtotal|number_format}-->円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">送料</th>
        <td class="alignC"><!--{$arrOrder[cnt].deliv_fee|number_format}-->円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">手数料</th>
        <td class="alignC"><!--{$arrOrder[cnt].charge|number_format}-->円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">合計</th>
        <td class="alignC"><span class="price"><!--{$arrOrder[cnt].payment_total|number_format}-->円</span></td>
      </tr>
    </tbody>
    </table>
    <br /><br />
    <!--{/section}-->
    
    <section class="paginator">
      <p><span class="attention2"><!--{$objNavi->all_row}-->件</span>の購入履歴があります。</p>
      <!--▼ページナビ-->
      <!--{include file="list_pager2.tpl"}-->
      <!--▲ページナビ-->
    </section>
    <!--{else}-->
    <p>購入履歴はありません。</p>
    <!--{/if}-->
</div>
