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
<col width="32%" />
<col width="68%" />
<!--{*## 顧客法人管理 ADD BEGIN ##*}-->
<!--{if $smarty.const.USE_CUSTOMER_COMPANY === true}-->
<tr>
    <th nowrap>法人名<span class="attention">※</span></th>
    <td>
        <!--{assign var=key value="company"}-->
        <!--{if $arrErr[$key]}-->
            <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
       <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->; ime-mode: active;" class="box300" />
    </td>
</tr>
<tr>
    <th>法人名(フリガナ)</th>
    <td>
        <!--{assign var=key value="company_kana"}-->
        <!--{if $arrErr[$key]}-->
            <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
       <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->; ime-mode: active;" class="box300" />
    </td>
</tr>
<!--{*
<tr>
    <th>部署名</th>
    <td>
        <!--{assign var=key value="company_department"}-->
        <!--{if $arrErr[$key]}-->
            <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
       <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->; ime-mode: active;" class="box300" />
    </td>
</tr>
*}-->
<!--{/if}-->
<!--{*## 顧客法人管理 ADD END ##*}-->

<!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
<tr>
    <th nowrap>介護保護サービス指定事業所名<span class="attention">※</span></th>
    <td>
        <!--{assign var=key value="company"}-->
        <!--{if $arrErr[$key]}-->
            <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
       <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->; ime-mode: active;" class="box300" />
    </td>
</tr>
<!--{if $flgFields > 1}-->
<tr>
    <th nowrap>介護保護サービス指定事業所番号<span class="attention">※</span></th>
    <td>
        <!--{assign var=key value="company_no"}-->
        <!--{if $arrErr[$key]}-->
            <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
       <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->; ime-mode: active;" class="box300" />
    </td>
</tr>
<!--{/if}-->
<!--{*## 会員登録項目カスタマイズ ADD END ##*}-->

<tr>
    <th>ご担当者名<span class="attention">※</span></th>
    <td>
        <!--{assign var=key1 value="`$prefix`name01"}-->
        <!--{assign var=key2 value="`$prefix`name02"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        姓&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: active;" class="box120" />&nbsp;
        名&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: active;" class="box120" />
    </td>
</tr>
<tr>
    <th>ご担当者名(フリガナ)<span class="attention">※</span></th>
    <td>
        <!--{assign var=key1 value="`$prefix`kana01"}-->
        <!--{assign var=key2 value="`$prefix`kana02"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        セイ&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: active;" class="box120" />&nbsp;
        メイ&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: active;" class="box120" />
    </td>
</tr>

<!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
<!--{if $flgFields > 1}-->
<tr>
    <th>指定事業所取得年月<span class="attention">※</span></th>
    <td>
        <!--{assign var=err_company_certified_date value="`$arrErr.company_certified_date_year``$arrErr.company_certified_date_month`"}-->
        <!--{if $err_company_certified_date}-->
            <div class="attention"><!--{$err_company_certified_date}--></div>
        <!--{/if}-->
        <select name="company_certified_date_year" style="<!--{$err_company_certified_date|sfGetErrorColor}-->">
            <!--{html_options options=$arrYear selected=$arrForm.company_certified_date_year|default:''}-->
        </select>年
        <select name="company_certified_date_month" style="<!--{$err_company_certified_date|sfGetErrorColor}-->">
            <!--{html_options options=$arrMonth selected=$arrForm.company_certified_date_month|default:''}-->
        </select>月
    </td>
</tr>
<tr>
    <th>新規開業予定</th>
    <td>
        <!--{assign var=err_company_open_date value="`$arrErr.company_open_date_year``$arrErr.company_open_date_month`"}-->
        <!--{if $err_company_open_date}-->
            <div class="attention"><!--{$err_company_open_date}--></div>
        <!--{/if}-->
        <select name="company_open_date_year" style="<!--{$err_company_open_date|sfGetErrorColor}-->">
            <!--{html_options options=$arrYear selected=$arrForm.company_open_date_year|default:''}-->
        </select>年
        <select name="company_open_date_month" style="<!--{$err_company_open_date|sfGetErrorColor}-->">
            <!--{html_options options=$arrMonth selected=$arrForm.company_open_date_month|default:''}-->
        </select>月
        &nbsp;<span class="mini">すでに開業済の場合は入力不要です。</span>
    </td>
</tr>
<!--{/if}-->
<!--{*## 会員登録項目カスタマイズ ADD END ##*}-->

<tr>
    <th>郵便番号<span class="attention">※</span></th>
    <td>
        <!--{assign var=key1 value="`$prefix`zip01"}-->
        <!--{assign var=key2 value="`$prefix`zip02"}-->
        <!--{assign var=key3 value="`$prefix`pref"}-->
        <!--{assign var=key4 value="`$prefix`addr01"}-->
        <!--{assign var=key5 value="`$prefix`addr02"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        <p class="top">〒&nbsp;<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.ZIP01_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />&nbsp;-&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.ZIP02_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />&nbsp;
        <a href="http://search.post.japanpost.jp/zipcode/" target="_blank"><span class="mini">郵便番号検索</span></a>
        </p>
        <p class="zipimg">
            <a href="<!--{$smarty.const.ROOT_URLPATH}-->input_zip.php" onclick="fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', '<!--{$key1}-->', '<!--{$key2}-->', '<!--{$key3}-->', '<!--{$key4}-->'); return false;" target="_blank">
                <img src="<!--{$TPL_URLPATH}-->img/button/btn_address_input.jpg" alt="住所自動入力" /></a>
            &nbsp;<span class="mini">郵便番号を入力後、クリックしてください。</span>
        </p>
    </td>
</tr>
<tr>
    <th>住所<span class="attention">※</span></th>
    <td>
        <!--{if $arrErr[$key3] || $arrErr[$key4] || $arrErr[$key5]}-->
            <div class="attention"><!--{$arrErr[$key3]}--><!--{$arrErr[$key4]}--><!--{$arrErr[$key5]}--></div>
        <!--{/if}-->
        <select name="<!--{$key3}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->">
                <option value="" selected="selected">都道府県を選択</option>
                <!--{html_options options=$arrPref selected=$arrForm[$key3]}-->
        </select>
        <p class="top"><input type="text" name="<!--{$key4}-->" value="<!--{$arrForm[$key4]|h}-->" class="box300" style="<!--{$arrErr[$key4]|sfGetErrorColor}-->; ime-mode: active;" /><br />
            <!--{$smarty.const.SAMPLE_ADDRESS1}--></p>
        <p class="top"><input type="text" name="<!--{$key5}-->" value="<!--{$arrForm[$key5]|h}-->" class="box300" style="<!--{$arrErr[$key5]|sfGetErrorColor}-->; ime-mode: active;" /><br />
            <!--{$smarty.const.SAMPLE_ADDRESS2}--></p>
        <p class="mini"><span class="attention">住所は2つに分けてご記入ください。マンション名は必ず記入してください。</span></p>
    </td>
</tr>
<tr>
    <th>電話番号<span class="attention">※</span></th>
    <td>
        <!--{assign var=key1 value="`$prefix`tel01"}-->
        <!--{assign var=key2 value="`$prefix`tel02"}-->
        <!--{assign var=key3 value="`$prefix`tel03"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />&nbsp;-&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />&nbsp;-&nbsp;<input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />
    </td>
</tr>
<tr>
    <th>FAX</th>
    <td>
        <!--{assign var=key1 value="`$prefix`fax01"}-->
        <!--{assign var=key2 value="`$prefix`fax02"}-->
        <!--{assign var=key3 value="`$prefix`fax03"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />&nbsp;-&nbsp;<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />&nbsp;-&nbsp;<input type="text" name="<!--{$key3}-->" value="<!--{$arrForm[$key3]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->; ime-mode: disabled;" class="box60" />
    </td>
</tr>

<!--{if $flgFields > 1}-->
    <tr>
        <th>メールアドレス<span class="attention">※</span></th>
        <td>
            <!--{assign var=key1 value="`$prefix`email"}-->
            <!--{assign var=key2 value="`$prefix`email02"}-->
            <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
            <!--{/if}-->
            <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" class="box300 top" /><br />
            <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" style="<!--{$arrErr[$key1]|cat:$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" class="box300" /><br />
            <span class="attention mini">確認のため2度入力してください。</span>
        </td>
    </tr>
    <!--{if $emailMobile}-->
        <tr>
            <th>携帯メールアドレス</th>
            <td>
                <!--{assign var=key1 value="`$prefix`email_mobile"}-->
                <!--{assign var=key2 value="`$prefix`email_mobile02"}-->
                <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
                <!--{/if}-->
                <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->; ime-mode: disabled;" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" class="box300 top" /><br />
                <input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" style="<!--{$arrErr[$key1]|cat:$arrErr[$key2]|sfGetErrorColor}-->; ime-mode: disabled;" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" class="box300" /><br />
                <span class="attention mini">確認のため2度入力してください。</span>
            </td>
        </tr>
    <!--{/if}-->
    <tr>
        <th>性別<span class="attention">※</span></th>
        <td>
            <!--{assign var=key1 value="`$prefix`sex"}-->
            <!--{if $arrErr[$key1]}-->
                <div class="attention"><!--{$arrErr[$key1]}--></div>
            <!--{/if}-->
            <span style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                <input type="radio" id="man" name="<!--{$key1}-->" value="1" <!--{if $arrForm[$key1] eq 1}--> checked="checked" <!--{/if}--> /><label for="man">男性</label>
                <input type="radio" id="woman" name="<!--{$key1}-->" value="2" <!--{if $arrForm[$key1] eq 2}--> checked="checked" <!--{/if}--> /><label for="woman">女性</label>
            </span>
        </td>
    </tr>

    <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
    <tr>
        <th>事業者区分<span class="attention">※</span></th>
        <td>
            <!--{assign var=key1 value="company_type"}-->
            <!--{if $arrErr[$key1]}-->
                <div class="attention"><!--{$arrErr[$key1]}--></div>
            <!--{/if}-->
            <span style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
            <!--{html_checkboxes name=$key1 options=$arrCAMPANY_TYPE selected=$arrForm[$key1] separator='&nbsp;&nbsp;'}-->
            </span>
        </td>
    </tr>
    <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
    <!--{*## 会員登録項目カスタマイズ DEL BEGIN ##*}-->
    <!--{*
    <tr>
        <th>職業</th>
        <td>
            <!--{assign var=key1 value="`$prefix`job"}-->
            <!--{if $arrErr[$key1]}-->
                <div class="attention"><!--{$arrErr[$key1]}--></div>
            <!--{/if}-->
            <select name="<!--{$key1}-->">
                <option value="" selected="selected">選択してください</option>
                <!--{html_options options=$arrJob selected=$arrForm[$key1]}-->
            </select>
        </td>
    </tr>
    <tr>
        <th>生年月日</th>
        <td>
            <!--{assign var=errBirth value="`$arrErr.year``$arrErr.month``$arrErr.day`"}-->
            <!--{if $errBirth}-->
                <div class="attention"><!--{$errBirth}--></div>
            <!--{/if}-->
            <select name="year" style="<!--{$errBirth|sfGetErrorColor}-->">
                <!--{html_options options=$arrYear selected=$arrForm.year|default:''}-->
            </select>年
            <select name="month" style="<!--{$errBirth|sfGetErrorColor}-->">
                <!--{html_options options=$arrMonth selected=$arrForm.month|default:''}-->
            </select>月
            <select name="day" style="<!--{$errBirth|sfGetErrorColor}-->">
                <!--{html_options options=$arrDay selected=$arrForm.day|default:''}-->
            </select>日
        </td>
    </tr>
    *}-->
    <!--{*## 会員登録項目カスタマイズ DEL END ##*}-->
    <!--{if $flgFields > 2}-->
        <tr>
            <th>希望するパスワード<span class="attention">※</span><br />
            </th>
            <td>
                <!--{if $arrErr.password || $arrErr.password02}-->
                    <div class="attention"><!--{$arrErr.password}--><!--{$arrErr.password02}--></div>
                <!--{/if}-->
                <input type="password" name="password" value="<!--{$arrForm.password|h}-->" maxlength="<!--{$smarty.const.PASSWORD_MAX_LEN}-->" style="<!--{$arrErr.password|sfGetErrorColor}-->" class="box120" />
                <p><span class="attention mini">半角英数字<!--{$smarty.const.PASSWORD_MIN_LEN}-->～<!--{$smarty.const.PASSWORD_MAX_LEN}-->文字でお願いします。（記号不可）</span></p>
                <input type="password" name="password02" value="<!--{$arrForm.password02|h}-->" maxlength="<!--{$smarty.const.PASSWORD_MAX_LEN}-->" style="<!--{$arrErr.password|cat:$arrErr.password02|sfGetErrorColor}-->" class="box120" />
                <p><span class="attention mini">確認のために2度入力してください。</span></p>
            </td>
        </tr>
        <tr>
            <th>パスワードを忘れた時のヒント<span class="attention">※</span></th>
            <td>
                <!--{if $arrErr.reminder || $arrErr.reminder_answer}-->
                    <div class="attention"><!--{$arrErr.reminder}--><!--{$arrErr.reminder_answer}--></div>
                <!--{/if}-->
                質問：
                <select name="reminder" style="<!--{$arrErr.reminder|sfGetErrorColor}-->">
                    <option value="" selected="selected">選択してください</option>
                    <!--{html_options options=$arrReminder selected=$arrForm.reminder}-->
                </select>
                <br />
                答え：<input type="text" name="reminder_answer" value="<!--{$arrForm.reminder_answer|h}-->" style="<!--{$arrErr.reminder_answer|sfGetErrorColor}-->; ime-mode: active;" class="box260" />
            </td>
        </tr>
        
        <!--{*## 会員登録項目カスタマイズ ADD BEGIN ##*}-->
        <tr>
            <th>通信欄</th>
            <td>
                <!--{assign var=key1 value="message"}-->
                <!--{if $arrErr[$key1]}-->
                    <div class="attention"><!--{$arrErr[$key1]}--></div>
                <!--{/if}-->
                <textarea name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" cols="70" rows="6" class="txtarea" wrap="hard"><!--{$arrForm[$key1]|h}--></textarea><br />
                <input type="checkbox" id="need_category_check" name="need_category_check" value="1" 
                  <!--{if $arrForm.need_category_check==1}-->checked<!--{/if}-->/>
                <label for="need_category_check">
                  <span class="attention mini">
                  介護用品、福祉総合用品カタログ最新号を送ってほしい場合はチェックしてください。<br />
                  ※介護保険指定サービス事業者以外へのカタログ送付は原則としておこなっておりません。
                  </span>
                </label>
            </td>
        </tr>
        <!--{*## 会員登録項目カスタマイズ ADD END ##*}-->
        
        <tr>
            <th>メールマガジン送付について<span class="attention">※</span></th>
            <td>
                <!--{if $arrErr.mailmaga_flg}-->
                    <div class="attention"><!--{$arrErr.mailmaga_flg}--></div>
                <!--{/if}-->
                <span style="<!--{$arrErr.mailmaga_flg|sfGetErrorColor}-->">
                    <input type="radio" name="mailmaga_flg" value="1" id="html" <!--{if $arrForm.mailmaga_flg eq 1}--> checked="checked" <!--{/if}--> /><label for="html">HTMLメール＋テキストメールを受け取る</label><br />
                    <input type="radio" name="mailmaga_flg" value="2" id="text" <!--{if $arrForm.mailmaga_flg eq 2}--> checked="checked" <!--{/if}--> /><label for="text">テキストメールを受け取る</label><br />
                    <input type="radio" name="mailmaga_flg" value="3" id="no" <!--{if $arrForm.mailmaga_flg eq 3}--> checked="checked" <!--{/if}--> /><label for="no">受け取らない</label>
                </span>
                <p><span class="attention mini">
                ウェブ限定セールや、在庫セールなどウェブ注文ならではのお得な情報をお届け致します。<br />
                ※初期登録の「メールマガジン配信」は「希望する」になっています。<br />
                「希望しない」場合は、ご利用開始後に会員情報ページより変更ください。
                </span></p>
            </td>
        </tr>
    <!--{/if}-->
<!--{/if}-->
