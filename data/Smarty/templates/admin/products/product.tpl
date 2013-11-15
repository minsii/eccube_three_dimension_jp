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
<script type="text/javascript">
// 表示非表示切り替え
function lfDispSwitch(id){
    var obj = document.getElementById(id);
    if (obj.style.display == 'none') {
        obj.style.display = '';
    } else {
        obj.style.display = 'none';
    }
}

// セレクトボックスのリストを移動
// (移動元セレクトボックスID, 移動先セレクトボックスID)
function fnMoveSelect(select, target) {
    $('#' + select).children().each(function() {
        if (this.selected) {
            $('#' + target).append(this);
            $(this).attr({selected: false});
        }
    });
    // IE7再描画不具合対策
    if ($.browser.msie && $.browser.version >= 7) {
        $('#' + select).hide();
        $('#' + select).show();
        $('#' + target).hide();
        $('#' + target).show();
    }
}

// target の子要素を選択状態にする
function selectAll(target) {
    $('#' + target).children().attr({selected: true});
}

<!--{*## その他商品項目カスタマイズ ADD BEGIN ##*}-->
$(function() {
    fckeditorCreate();
});

function fckeditorCreate(){ 
    var oFCKeditor = new FCKeditor() ;
    oFCKeditor.BasePath    = '<!--{$TPL_URLPATH}-->js/fckeditor/' ;
    oFCKeditor.Height='220';
    oFCKeditor.InstanceName = 'main_comment';
    oFCKeditor.ToolbarSet = 'ECCUBEcat';
    oFCKeditor.ReplaceTextarea() ;

    var oFCKeditor2 = new FCKeditor() ;
    oFCKeditor2.BasePath    = '<!--{$TPL_URLPATH}-->js/fckeditor/' ;
    oFCKeditor2.Height='220';
    oFCKeditor2.InstanceName = 'comment5';
    oFCKeditor2.ToolbarSet = 'ECCUBEcat';
    oFCKeditor2.ReplaceTextarea() ;

    var oFCKeditor3 = new FCKeditor() ;
    oFCKeditor3.BasePath    = '<!--{$TPL_URLPATH}-->js/fckeditor/' ;
    oFCKeditor3.Height='220';
    oFCKeditor3.InstanceName = 'comment6';
    oFCKeditor3.ToolbarSet = 'ECCUBEcat';
    oFCKeditor3.ReplaceTextarea() ;
}
<!--{*## その他商品項目カスタマイズ ADD END ##*}-->
</script>

<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<!--{foreach key=key item=item from=$arrSearchHidden}-->
    <!--{if is_array($item)}-->
        <!--{foreach item=c_item from=$item}-->
        <input type="hidden" name="<!--{$key|h}-->[]" value="<!--{$c_item|h}-->" />
        <!--{/foreach}-->
    <!--{else}-->
        <input type="hidden" name="<!--{$key|h}-->" value="<!--{$item|h}-->" />
    <!--{/if}-->
<!--{/foreach}-->
<input type="hidden" name="mode" value="edit" />
<input type="hidden" name="image_key" value="" />
<input type="hidden" name="down_key" value="">
<input type="hidden" name="product_id" value="<!--{$arrForm.product_id|h}-->" />
<input type="hidden" name="product_class_id" value="<!--{$arrForm.product_class_id|h}-->" />
<input type="hidden" name="copy_product_id" value="<!--{$arrForm.copy_product_id|h}-->" />
<input type="hidden" name="anchor_key" value="" />
<input type="hidden" name="select_recommend_no" value="" />
<input type="hidden" name="has_product_class" value="<!--{$arrForm.has_product_class|h}-->" />
<!--{foreach key=key item=item from=$arrForm.arrHidden}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->" />
<!--{/foreach}-->
<div id="products" class="contents-main">
    <h2>基本情報</h2>

    <table class="form">
        <tr>
            <th>商品ID</th>
            <td><!--{$arrForm.product_id|h}--></td>
        </tr>
        <tr>
            <th>商品名<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.name}--></span>
                <input type="text" name="name" value="<!--{$arrForm.name|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.name != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" size="60" class="box60" />
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <!--{*# 商品登録日表示 ADD BEGIN #*}-->
        <tr>
            <th>商品登録日</th>
            <td>
                <!--{$arrDetail.create_date|sfDispDBDate|h}-->
            </td>
        </tr>
        <!--{*# 商品登録日表示 ADD END #*}-->
        <tr>
            <th>商品カテゴリ<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.category_id}--></span>
                <table class="layout">
                    <tr>
                        <td>
                            <select name="category_id[]" id="category_id" style="<!--{if $arrErr.category_id != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}--> height: 120px; min-width: 200px;" onchange="" size="10" multiple>
                            </select>
                        </td>
                        <td style="padding: 15px;">
                            <a class="btn-normal" href="javascript:;" name="on_select" onclick="fnMoveSelect('category_id_unselect','category_id'); return false;">&nbsp;&nbsp;&lt;-&nbsp;登録&nbsp;&nbsp;</a><br /><br />
                            <a class="btn-normal" href="javascript:;" name="un_select" onclick="fnMoveSelect('category_id','category_id_unselect'); return false;">&nbsp;&nbsp;削除&nbsp;-&gt;&nbsp;&nbsp;</a>
                        </td>
                        <td>
                            <select name="category_id_unselect[]" id="category_id_unselect" onchange="" size="10" style="height: 120px; min-width: 200px;" multiple>
                                <!--{html_options values=$arrCatVal output=$arrCatOut selected=$arrForm.category_id}-->
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>公開・非公開<span class="attention"> *</span></th>
            <td>
                <!--{html_radios name="status" options=$arrDISP selected=$arrForm.status separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
        <tr>
            <th>商品ステータス</th>
            <td>
                <!--{html_checkboxes name="product_status" options=$arrSTATUS selected=$arrForm.product_status separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
        <!--{*# 商品ステータス2、ステータス3を追加 ADD BEGIN #*}-->
        <tr>
            <th>商品ステータス2</th>
            <td>
                <!--{html_checkboxes name="product_status2" options=$arrSTATUS2 selected=$arrForm.product_status2 separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>

        <tr>
            <th>商品ステータス3</th>
            <td>
                <!--{html_checkboxes name="product_status3" options=$arrSTATUS3 selected=$arrForm.product_status3 separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
        <!--{*# 商品ステータス2、ステータス3を追加 ADD END #*}-->
        
        <!--{*# 商品支払方法指定 ADD BEGIN #*}-->
        <!--{if $smarty.const.USE_PRODUCT_PAYMENT === true}-->
        <tr>
            <th>支払方法</th>
            <td>
                <!--{html_checkboxes name="payment_id" options=$arrPAYMENT selected=$arrForm.payment_id separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
        <!--{/if}-->
        <!--{*# 商品支払方法指定 ADD END #*}-->
        <!--{*# 商品配送方法指定 ADD BEGIN #*}-->
        <!--{if $smarty.const.USE_PRODUCT_DELIV === true}-->
        <tr>
            <th>配送方法</th>
            <td>
                <!--{html_checkboxes name="deliv_id" options=$arrDELIV selected=$arrForm.deliv_id separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
        <!--{/if}-->
        <!--{*# 商品配送方法指定 ADD END #*}-->
        <!--{if $arrForm.has_product_class == false}-->
        <tr>
            <th>商品種別<span class="attention"> *</span></th>
            <td>
                <!--{html_radios name="product_type_id" options=$arrProductType selected=$arrForm.product_type_id separator='&nbsp;&nbsp;'}-->
            </td>
        </tr>
<!--{if $smarty.const.USE_DOWNLOAD_PRODUCT === true}-->
        <tr>
            <th>ダウンロード商品ファイル名<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.down_filename}--></span>
                <input type="text" name="down_filename" value="<!--{$arrForm.down_filename|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.down_filename != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}--><!--{/if}-->" size="60" class="box60" />
                <span class="red"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="down_file"}-->
            <th>ダウンロード商品用<br />ファイルアップロード<span class="attention"> *</span></th>
            <td>
                <a name="<!--{$key}-->"></a>
                <span class="attention"><!--{$arrErr[$key]}--><!--{$arrErr.down_realfilename}--></span>
                    <!--{if $arrForm.down_realfilename != ""}-->
                        <!--{$arrForm.down_realfilename|h}--><input type="hidden" name="down_realfilename" value="<!--{$arrForm.down_realfilename|h}-->">
                        <a href="" onclick="selectAll('category_id'); fnModeSubmit('delete_down', 'down_key', '<!--{$key}-->'); return false;">[ファイルの取り消し]</a><br>
                    <!--{/if}-->
                    <input type="file" name="down_file" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); fnModeSubmit('upload_down', 'down_key', '<!--{$key}-->'); return false;">アップロード</a><br />登録可能拡張子：<!--{$smarty.const.DOWNLOAD_EXTENSION}-->　(パラメーター DOWNLOAD_EXTENSION)
            </td>
        </tr>
<!--{/if}-->
        <tr>
            <th>商品コード<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.product_code}--></span>
                <input type="text" name="product_code" value="<!--{$arrForm.product_code|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{if $arrErr.product_code != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" size="60" class="box60" />
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th><!--{$smarty.const.NORMAL_PRICE_TITLE}--></th>
            <td>
                <span class="attention"><!--{$arrErr.price01}--></span>
                <input type="text" name="price01" value="<!--{$arrForm.price01|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.price01 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>円
                <span class="attention"> (半角数字で入力)</span>
            </td>
        </tr>
        <tr>
            <th><!--{$smarty.const.SALE_PRICE_TITLE}--><span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.price02}--></span>
                <input type="text" name="price02" value="<!--{$arrForm.price02|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.price02 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>円
                <span class="attention"> (半角数字で入力)</span>
            </td>
        </tr>
        <tr>
            <th>在庫数<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.stock}--></span>
                <input type="text" name="stock" value="<!--{$arrForm.stock|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.AMOUNT_LEN}-->" style="<!--{if $arrErr.stock != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>
                <input type="checkbox" name="stock_unlimited" value="1" <!--{if $arrForm.stock_unlimited == "1"}-->checked<!--{/if}--> onclick="fnCheckStockLimit('<!--{$smarty.const.DISABLED_RGB}-->');"/>無制限
            </td>
        </tr>
        <!--{/if}-->

        <!--{*# 商品非課税指定 ADD BEGIN #*}-->
        <tr>
            <th>非課税</th>
            <td>
                <span class="attention"><!--{$arrErr.taxfree}--></span>
                <input type="checkbox" name="taxfree" value="1" <!--{if $arrForm.taxfree == "1"}-->checked<!--{/if}--> />
            </td>
        </tr>
        <!--{*# 商品非課税指定 ADD END #*}-->

        <tr>
            <th>商品送料</th>
            <td>
                <span class="attention"><!--{$arrErr.deliv_fee}--></span>
                <input type="text" name="deliv_fee" value="<!--{$arrForm.deliv_fee|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PRICE_LEN}-->" style="<!--{if $arrErr.deliv_fee != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>円
                <span class="attention"> (半角数字で入力)</span>
                <!--{if $smarty.const.OPTION_PRODUCT_DELIV_FEE != 1}--><br /><span class="attention">※現在無効です</span> (パラメーター OPTION_PRODUCT_DELIV_FEE)<!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>ポイント付与率<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.point_rate}--></span>
                <input type="text" name="point_rate" value="<!--{$arrForm.point_rate|default:$arrForm.arrInfo.point_rate|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.PERCENTAGE_LEN}-->" style="<!--{if $arrErr.point_rate != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>％
                <span class="attention"> (半角数字で入力)</span>
            </td>
        </tr>
        <!--{*# 配送ランク ADD BEGIN #*}-->
        <!--{if $smarty.const.USE_DELIV_RANK === true}-->
        <tr>
            <th>配送ランク<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.deliv_rank}--></span>
                <select name="deliv_rank" style="<!--{$arrErr.deliv_rank|sfGetErrorColor}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrDELIV_RANK selected=$arrForm.deliv_rank}-->
                </select>
            </td>
        </tr>
        <!--{/if}-->
        <!--{*# 配送ランク ADD END #*}-->
        <tr>
            <th>発送日目安</th>
            <td>
                <span class="attention"><!--{$arrErr.deliv_date_id}--></span>
                <select name="deliv_date_id" style="<!--{$arrErr.deliv_date_id|sfGetErrorColor}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrDELIVERYDATE selected=$arrForm.deliv_date_id}-->
                </select>
            </td>
        </tr>
        <tr>
            <th>販売制限数</th>
            <td>
                <span class="attention"><!--{$arrErr.sale_limit}--></span>
                <input type="text" name="sale_limit" value="<!--{$arrForm.sale_limit|h}-->" size="6" class="box6" maxlength="<!--{$smarty.const.AMOUNT_LEN}-->" style="<!--{if $arrErr.sale_limit != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>
                <span class="attention"> (半角数字で入力)</span>
            </td>
        </tr>
        <!--{*## その他商品項目カスタマイズ MDF BEGIN ##*}-->
        <!--{*
        <tr>
            <th>メーカー</th>
            <td>
                <span class="attention"><!--{$arrErr.maker_id}--></span>
                <select name="maker_id" style="<!--{$arrErr.maker_id|sfGetErrorColor}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrMaker selected=$arrForm.maker_id}-->
                </select>
            </td>
        </tr>
        <tr>
            <th>メーカーURL</th>
            <td>
                <span class="attention"><!--{$arrErr.comment1}--></span>
                <input type="text" name="comment1" value="<!--{$arrForm.comment1|h}-->" maxlength="<!--{$smarty.const.URL_LEN}-->" size="60" class="box60" style="<!--{$arrErr.comment1|sfGetErrorColor}-->" />
                <span class="attention"> (上限<!--{$smarty.const.URL_LEN}-->文字)</span>
            </td>
        </tr>
        *}-->
        <tr>
            <th>メーカー</th>
            <td>
                <span class="attention"><!--{$arrErr.comment1}--></span>
                <input type="text" name="comment1" value="<!--{$arrForm.comment1|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.comment1|sfGetErrorColor}-->" />
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>生産国</th>
            <td>
                <span class="attention"><!--{$arrErr.comment2}--></span>
                <input type="text" name="comment2" value="<!--{$arrForm.comment2|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.comment2|sfGetErrorColor}-->" />
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>TAISコード</th>
            <td>
                <span class="attention"><!--{$arrErr.comment4}--></span>
                <input type="text" name="comment4" value="<!--{$arrForm.comment4|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.comment4|sfGetErrorColor}-->" />
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <!--{*## その他商品項目カスタマイズ MDF END ##*}-->
        
        <tr>
            <th>検索ワード<br />※複数の場合は、カンマ( , )区切りで入力して下さい</th>
            <td>
                <span class="attention"><!--{$arrErr.comment3}--></span>
                <textarea name="comment3" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.comment3|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm.comment3|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        
        <!--{*## SEO管理 ADD BEGIN ##*}-->  
        <!--{if $smarty.const.USE_SEO === true}-->
        <tr>
            <th>ページタイトル</th>
            <td>
                <span class="attention"><!--{$arrErr.title}--></span>
                <input type="text" name="title" value="<!--{$arrForm.title|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.title|sfGetErrorColor}-->" /><span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>H1テキスト</th>
            <td>
                <span class="attention"><!--{$arrErr.h1}--></span>
                <input type="text" name="h1" value="<!--{$arrForm.h1|h}-->" maxlength="<!--{$smarty.const.SMTEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.h1|sfGetErrorColor}-->" /><span class="attention"> (上限<!--{$smarty.const.SMTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>メタタグ:Description</th>
            <td>
                <span class="attention"><!--{$arrErr.description}--></span>
                <input type="text" name="description" value="<!--{$arrForm.description|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.description|sfGetErrorColor}-->" /><span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>メタタグ:Keywords</th>
            <td>
                <span class="attention"><!--{$arrErr.keyword}--></span>
                <input type="text" name="keyword" value="<!--{$arrForm.keyword|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" size="60" class="box60" style="<!--{$arrErr.keyword|sfGetErrorColor}-->" /><span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <!--{/if}-->
        <!--{*## SEO管理 ADD END ##*}-->
        <tr>
            <th>備考欄(SHOP専用)</th>
            <td>
                <span class="attention"><!--{$arrErr.note}--></span>
                <textarea name="note" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr.note|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm.note|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>一覧-メインコメント<span class="attention"> *</span></th>
            <td>
                <span class="attention"><!--{$arrErr.main_list_comment}--></span>
                <textarea name="main_list_comment" maxlength="<!--{$smarty.const.MTEXT_LEN}-->" style="<!--{if $arrErr.main_list_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" cols="60" rows="8" class="area60"><!--{"\n"}--><!--{$arrForm.main_list_comment|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.MTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <!--{*## その他商品項目カスタマイズ MDF BEGIN ##*}-->
        <tr>
            <th>詳細-メインコメント1<span class="attention">(タグ許可)*</span></th>
            <td>
                <span class="attention"><!--{$arrErr.main_comment}--></span>
                <textarea name="main_comment" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{if $arrErr.main_comment != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" cols="60" rows="8" class="area60"><!--{"\n"}--><!--{$arrForm.main_comment|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>詳細-メインコメント2<span class="attention">(タグ許可)*</span></th>
            <td>
                <span class="attention"><!--{$arrErr.comment5}--></span>
                <textarea name="comment5" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{if $arrErr.comment5 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" cols="60" rows="8" class="area60"><!--{"\n"}--><!--{$arrForm.comment5|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>詳細-メインコメント3<span class="attention">(タグ許可)*</span></th>
            <td>
                <span class="attention"><!--{$arrErr.comment6}--></span>
                <textarea name="comment6" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{if $arrErr.comment6 != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->" cols="60" rows="8" class="area60"><!--{"\n"}--><!--{$arrForm.comment6|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <!--{*## その他商品項目カスタマイズ MDF END ##*}-->
        <tr>
            <!--{assign var=key value="main_list_image"}-->
            <th>一覧-メイン画像<br />[<!--{$smarty.const.SMALL_IMAGE_WIDTH}-->×<!--{$smarty.const.SMALL_IMAGE_HEIGHT}-->]</th>
            <td>
                <a name="<!--{$key}-->"></a>
                <a name="main_image"></a>
                <a name="main_large_image"></a>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); fnModeSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="main_list_image" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); fnModeSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="main_image"}-->
            <th>詳細-メイン画像<br />[<!--{$smarty.const.NORMAL_IMAGE_WIDTH}-->×<!--{$smarty.const.NORMAL_IMAGE_HEIGHT}-->]</th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); fnModeSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="main_image" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); fnModeSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="main_large_image"}-->
            <th>詳細-メイン拡大画像<br />[<!--{$smarty.const.LARGE_IMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_IMAGE_HEIGHT}-->]</th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); fnModeSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="<!--{$key}-->" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); fnModeSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
    </table>

    <!--{* オペビルダー用 *}-->
    <!--{if "sfViewAdminOpe"|function_exists === TRUE}-->
    <!--{include file=`$smarty.const.MODULE_REALDIR`mdl_opebuilder/admin_ope_view.tpl}-->
    <!--{/if}-->

    <div class="btn">
        <a class="btn-normal" href="javascript:;" onclick="selectAll('category_id'); lfDispSwitch('sub_detail'); return false;"><span>サブ情報表示/非表示</span></a>
    </div>

    <!--{if $arrForm.sub_find == true}-->
    <div id="sub_detail" style="">
    <!--{else}-->
    <div id="sub_detail" style="display:none">
    <!--{/if}-->
    <h2>サブ情報</h2>
    <table class="form">
        <!--{section name=cnt loop=$smarty.const.PRODUCTSUB_MAX}-->
        <!--▼商品<!--{$smarty.section.cnt.iteration}-->-->
        <tr>
            <th>詳細-サブタイトル(<!--{$smarty.section.cnt.iteration}-->)</th>
            <!--{assign var=key value="sub_title`$smarty.section.cnt.iteration`"}-->
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="text" name="sub_title<!--{$smarty.section.cnt.iteration}-->" value="<!--{$arrForm[$key]|h}-->" size="60" class="box60" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/>
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <th>詳細-サブコメント(<!--{$smarty.section.cnt.iteration}-->)<span class="attention">(タグ許可)</span></th>
            <!--{assign var=key value="sub_comment`$smarty.section.cnt.iteration`"}-->
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <textarea name="sub_comment<!--{$smarty.section.cnt.iteration}-->" cols="60" rows="8" class="area60" maxlength="<!--{$smarty.const.LLTEXT_LEN}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key]|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="sub_image`$smarty.section.cnt.iteration`"}-->
            <th>詳細-サブ画像(<!--{$smarty.section.cnt.iteration}-->)<br />[<!--{$smarty.const.NORMAL_SUBIMAGE_WIDTH}-->×<!--{$smarty.const.NORMAL_SUBIMAGE_HEIGHT}-->]</th>
            <td>
                <a name="<!--{$key}-->"></a>
                <!--{assign var=largekey value="sub_large_image`$smarty.section.cnt.iteration`"}-->
                <a name="<!--{$largekey}-->"></a>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); fnModeSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="<!--{$key}-->" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/>
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); fnModeSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <tr>
            <!--{assign var=key value="sub_large_image`$smarty.section.cnt.iteration`"}-->
            <th>詳細-サブ拡大画像(<!--{$smarty.section.cnt.iteration}-->)<br />[<!--{$smarty.const.LARGE_SUBIMAGE_WIDTH}-->×<!--{$smarty.const.LARGE_SUBIMAGE_HEIGHT}-->]</th>
            <td>
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{if $arrForm.arrFile[$key].filepath != ""}-->
                <img src="<!--{$arrForm.arrFile[$key].filepath}-->" alt="<!--{$arrForm.name|h}-->" />　<a href="" onclick="selectAll('category_id'); fnModeSubmit('delete_image', 'image_key', '<!--{$key}-->'); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <input type="file" name="<!--{$key}-->" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"/>
                <a class="btn-normal" href="javascript:;" name="btn" onclick="selectAll('category_id'); fnModeSubmit('upload_image', 'image_key', '<!--{$key}-->'); return false;">アップロード</a>
            </td>
        </tr>
        <!--▲商品<!--{$smarty.section.cnt.iteration}-->-->
        <!--{/section}-->
    </table>
    </div>

    <div class="btn">
        <a class="btn-normal" href="javascript:;" onclick="selectAll('category_id'); lfDispSwitch('recommend_select'); return false;"><span>関連商品表示/非表示</span></a>
    </div>

    <!--{if $smarty.const.OPTION_RECOMMEND == 1}-->
    <!--{if count($arrRecommend) > 0}-->
    <div id="recommend_select" style="">
    <!--{else}-->
    <div id="recommend_select" style="display:none">
    <!--{/if}-->
    <h2>関連商品</h2>
    <table class="form">
        <!--▼関連商品-->
        <!--{section name=cnt loop=$smarty.const.RECOMMEND_PRODUCT_MAX}-->
        <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
        <tr>
            <!--{assign var=key value="recommend_id`$smarty.section.cnt.iteration`"}-->
            <!--{assign var=anckey value="recommend_no`$smarty.section.cnt.iteration`"}-->
            <th>関連商品(<!--{$smarty.section.cnt.iteration}-->)<br />
                <!--{if $arrRecommend[$recommend_no].product_id}-->
                    <img src="<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=<!--{$arrRecommend[$recommend_no].main_list_image|sfNoImageMainList|h}-->&width=65&height=65" alt="<!--{$arrRecommend[$recommend_no].name|h}-->" />
                <!--{/if}-->
            </th>
            <td>
                <a name="<!--{$anckey}-->"></a>
                <input type="hidden" name="<!--{$key}-->" value="<!--{$arrRecommend[$recommend_no].product_id|h}-->" />
                <a class="btn-normal" href="javascript:;" name="change" onclick="selectAll('category_id'); win03('./product_select.php?no=<!--{$smarty.section.cnt.iteration}-->', 'search', '615', '500'); return false;">変更</a>
                <!--{assign var=key value="recommend_delete`$smarty.section.cnt.iteration`"}-->
                <input type="checkbox" name="<!--{$key}-->" value="1" />削除<br />
               <!--{assign var=key value="recommend_comment`$smarty.section.cnt.iteration`"}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                商品コード:<!--{$arrRecommend[$recommend_no].product_code_min}--><br />
                商品名:<!--{$arrRecommend[$recommend_no].name|h}--><br />
                <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{"\n"}--><!--{$arrRecommend[$recommend_no].comment|h}--></textarea><br />
                <span class="attention"> (上限<!--{$smarty.const.LTEXT_LEN}-->文字)</span>
            </td>
        </tr>
        <!--{/section}-->
        <!--▲関連商品-->
    </table>
    </div>
    <!--{/if}-->

    <div class="btn-area">
        <!--{if count($arrSearchHidden) > 0}-->
        <!--▼検索結果へ戻る-->
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_PRODUCTS_URLPATH}-->'); fnModeSubmit('search','',''); return false;"><span class="btn-prev">検索画面に戻る</span></a></li>
        <!--▲検索結果へ戻る-->
        <!--{/if}-->
            <li><a class="btn-action" href="javascript:;" onclick="selectAll('category_id'); document.form1.submit(); return false;"><span class="btn-next">確認ページへ</span></a></li>
        </ul>
    </div>
</div>
</form>
