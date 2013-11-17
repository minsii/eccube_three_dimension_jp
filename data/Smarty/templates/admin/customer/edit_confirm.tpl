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

function func_return(){
    document.form1.mode.value = "return";
    document.form1.submit();
}

//-->
</script>


<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="complete" />

    <!--{foreach from=$arrForm key=key item=item}-->
        <!--{if $key ne "mode" && $key ne "subm" && $key ne $smarty.const.TRANSACTION_ID_NAME}-->
          <!--{*## 会員登録項目カスタマイズ MDF BEGIN ##*}-->
          <!--{if is_array($item)}-->
            <!--{foreach from=$item key=val_key item=val_item}-->
            <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$val_item|h}-->" />
            <!--{/foreach}-->
          <!--{else}-->
            <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
          <!--{/if}-->
          <!--{*## 会員登録項目カスタマイズ MDF END ##*}-->
        <!--{/if}-->
    <!--{/foreach}-->

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
            <tr>
                <th>会員ID</th>
                <td><!--{$arrForm.customer_id|h}--></td>
            </tr>
            <tr>
                <th>会員状態</th>
                <td><!--{$arrStatus[$arrForm.status]}--></td>
            </tr>

            <!--{*## 顧客法人管理 ADD BEGIN ##*}-->
            <!--{if $smarty.const.USE_CUSTOMER_COMPANY === true}-->
            <tr>
                <th>法人名</th>
                <td><!--{$arrForm.company|h}--></td>
            </tr>
            <tr>
                <th>法人名(フリガナ)</th>
                <td><!--{$arrForm.company_kana|h}--></td>
            </tr>
            <!--{*
            <tr>
                <th>部署名</th>
                <td><!--{$arrForm.company_department|h}--></td>
            </tr>
            *}-->
            <!--{/if}-->
            <!--{*## 顧客法人管理 ADD END ##*}-->
            <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
            <tr>
                <th>介護保護サービス指定事業所名</th>
                <td><!--{$arrForm.company|h}--></td>
            </tr>
            <tr>
                <th>介護保護サービス指定事業所番号</th>
                <td><!--{$arrForm.company_no|h}--></td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
            <tr>
                <th>ご担当者</th>
                <td><!--{$arrForm.name01|h}--><!--{$arrForm.name02|h}-->　様</td>
            </tr>
            <tr>
                <th>ご担当者(フリガナ)</th>
                <td><!--{$arrForm.kana01|h}--><!--{$arrForm.kana02|h}-->　様</td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
            <tr>
                <th>指定事業所取得年月</th>
                <td>
                    <!--{$arrForm.company_certified_date_year|h}-->年<!--{$arrForm.company_certified_date_month|h}-->月
                </td>
            </tr>
            <tr>
                <th>新規開業予定</th>
                <td>
                    <!--{if strlen($arrForm.company_open_date_year) > 0 && strlen($arrForm.company_open_date_month) > 0}-->
                        <!--{$arrForm.company_open_date_year|h}-->年<!--{$arrForm.company_open_date_month|h}-->月
                    <!--{else}-->
                    未登録
                    <!--{/if}-->
                </td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
            <tr>
                <th>郵便番号</th>
                <td>〒 <!--{$arrForm.zip01|h}--> - <!--{$arrForm.zip02|h}--></td>
            </tr>
            <tr>
                <th>住所</th>
                <td><!--{$arrPref[$arrForm.pref]|h}--><!--{$arrForm.addr01|h}--><!--{$arrForm.addr02|h}--></td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td><!--{$arrForm.email|h}--></td>
            </tr>
            <tr>
                <th>携帯メールアドレス</th>
                <td><!--{$arrForm.email_mobile|h}--></td>
            </tr>
            <tr>
                <th>お電話番号</th>
                <td><!--{$arrForm.tel01|h}--> - <!--{$arrForm.tel02|h}--> - <!--{$arrForm.tel03|h}--></td>
            </tr>
            <tr>
                <th>FAX</th>
                <td><!--{if strlen($arrForm.fax01) > 0}--><!--{$arrForm.fax01|h}--> - <!--{$arrForm.fax02|h}--> - <!--{$arrForm.fax03|h}--><!--{else}-->未登録<!--{/if}--></td>
            </tr>
            <tr>
                <th>性別</th>
                <td><!--{$arrSex[$arrForm.sex]|h}--></td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
            <tr>
                <th>事業者区分</th>
                <td>
                <!--{foreach key=key item=item from=$arrForm.company_type}-->
                  <!--{$arrCAMPANY_TYPE[$item]|h}--> <br />
                <!--{/foreach}-->
                </td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
            <!--{*## 会員登録項目カスタマイズ DEL BEGIN ##*}-->
<!--{*
            <tr>
                <th>ご職業</th>
                <td><!--{$arrJob[$arrForm.job]|default:"未登録"|h}--></td>
            </tr>
            <tr>
                <th>生年月日</th>
                <td><!--{if strlen($arrForm.year) > 0 && strlen($arrForm.month) > 0 && strlen($arrForm.day) > 0}--><!--{$arrForm.year|h}-->年<!--{$arrForm.month|h}-->月<!--{$arrForm.day|h}-->日<!--{else}-->未登録<!--{/if}--></td>
            </tr>
*}-->
            <!--{*## 会員登録項目カスタマイズ DEL END ##*}-->
            <tr>
                <th>パスワード</th>
                <td><!--{$smarty.const.DEFAULT_PASSWORD}--></td>
            </tr>
            <tr>
                <th>パスワードを忘れたときのヒント</th>
                <td>
                    質問： <!--{$arrReminder[$arrForm.reminder]|h}--><br />
                    答え： <!--{$smarty.const.DEFAULT_PASSWORD}-->
                </td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
            <tr>
                <th>通信欄</th>
                <td><!--{$arrForm.message|h}--></td>
            </tr>
            <tr>
                <th>カタログ希望</th>
                <td><!--{if $arrForm.need_category_check == 1}-->はい<!--{else}-->いいえ<!--{/if}--></td>
            </tr>
            <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
            <tr>
                <th>メールマガジン</th>
                <td><!--{if $arrForm.mailmaga_flg eq 1}-->HTML<!--{elseif $arrForm.mailmaga_flg eq 2}-->テキスト<!--{else}-->希望しない<!--{/if}--></td>
            </tr>
            <tr>
                <th>SHOP用メモ</th>
                <td><!--{$arrForm.note|h|nl2br|default:"未登録"}--></td>
            </tr>
            <tr>
                <th>所持ポイント</th>
                <td><!--{$arrForm.point|default:"0"|h}--> pt</td>
            </tr>
        </table>
        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="func_return(); return false;"><span class="btn-prev">編集画面に戻る</span></a></li>
                <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('form1', 'complete', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
    </div>
</form>
