<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2012 LOCKON CO.,LTD. All Rights Reserved.
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
    <!--{include file=$tpl_navi}-->

    <div id="mycontents_area">
        <h3><!--{$CustomerName1|h}--> <!--{$CustomerName2|h}--> 様の月額予算実績</h3>
        <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="edit_month_est" />
        <table>
            <colgroup>
                <col width="20%" />
                <col />
                <col width="30%" />
            </colgroup>
            <tr>
                <th>期間</th>
                <td>
                  <!--{assign var=key1 value="month_est_start_year"}-->
                  <!--{assign var=key2 value="month_est_start_month"}-->
                  <!--{assign var=key3 value="month_est_start_day"}-->
                  <!--{$arrMonthForm[$key1]|h}-->年 <!--{$arrMonthForm[$key2]|h}-->月 <!--{$arrMonthForm[$key3]|h}-->日
                  <input type="hidden" name="<!--{$key1}-->" value="<!--{$arrMonthForm[$key1]}-->" />
                  <input type="hidden" name="<!--{$key2}-->" value="<!--{$arrMonthForm[$key2]}-->" />
                  <input type="hidden" name="<!--{$key3}-->" value="<!--{$arrMonthForm[$key3]}-->" />
                  &nbsp;から&nbsp;
                  <!--{assign var=key1 value="month_est_end_year"}-->
                  <!--{assign var=key2 value="month_est_end_month"}-->
                  <!--{assign var=key3 value="month_est_end_day"}-->
                  <!--{$arrMonthForm[$key1]|h}-->年 <!--{$arrMonthForm[$key2]|h}-->月 <!--{$arMonthForm[$key3]|h}-->日
                  <input type="hidden" name="<!--{$key1}-->" value="<!--{$arrMonthForm[$key1]}-->" />
                  <input type="hidden" name="<!--{$key2}-->" value="<!--{$arrMonthForm[$key2]}-->" />
                  <input type="hidden" name="<!--{$key3}-->" value="<!--{$arrMonthForm[$key3]}-->" />
                </td>
                <td rowspan="5" class="alignC">
                  <input type="image" src="<!--{$TPL_URLPATH}-->img/page/mypage/btn_copyorder.png"
                    width="178" height="33" alt="設定・変更" onclick="this.form.submit();"/>
                </td>
            </tr>
            <tr>
                <th>月額予算金額</th>
                <td>
                  <!--{assign var=key value="month_est_total"}-->
                  <!--{if $arrErr[$key]}-->
                      <div class="attention"><!--{$arrErr[$key]}--></div>
                  <!--{/if}-->
                  ￥<input type="text" name="<!--{$key}-->" value="<!--{$arrMonthForm[$key]}-->" />
                </td>
            </tr>
        </table>
        </form>
        
        <!-- 購入実績合計期間ここから -->
        <table summary="使用ポイント">
            <colgroup>
                <col width="20%">
                <col width="80%">
            </colgroup>
            <tbody>
                <tr>
                    <th class="alignL">ご購入実績合計</th>
                    <td>￥<!--{$arrMonthDisp.month_order_total|default:0|number_format}--></td>
                </tr>
                <tr>
                    <th class="alignL">予算残高</th>
                    <td>￥<!--{$arrMonthDisp.month_est_balance|default:0|number_format}--></td>
                </tr>
            </tbody>
        </table>
        <!-- 購入実績合計期間ここまで -->

        <br />
        <br />
        <h3><!--{$CustomerName1|h}--> <!--{$CustomerName2|h}--> 様の年額予算実績</h3>

        <form name="form2" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="edit_year_est" />
        <table>
            <colgroup>
                <col width="20%" />
                <col />
                <col width="30%" />
            </colgroup>
            <tr>
                <th>期間</th>
                <td>
                  <!--{assign var=key1 value="year_est_start_year"}-->
                  <!--{assign var=key2 value="year_est_start_month"}-->
                  <!--{assign var=key3 value="year_est_start_day"}-->
                  <!--{assign var=key4 value="year_est_end_year"}-->
                  <!--{assign var=key5 value="year_est_end_month"}-->
                  <!--{assign var=key6 value="year_est_end_day"}-->
                  <!--{if $arrErr[$key1]|| $arrErr[$key2] || $arrErr[$key3] || $arrErr[$key4] || $arrErr[$key5] || $arrErr[$key6] }-->
                      <div class="attention">
                        <!--{$arrErr[$key1]}-->
                        <!--{$arrErr[$key2]}-->
                        <!--{$arrErr[$key3]}-->
                        <!--{$arrErr[$key4]}-->
                        <!--{$arrErr[$key5]}-->
                        <!--{$arrErr[$key6]}-->
                      </div>
                  <!--{/if}-->
                  <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                      <!--{html_options options=$arrYear selected=$arrYearForm[$key1]|default:''}-->
                  </select>年
                  <select name="<!--{$key2}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->">
                      <!--{html_options options=$arrMonth selected=$arrYearForm[$key2]|default:''}-->
                  </select>月
                  <select name="<!--{$key3}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->">
                      <!--{html_options options=$arrDay selected=$arrYearForm[$key3]|default:''}-->
                  </select>日
                  &nbsp;から&nbsp;
                  <select name="<!--{$key4}-->" style="<!--{$arrErr[$key4]|sfGetErrorColor}-->">
                      <!--{html_options options=$arrYear selected=$arrYearForm[$key4]|default:''}-->
                  </select>年
                  <select name="<!--{$key5}-->" style="<!--{$arrErr[$key5]|sfGetErrorColor}-->">
                      <!--{html_options options=$arrMonth selected=$arrYearForm[$key5]|default:''}-->
                  </select>月
                  <select name="<!--{$key6}-->" style="<!--{$arrErr[$key6]|sfGetErrorColor}-->">
                      <!--{html_options options=$arrDay selected=$arrYearForm[$key6]|default:''}-->
                  </select>日
                </td>
                <td rowspan="5" class="alignC">
                  <input type="image" src="<!--{$TPL_URLPATH}-->img/page/mypage/btn_copyorder.png"
                    width="178" height="33" alt="設定・変更" onclick="this.form.submit();"/>
                </td>
            </tr>
            <tr>
                <th>年額予算金額</th>
                <td>
                  <!--{assign var=key value="year_est_total"}-->
                  <!--{if $arrErr[$key]}-->
                      <div class="attention"><!--{$arrErr[$key]}--></div>
                  <!--{/if}-->
                  ￥<input type="text" name="<!--{$key}-->" value="<!--{$arrYearForm[$key]}-->" />
                </td>
            </tr>
        </table>
        </form>
        
        <!-- 購入実績合計期間ここから -->
        <table summary="使用ポイント">
            <colgroup>
                <col width="20%">
                <col width="80%">
            </colgroup>
            <tbody>
                <tr>
                    <th class="alignL">ご購入実績合計</th>
                    <td>￥<!--{$arrYearDisp.year_order_total|default:0|number_format}--></td>
                </tr>
                <tr>
                    <th class="alignL">予算残高</th>
                    <td>￥<!--{$arrYearDisp.year_est_balance|default:0|number_format}--></td>
                </tr>
            </tbody>
        </table>
        <!-- 購入実績合計期間ここまで -->
    </div>
</div>
