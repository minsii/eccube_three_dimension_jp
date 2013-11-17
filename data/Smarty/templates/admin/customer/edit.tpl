<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
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
<script type="text/javascript">
<!--

    function fnReturn() {
        document.search_form.action = './<!--{$smarty.const.DIR_INDEX_PATH}-->';
        document.search_form.submit();
        return false;
    }

    function fnOrderidSubmit(order_id, order_id_value) {
        if(order_id != "" && order_id_value != "") {
            document.form2[order_id].value = order_id_value;
        }
        document.form2.action = '../order/edit.php';
        document.form2.submit();
    }

//-->
</script>

<form name="search_form" method="post" action="">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="search" />

    <!--{foreach from=$arrSearchData key="key" item="item"}-->
        <!--{if $key ne "customer_id" && $key ne "mode" && $key ne "edit_customer_id" && $key ne $smarty.const.TRANSACTION_ID_NAME}-->
            <!--{if is_array($item)}-->
                <!--{foreach item=c_item from=$item}-->
                    <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
                <!--{/foreach}-->
            <!--{else}-->
                <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
            <!--{/if}-->
        <!--{/if}-->
    <!--{/foreach}-->
</form>

<form name="form1" id="form1" method="post" action="?" autocomplete="off">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="confirm" />
    <input type="hidden" name="customer_id" value="<!--{$arrForm.customer_id|h}-->" />

    <!-- 検索条件の保持 -->
    <!--{foreach from=$arrSearchData key="key" item="item"}-->
        <!--{if $key ne "customer_id" && $key ne "mode" && $key ne "edit_customer_id" && $key ne $smarty.const.TRANSACTION_ID_NAME}-->
            <!--{if is_array($item)}-->
                <!--{foreach item=c_item from=$item}-->
                    <input type="hidden" name="search_data[<!--{$key|h}-->][]" value="<!--{$c_item|h}-->" />
                <!--{/foreach}-->
            <!--{else}-->
                <input type="hidden" name="search_data[<!--{$key|h}-->]" value="<!--{$item|h}-->" />
            <!--{/if}-->
        <!--{/if}-->
    <!--{/foreach}-->

    <div id="customer" class="contents-main">
        <table class="form">
            <!--{if $arrForm.customer_id}-->
            <tr>
                <th>会員ID<span class="attention"> *</span></th>
                <td><!--{$arrForm.customer_id|h}--></td>
            </tr>
            <!--{/if}-->
            <tr>
                <th>会員状態<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.status}--></span>
                    <span <!--{if $arrErr.status != ""}--><!--{sfSetErrorStyle}--><!--{/if}-->>
                        <!--{html_radios name="status" options=$arrStatus separator=" " selected=$arrForm.status}-->
                    </span>
                </td>
            </tr>
            <!--{*## 顧客法人管理 ADD BEGIN ##*}-->
            <!--{if $smarty.const.USE_CUSTOMER_COMPANY === true}-->
            <tr>
                <th>法人名</th>
                <td>
                    <!--{assign var=key value="company"}-->
                    <span class="attention"><!--{$arrErr[$key]}--><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>法人名(フリガナ)</th>
                <td>
                    <!--{assign var=key value="company_kana"}-->
                    <span class="attention"><!--{$arrErr[$key]}--><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <!--{*
            <tr>
                <th>部署名</th>
                <td>
                    <!--{assign var=key value="company_department"}-->
                    <span class="attention"><!--{$arrErr[$key]}--><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            *}-->
            <!--{/if}-->
            <!--{*## 顧客法人管理 ADD END ##*}-->
            
            <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
            <tr>
                <th>介護保護サービス指定事業所名<span class="attention">*</span></th>
                <td>
                    <!--{assign var=key value="company"}-->
                    <span class="attention"><!--{$arrErr[$key]}--><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>介護保護サービス指定事業所番号<span class="attention">*</span></th>
                <td>
                  <!--{assign var=key value="company_no"}-->
                  <span class="attention"><!--{$arrErr[$key]}--></span>
                  <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> class="box60" />
                </td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
            
            <tr>
                <th>ご担当者名<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.name01}--><!--{$arrErr.name02}--></span>
                    <input type="text" name="name01" value="<!--{$arrForm.name01|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="30" class="box30" <!--{if $arrErr.name01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />&nbsp;&nbsp;<input type="text" name="name02" value="<!--{$arrForm.name02|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="30" class="box30" <!--{if $arrErr.name02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>ご担当者名(フリガナ)<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.kana01}--><!--{$arrErr.kana02}--></span>
                    <input type="text" name="kana01" value="<!--{$arrForm.kana01|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="30" class="box30" <!--{if $arrErr.kana01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />&nbsp;&nbsp;<input type="text" name="kana02" value="<!--{$arrForm.kana02|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="30" class="box30" <!--{if $arrErr.kana02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            
            <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
            <tr>
                <th>指定事業所取得年月<span class="attention">*</span></th>
                <td>
                    <!--{assign var=key value="company_certified_date_year"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <select name="<!--{$key}-->" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                        <option value="" selected="selected">------</option>
                        <!--{html_options options=$arrYear selected=$arrForm[$key]}-->
                    </select>年
                    <!--{assign var=key value="company_certified_date_month"}-->
                    <select name="<!--{$key}-->" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                        <option value="" selected="selected">----</option>
                        <!--{html_options options=$arrMonth selected=$arrForm[$key]}-->
                    </select>月
                </td>
            </tr>
            <tr>
                <th>新規開業予定</th>
                <td>
                    <!--{assign var=key value="company_open_date_year"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <select name="<!--{$key}-->" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                        <option value="" selected="selected">------</option>
                        <!--{html_options options=$arrYear selected=$arrForm[$key]}-->
                    </select>年
                    <!--{assign var=key value="company_open_date_month"}-->
                    <select name="<!--{$key}-->" <!--{if $arrErr[$key] != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                        <option value="" selected="selected">----</option>
                        <!--{html_options options=$arrMonth selected=$arrForm[$key]}-->
                    </select>月
                </td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
            
            <tr>
                <th>郵便番号<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.zip01}--><!--{$arrErr.zip02}--></span>
                    〒 <input type="text" name="zip01" value="<!--{$arrForm.zip01|h}-->" maxlength="<!--{$smarty.const.ZIP01_LEN}-->" size="6" class="box6" maxlength="3" <!--{if $arrErr.zip01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="zip02" value="<!--{$arrForm.zip02|h}-->" maxlength="<!--{$smarty.const.ZIP02_LEN}-->" size="6" class="box6" maxlength="4" <!--{if $arrErr.zip02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                    <a class="btn-normal" href="javascript:;" name="address_input" onclick="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', 'zip01', 'zip02', 'pref', 'addr01'); return false;">住所入力</a>
                </td>
            </tr>
            <tr>
                <th>住所<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.pref}--><!--{$arrErr.addr01}--><!--{$arrErr.addr02}--></span>
                    <select class="top" name="pref" <!--{if $arrErr.pref != ""}--><!--{sfSetErrorStyle}--><!--{/if}-->>
                        <option class="top" value="" selected="selected">都道府県を選択</option>
                        <!--{html_options options=$arrPref selected=$arrForm.pref}-->
                    </select><br />
                    <input type="text" name="addr01" value="<!--{$arrForm.addr01|h}-->" size="60" class="box60" <!--{if $arrErr.addr01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /><br />
                    <!--{$smarty.const.SAMPLE_ADDRESS1}--><br />
                    <input type="text" name="addr02" value="<!--{$arrForm.addr02|h}-->" size="60" class="box60" <!--{if $arrErr.addr02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /><br />
                    <!--{$smarty.const.SAMPLE_ADDRESS2}-->
                </td>
            </tr>
            <tr>
                <th>メールアドレス<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.email}--></span>
                    <input type="text" name="email" value="<!--{$arrForm.email|h}-->" size="60" class="box60" <!--{if $arrErr.email != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>携帯メールアドレス</th>
                <td>
                    <span class="attention"><!--{$arrErr.email_mobile}--></span>
                    <input type="text" name="email_mobile" value="<!--{$arrForm.email_mobile|h}-->" size="60" class="box60" <!--{if $arrErr.email_mobile != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>電話番号<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.tel01}--><!--{$arrErr.tel02}--><!--{$arrErr.tel03}--></span>
                    <input type="text" name="tel01" value="<!--{$arrForm.tel01|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.tel01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="tel02" value="<!--{$arrForm.tel02|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.tel01 != "" || $arrErr.tel02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="tel03" value="<!--{$arrForm.tel03|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.tel01 != "" || $arrErr.tel03 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>FAX</th>
                <td>
                    <span class="attention"><!--{$arrErr.fax01}--><!--{$arrErr.fax02}--><!--{$arrErr.fax03}--></span>
                    <input type="text" name="fax01" value="<!--{$arrForm.fax01|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.fax01 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="fax02" value="<!--{$arrForm.fax02|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.fax01 != "" || $arrErr.fax02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> - <input type="text" name="fax03" value="<!--{$arrForm.fax03|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" size="6" class="box6" <!--{if $arrErr.fax01 != "" || $arrErr.fax03 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            <tr>
                <th>性別<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.sex}--></span>
                    <span <!--{if $arrErr.sex != ""}--><!--{sfSetErrorStyle}--><!--{/if}-->>
                        <!--{html_radios name="sex" options=$arrSex separator=" " selected=$arrForm.sex}-->
                    </span>
                </td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ DEL BEGIN ##*}-->
            <!--{*
            <tr>
                <th>ご職業</th>
                <td>
                    <span class="attention"><!--{$arrErr.job}--></span>
                    <select name="job" <!--{if $arrErr.job != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                    <option value="" selected="selected">選択してください</option>
                    <!--{html_options options=$arrJob selected=$arrForm.job}-->
                    </select>
                </td>
            </tr>
            <tr>
                <th>生年月日</th>
                <td>
                    <span class="attention"><!--{$arrErr.year}--></span>
                    <select name="year" <!--{if $arrErr.year != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                        <option value="" selected="selected">------</option>
                        <!--{html_options options=$arrYear selected=$arrForm.year}-->
                    </select>年
                    <select name="month" <!--{if $arrErr.year != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                        <option value="" selected="selected">----</option>
                        <!--{html_options options=$arrMonth selected=$arrForm.month}-->
                    </select>月
                    <select name="day" <!--{if $arrErr.year != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                        <option value="" selected="selected">----</option>
                        <!--{html_options options=$arrDay selected=$arrForm.day"}-->
                    </select>日
                </td>
            </tr>
            *}-->
            <!--{*## 会員登録項目カスタマイズ DEL END ##*}-->
            
            <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
            <tr>
                <th>事業者区分<span class="attention">*</span></th>
                <td>
                  <!--{assign var=key1 value="company_type"}-->
                  <span class="attention"><!--{$arrErr[$key1]}--></span>
                  <span style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                  <!--{html_checkboxes name=$key1 options=$arrCAMPANY_TYPE selected=$arrForm[$key1] separator='&nbsp;&nbsp;'}-->
                  </span>
                </td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
            
            <tr>
                <th>パスワード<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.password}--><!--{$arrErr.password02}--></span>
                    <input type="password" name="password" value="<!--{$arrForm.password|h}-->" size="30" class="box30" <!--{if $arrErr.password != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />　半角英数小文字<!--{$smarty.const.PASSWORD_MIN_LEN}-->～<!--{$smarty.const.PASSWORD_MAX_LEN}-->文字（記号不可）<br />
                    <input type="password" name="password02" value="<!--{$arrForm.password02|h}-->" size="30" class="box30" <!--{if $arrErr.password02 != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                    <p><span class="attention mini">確認のために2度入力してください。</span></p>
                </td>
            </tr>
            <tr>
                <th>パスワードを忘れたときのヒント<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.reminder}--><!--{$arrErr.reminder_answer}--></span>
                    質問：
                    <select class="top" name="reminder" <!--{if $arrErr.reminder != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> >
                        <option value="" selected="selected">選択してください</option>
                        <!--{html_options options=$arrReminder selected=$arrForm.reminder}-->
                    </select><br />
                    答え：
                    <input type="text" name="reminder_answer" value="<!--{$arrForm.reminder_answer|h}-->" size="30" class="box30" <!--{if $arrErr.reminder_answer != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> />
                </td>
            </tr>
            
            <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
            <tr>
                <th>通信欄</th>
                <td><!--{$arrForm.message|h}--></td>
                <input type="hidden" name="message" value="<!--{$arrForm.message|h}-->" />
            </tr>
            <tr>
                <th>カタログ希望</th>
                <td>
                  <input type="checkbox" id="need_category_check" name="need_category_check" value="1" <!--{if $arrForm.need_category_check==1}-->checked<!--{/if}-->/>
                </td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
            
            <tr>
                <th>メールマガジン<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.mailmaga_flg}--></span>
                    <span <!--{if $arrErr.mailmaga_flg != ""}--><!--{sfSetErrorStyle}--><!--{/if}-->>
                        <!--{html_radios name="mailmaga_flg" options=$arrMailMagazineType separator=" " selected=$arrForm.mailmaga_flg}-->
                    </span>
                </td>
            </tr>
            <tr>
                <th>SHOP用メモ</th>
                <td>
                    <span class="attention"><!--{$arrErr.note}--></span>
                    <textarea name="note" maxlength="<!--{$smarty.const.LTEXT_LEN}-->" <!--{if $arrErr.note != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> cols="60" rows="8" class="area60"><!--{"\n"}--><!--{$arrForm.note|h}--></textarea>
                </td>
            </tr>
            <tr>
                <th>所持ポイント<span class="attention"> *</span></th>
                <td>
                    <span class="attention"><!--{$arrErr.point}--></span>
                    <input type="text" name="point" value="<!--{$arrForm.point|h}-->" maxlength="<!--{$smarty.const.TEL_LEN}-->" size="6" class="box6" <!--{if $arrErr.point != ""}--><!--{sfSetErrorStyle}--><!--{/if}--> /> pt
                </td>
            </tr>
        </table>

        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="return fnReturn();"><span class="btn-prev">検索画面に戻る</span></a></li>
                <li><a class="btn-action" href="javascript:;" onclick="fnSetFormSubmit('form1', 'mode', 'confirm'); return false;"><span class="btn-next">確認ページへ</span></a></li>
            </ul>
        </div>

<!--{*## 顧客管理画面にお届け先一覧表示 ADD BEGIN ##*}-->
<!--{if $smarty.const.USE_ADMIN_CUSTOMER_DELIV_LIST === true}-->
        <!--{assign var=multipager_prefix value="deliv"}-->
        <input type="hidden" name="search_deliv_pageno" value="<!--{$tpl_multi_pageno[$multipager_prefix]}-->">

        <h2>お届け先一覧</h2>
        <!--{if $tpl_multi_linemax[$multipager_prefix] > 0}-->
        <p><span class="attention"><!--お届け先一覧--><!--{$tpl_multi_linemax[$multipager_prefix]}-->件</span>&nbsp;が該当しました。</p>

        <!--{include file=$tpl_multi_pager}-->

            <!--{* お届け先一覧表示テーブル *}-->
            <table class="list">
                <tr>
                    <th>お届け先ID</th>
                    <!--{if $smarty.const.USE_ADMIN_CUSTOMER_DELIV_LIST === true}-->
                    <th>法人名</th>
                    <!--{/if}-->
                    <th>お名前</th>
                    <th>住所</th>
                </tr>
                <!--{section name=cnt loop=$arrOtherDeliv}-->
                    <tr>
                        <td><!--{$arrOtherDeliv[cnt].other_deliv_id}--></td>
                    <!--{if $smarty.const.USE_ADMIN_CUSTOMER_DELIV_LIST === true}-->
                        <td class="center"><!--{$arrOtherDeliv[cnt].company|h}--> <!--{$arrOtherDeliv[cnt].company_department|h}--></td>
                    <!--{/if}-->
                        <td class="center"><!--{$arrOtherDeliv[cnt].name01|h}--> <!--{$arrOtherDeliv[cnt].name02|h}--></td>
                        <!--{assign var=pref_id value=$arrOtherDeliv[cnt].pref}-->
                        <td class="left">
                          〒<!--{$arrOtherDeliv[cnt].zip01|h}-->-<!--{$arrOtherDeliv[cnt].zip02|h}-->  <!--{$arrPref[$pref_id]|h}--><!--{$arrOtherDeliv[cnt].addr01|h}--><!--{$arrOtherDeliv[cnt].addr02|h}-->
                        </td>
                    </tr>
                <!--{/section}-->
            </table>
            <!--{* お届け先一覧表示テーブル *}-->
        <!--{else}-->
            <div class="message">お届け先情報はありません。</div>
        <!--{/if}-->
        <!--{assign var=multipager_prefix value=""}--><!--{* unset multipager_prefix *}-->
<!--{/if}-->
<!--{*## 顧客管理画面にお届け先一覧表示 ADD END ##*}-->

        <input type="hidden" name="order_id" value="" />
        <input type="hidden" name="search_pageno" value="<!--{$tpl_pageno}-->">
        <input type="hidden" name="edit_customer_id" value="<!--{$edit_customer_id}-->" >

        <h2>購入履歴一覧</h2>
        <!--{if $tpl_linemax > 0}-->
        <p><span class="attention"><!--購入履歴一覧--><!--{$tpl_linemax}-->件</span>&nbsp;が該当しました。</p>

        <!--{include file=$tpl_pager}-->

            <!--{* 購入履歴一覧表示テーブル *}-->
            <table class="list">
                <tr>
                    <th>日付</th>
                    <th>注文番号</th>
                    <th>購入金額</th>
                    <th>発送日</th>
                    <th>支払方法</th>
                </tr>
                <!--{section name=cnt loop=$arrPurchaseHistory}-->
                    <tr>
                        <td><!--{$arrPurchaseHistory[cnt].create_date|sfDispDBDate}--></td>
                        <td class="center"><a href="../order/edit.php?order_id=<!--{$arrPurchaseHistory[cnt].order_id}-->" ><!--{$arrPurchaseHistory[cnt].order_id}--></a></td>
                        <td class="center"><!--{$arrPurchaseHistory[cnt].payment_total|number_format}-->円</td>
                        <td class="center"><!--{if $arrPurchaseHistory[cnt].status eq 5}--><!--{$arrPurchaseHistory[cnt].commit_date|sfDispDBDate}--><!--{else}-->未発送<!--{/if}--></td>
                        <!--{assign var=payment_id value="`$arrPurchaseHistory[cnt].payment_id`"}-->
                        <td class="center"><!--{$arrPayment[$payment_id]|h}--></td>
                    </tr>
                <!--{/section}-->
            </table>
            <!--{* 購入履歴一覧表示テーブル *}-->
        <!--{else}-->
            <div class="message">購入履歴はありません。</div>
        <!--{/if}-->

    </div>
</form>
