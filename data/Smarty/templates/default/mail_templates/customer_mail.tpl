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
　※本メールは自動配信メールです。
　等幅フォント(MSゴシック12ポイント、Osaka-等幅など)で
　最適にご覧になれます。

┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
　※本メールは、
　<!--{$CONF.shop_name}-->より会員登録を希望された方に
　お送りしています。
　もしお心当たりが無い場合はこのままこのメールを破棄していただ
　ければ会員登録はなされません。
　またその旨 <!--{* 問い合わせ受付メール *}--><!--{$CONF.email02}--> まで
　ご連絡いただければ幸いです。
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛

<!--{$name01}--> <!--{$name02}--> 様

<!--{$CONF.shop_name}-->でございます。
仮登録が完了しました。本会員登録承認までお待ちください。

介護保護サービス指定事業所名：<!--{$arrForm.company|h}-->
介護保護サービス指定事業所番号：<!--{$arrForm.company_no|h}-->
ご担当者名：<!--{$arrForm.name01|h}--> <!--{$arrForm.name02|h}--> (<!--{$arrForm.kana01|h}--> <!--{$arrForm.kana02|h}-->)

指定事業所取得年月：<!--{$arrForm.company_certified_date_year|h}-->年<!--{$arrForm.company_certified_date_month|h}-->月
新規開業予定：<!--{if strlen($arrForm.company_open_date_year) > 0 && strlen($arrForm.company_open_date_month) > 0}-->
<!--{$arrForm.company_open_date_year|h}-->年<!--{$arrForm.company_open_date_month|h}-->月<!--{else}-->
未登録
<!--{/if}-->

郵便番号：〒<!--{$arrForm.zip01|h}--> - <!--{$arrForm.zip02|h}-->
住所：<!--{$arrPref[$arrForm.pref]|h}--><!--{$arrForm.addr01|h}--><!--{$arrForm.addr02|h}-->
電話番号：<!--{$arrForm.tel01|h}--> - <!--{$arrForm.tel02|h}--> - <!--{$arrForm.tel03|h}-->
FAX：<!--{if strlen($arrForm.fax01) > 0 && strlen($arrForm.fax02) > 0 && strlen($arrForm.fax03) > 0}-->
<!--{$arrForm.fax01|h}--> - <!--{$arrForm.fax02|h}--> - <!--{$arrForm.fax03|h}-->
<!--{else}-->
未登録
<!--{/if}-->

メールアドレス：<!--{$arrForm.email}-->
性別：<!--{if $arrForm.sex eq 1}-->
男性
<!--{else}-->
女性
<!--{/if}-->
事業者区分：<!--{foreach key=key item=item from=$arrForm.company_type}--><!--{$arrCAMPANY_TYPE[$item]|h}-->　 <!--{/foreach}-->

パスワード：登録いただいたパスワード
通信欄：<!--{$arrForm.message|h}-->
カタログ希望：<!--{if $arrForm.need_category_check == 1}-->はい<!--{else}-->いいえ<!--{/if}-->

メールマガジン送付について：<!--{if $arrForm.mailmaga_flg eq 1}-->
HTMLメール＋テキストメールを受け取る
<!--{elseif $arrForm.mailmaga_flg eq 2}-->
テキストメールを受け取る
<!--{else}-->
受け取らない
<!--{/if}-->



