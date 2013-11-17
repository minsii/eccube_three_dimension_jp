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
    
    
        <h3>購入履歴一覧</h3>
    <section class="mypage_orderlist_condition">
        <h3>ご注文を検索する</h3>
        <div>
            <h4><font color="4B99E3" size="+3">■</font>ご注文時期を指定する</h4>
            <div>
            <select class="pure-menu box100"></select>年
            <select class="pure-menu box100"></select>月から
            <select class="pure-menu box100"></select>年
            <select class="pure-menu box100"></select>月まで
            
            <span class="btn"><input class="pure-button" type="button" value="クリア"/></span>
            </div>
            <h4><font color="4B99E3" size="+3">■</font>商品名/カタログ番号で検索する</h4>
            <div>
            <input class="" type="text" value="クリア"/>
            <span class="btn"><input class="pure-button" type="button" value="クリア"/></span>
            </div>
        </div>
        <div class="alignC"><span>この条件で</span><a href="#"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/btn_searchorders.png" width="122" height="33" alt="検索" /></a></div>
    </section>
    
    <section class="paginator">
      <p><span class="attention2">1件</span>の購入履歴があります。</p>
        <ul class="paging">
            <li class="first"><<前へ</li>
            <li>01|</li>
            <li>02|</li>
            <li>03|</li>
            <li class="last">次へ>></li>
        </ul>
    </section>
    <div class="pagenumber_area"> 
      <!--▼ページナビ--> 
      
      <!--▲ページナビ--> 
    </div>
    <h3>2013のご注文分
        <span class="order_detail"><a href="#">購入履歴詳細はこちら</a></span>
    </h3>
    
    <table>
            <colgroup>
            	<col width="20%" />
                <col />
                <col width="30%" />
            </colgroup>
        <tr>
            <th>購入日時</th>
            <td>あああ</td>
            <td rowspan="5" class="alignC"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/btn_copyorder.png" width="178" height="33" alt="この購入日時内容で再発注" /></td>
        </tr>
        <tr>
            <th>注文番号</th>
            <td>あああ</td>
        </tr>
        <tr>
            <th>お支払い方法</th>
            <td>あああ</td>
        </tr>
        <tr>
            <th>お届け先</th>
            <td>あああ</td>
        </tr>
        <tr>
            <th>注文状況</th>
            <td>あああ</td>
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
      <tr>
        <td>1</td>
        <td class="alignC"> 通常商品 </td>
        <td><a href="/~three-dimension-jp/products/detail.php?product_id=19">Co-Co Life 全冊セット(Vol.2〜Vol.13)　(テスト用, CSV登録1)</a><br></td>
        <td class="alignC">5,000円</td>
        <td class="alignC">1</td>
        <td class="alignC">1</td>
        <td class="alignC">5,000円</td>
      </tr>
      <tr>
        <td>1</td>
        <td class="alignC"> 通常商品 </td>
        <td><a href="/~three-dimension-jp/products/detail.php?product_id=19">Co-Co Life 全冊セット(Vol.2〜Vol.13)　(テスト用, CSV登録1)</a><br></td>
        <td class="alignC">5,000円</td>
        <td class="alignC">1</td>
        <td class="alignC">1</td>
        <td class="alignC">5,000円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">小計</th>
        <td class="alignC">5,000円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">送料</th>
        <td class="alignC">900円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">手数料</th>
        <td class="alignC">0円</td>
      </tr>
      <tr>
        <th colspan="6" class="alignR">合計</th>
        <td class="alignC"><span class="price">5,900円</span></td>
      </tr>
    </tbody>
    </table>
    <section class="paginator">
      <p><span class="attention2">1件</span>の購入履歴があります。</p>
        <ul class="paging">
            <li class="first"><<前へ</li>
            <li>01|</li>
            <li>02|</li>
            <li>03|</li>
            <li class="last">次へ>></li>
        </ul>
    </section>

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
