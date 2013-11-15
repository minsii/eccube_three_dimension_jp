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

<script type="text/javascript" src="<!--{$TPL_URLPATH}-->js/breadcrumbs.js"></script>
<script type="text/javascript">//<![CDATA[
    $(function() {
        $('h2').breadcrumbs({
            'bread_crumbs': <!--{$tpl_bread_crumbs}-->
        });

<!--{*## カテゴリ追加情報  ADD BEGIN ##*}-->
<!--{if $smarty.const.USE_CATEGORY_INFO === true}-->
        fckeditorCreate();
<!--{/if}-->
<!--{*## カテゴリ追加情報  ADD END ##*}-->

    });

<!--{*## カテゴリ追加情報  ADD BEGIN ##*}-->
<!--{if $smarty.const.USE_CATEGORY_INFO === true}-->
function fckeditorCreate(){ 
    var oFCKeditor = new FCKeditor() ;
    oFCKeditor.BasePath    = '<!--{$TPL_URLPATH}-->js/fckeditor/' ;
    oFCKeditor.Height='320';
    oFCKeditor.InstanceName = 'category_info';
    oFCKeditor.ToolbarSet = 'ECCUBEcat';
    oFCKeditor.ReplaceTextarea() ;
/*
    var oFCKeditor2 = new FCKeditor() ;
    oFCKeditor2.BasePath    = '<!--{$smarty.const.TPL_DIR}-->js/fckeditor/' ;
    oFCKeditor2.Height='320';
    oFCKeditor2.InstanceName = 'category_info_bottom';
    oFCKeditor2.ToolbarSet = 'ECCUBEcat';
    oFCKeditor2.ReplaceTextarea() ;
*/
}
<!--{/if}-->
<!--{*## カテゴリ追加情報  ADD END ##*}-->


<!--{*## カテゴリお勧め商品 ADD BEGIN ##*}-->
<!--{if $smarty.const.CATEGORY_RECOMMEND_PRODUCT_MAX > 0}-->
function lfDeleteRecommend(key){
    var fm = document.form1;
    var mode_bk = fm.mode.value;
    fm.mode.value = "recommend_select";
    fm[key].value = "";
    fm.submit();
    fm.mode.value = mode_bk;
}
<!--{/if}-->
<!--{*## カテゴリお勧め商品 ADD END ##*}-->
//]]>
</script>
<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit" />
<input type="hidden" name="parent_category_id" value="<!--{$arrForm.parent_category_id|h}-->">
<input type="hidden" name="category_id" value="<!--{$arrForm.category_id|h}-->">
<input type="hidden" name="keySet" value="">
<div id="products" class="contents-main">
    <div class="btn">
        <a class="btn-normal" href="javascript:;" onclick="fnModeSubmit('csv','',''); return false;">CSV ダウンロード</a>
        <a class="btn-normal" href='../contents/csv.php?tpl_subno_csv=category'>CSV 出力項目設定</a>
    </div>

    <!--{* ▼画面左 *}-->
    <div id="products-category-left">
        <a href="?"><img src="<!--{$TPL_URLPATH}-->img/contents/folder_close.gif" alt="フォルダ">&nbsp;ホーム</a><br />
        <!--{section name=cnt loop=$arrTree}-->
            <!--{assign var=level value="`$arrTree[cnt].level`}-->

            <!--{* 上の階層表示の時にdivを閉じる *}-->
            <!--{assign var=close_cnt value="`$before_level-$level+1`"}-->
            <!--{if $close_cnt > 0}-->
                <!--{section name=n loop=$close_cnt}--></div><!--{/section}-->
            <!--{/if}-->

            <!--{* スペース繰り返し *}-->
            <!--{section name=n loop=$level}-->　　<!--{/section}-->

            <!--{* カテゴリ名表示 *}-->
            <!--{assign var=disp_name value="`$arrTree[cnt].category_id`.`$arrTree[cnt].category_name`"}-->
            <!--{if $arrTree[cnt].level != $smarty.const.LEVEL_MAX}-->
                <a href="?" onclick="fnModeSubmit('tree', 'parent_category_id', <!--{$arrTree[cnt].category_id}-->); return false;">
                <!--{if $arrForm.parent_category_id == $arrTree[cnt].category_id}-->
                    <img src="<!--{$TPL_URLPATH}-->img/contents/folder_open.gif" alt="フォルダ">
                <!--{else}-->
                    <img src="<!--{$TPL_URLPATH}-->img/contents/folder_close.gif" alt="フォルダ">
                <!--{/if}-->
                <!--{$disp_name|sfCutString:10:false|h}--></a><br />
            <!--{else}-->
                <img src="<!--{$TPL_URLPATH}-->img/contents/folder_close.gif" alt="フォルダ">
                <!--{$disp_name|sfCutString:10:false|h}--></a><br />
            <!--{/if}-->

            <!--{if $arrTree[cnt].display == true}-->
                <div id="f<!--{$arrTree[cnt].category_id}-->">
            <!--{else}-->
                <div id="f<!--{$arrTree[cnt].category_id}-->" style="display:none">
            <!--{/if}-->

            <!--{if $smarty.section.cnt.last}-->
                <!--{section name=n loop=$level}--></div><!--{/section}-->
            <!--{/if}-->

            <!--{assign var=before_level value="`$arrTree[cnt].level`}-->
        <!--{/section}-->
    </div>
    <!--{* ▲画面左 *}-->

    <!--{* ▼画面右 *}-->
    <div id="products-category-right">


        <div class="now_dir">
                <!--{if $arrErr.category_name}-->
                <span class="attention"><!--{$arrErr.category_name}--></span>
                <!--{/if}-->
                カテゴリ名　<input type="text" name="category_name" value="<!--{$arrForm.category_name|h}-->" size="30" class="box30" maxlength="<!--{$smarty.const.STEXT_LEN}-->" /><span class="attention">&nbsp;（上限<!--{$smarty.const.STEXT_LEN}-->文字）</span>
                
                <!--{*## カテゴリ一覧でカゴ表示管理 ADD BEGIN ##*}-->
                &nbsp;&nbsp;<input type="checkbox" name="hide_list_cart" value="1" <!--{if $arrForm.hide_list_cart == 1}-->checked<!--{/if}-->/>カゴ非表示
                <!--{*## カテゴリ一覧でカゴ表示管理 ADD END ##*}-->
                <!--{*## カテゴリ追加情報  ADD BEGIN ##*}-->
                <br /><br />
                <!--{if $smarty.const.USE_CATEGORY_INFO === true}-->
                  カテゴリ説明1
                  <textarea name="category_info" id="category_info" cols="40" rows="10"><!--{$arrForm.category_info|escape}--></textarea><br />
                  <span class="attention">&nbsp;(上限<!--{$smarty.const.LLTEXT_LEN}-->文字)</span><br /><br />
                <!--{/if}-->
                <!--{*## カテゴリ追加情報  ADD END ##*}-->

                <!--{*## カテゴリお勧め商品 ADD BEGIN ##*}-->
                <input type="hidden" name="select_recommend_no" value="" />
                <!--{if $smarty.const.CATEGORY_RECOMMEND_PRODUCT_MAX > 0}-->
                <input type="hidden" name="anchor_key" value="">
                おすすめ商品
                <table width="100%">
                  <!--{section name=cnt loop=$smarty.const.CATEGORY_RECOMMEND_PRODUCT_MAX}-->
                  <tr>
                    <!--{assign var=recommend_no value="`$smarty.section.cnt.iteration`"}-->
                    <!--{assign var=key value="recommend_id`$smarty.section.cnt.iteration`"}-->
                    <!--{assign var=anckey value="recommend_no`$smarty.section.cnt.iteration`"}-->
                    <!--{*
                    <td align="center" width="10">
                      (<!--{$smarty.section.cnt.iteration}-->)
                    </td>
                    *}-->
                    <td width="65">
                      <!--{if $arrRecommend[$recommend_no].main_list_image != ""}--><!--{assign var=image_path value="`$smarty.const.IMAGE_SAVE_URLPATH``$arrRecommend[$recommend_no].main_list_image`"}-->
                      <img src="<!--{$image_path|escape}-->" alt="<!--{$arrRecommend[$recommend_no].name|escape}-->" width="65" height="65">
                      <!--{/if}-->
                    </td>
                    <td>
                     <a name="<!--{$anckey}-->"></a>
                     <input type="hidden" name="<!--{$key}-->" value="<!--{$arrRecommend[$recommend_no].product_id|escape}-->">
                     <input type="button" name="change" value="商品変更" onclick="win03('./product_select.php?no=<!--{$smarty.section.cnt.iteration}-->', 'search', '615', '500'); return false;" >
                     <input type="button" name="change" value="削除" onclick="lfDeleteRecommend('<!--{$key}-->');return false;" ><br />
                     商品コード：<!--{$arrRecommend[$recommend_no].product_code_min}--><br />
                     商品名：<!--{$arrRecommend[$recommend_no].name|escape}--><br />
                     <!--{assign var=key value="recommend_comment`$smarty.section.cnt.iteration`"}-->
                     <textarea name="<!--{$key}-->" cols="60" rows="8" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" ><!--{$arrRecommend[$recommend_no].comment|h}--></textarea><br />
                     <span class="attention"> (上限<!--{$smarty.const.LTEXT_LEN}-->文字)</span>
                    </td>
                  </tr>
                  <!--{/section}-->
                </table>
                <!--{/if}-->
                <!--{*## カテゴリお勧め商品 ADD END ##*}-->

                <!--{*## SEO管理 ADD BEGIN ##*}-->
                <!--{if $smarty.const.USE_SEO === true}-->
                <!--{if $arrErr.title}-->
                <span class="attention"><!--{$arrErr.title}--></span>
                <!--{/if}-->
                ページタイトル<input type="text" name="title" value="<!--{$arrForm.title|h}-->" size="30" class="box30" maxlength="<!--{$smarty.const.STEXT_LEN}-->" />
                <span class="attention">&nbsp;（上限<!--{$smarty.const.STEXT_LEN}-->文字）</span><br /><br />


                <!--{if $arrErr.h1}-->
                <span class="attention"><!--{$arrErr.h1}--></span>
                <!--{/if}-->
                H1テキスト<input type="text" name="h1" value="<!--{$arrForm.h1|h}-->" size="30" class="box30" maxlength="<!--{$smarty.const.SMTEXT_LEN}-->" />
                <span class="attention">&nbsp;（上限<!--{$smarty.const.SMTEXT_LEN}-->文字）</span><br /><br />

                <!--{if $arrErr.description}-->
                <span class="attention"><!--{$arrErr.description}--></span>
                <!--{/if}-->
                メタタグ:Description<input type="text" name="description" value="<!--{$arrForm.description|h}-->" size="30" class="box30" maxlength="<!--{$smarty.const.STEXT_LEN}-->" />
                <span class="attention">&nbsp;（上限<!--{$smarty.const.STEXT_LEN}-->文字）</span><br /><br />

                <!--{if $arrErr.keyword}-->
                <span class="attention"><!--{$arrErr.keyword}--></span>
                <!--{/if}-->
                メタタグ:Keywords<input type="text" name="keyword" value="<!--{$arrForm.keyword|h}-->" size="30" class="box30" maxlength="<!--{$smarty.const.STEXT_LEN}-->" />
                <span class="attention">&nbsp;（上限<!--{$smarty.const.STEXT_LEN}-->文字）</span><br /><br />

                <!--{/if}-->
                <!--{*## SEO管理 ADD END ##*}-->


                <!--{*## CATEGORY 情報 ## ADD BEGIN*}-->
<!--{*
                <!--{if $smarty.const.USE_CATEGORY_INFO === true}-->
                <!--{foreach key=name item=value from=$arrHiddenForm}-->
                <input type="hidden" name="<!--{$name}-->" value="<!--{$value}-->">
                <!--{/foreach}-->
                カテゴリ画像
                <!--{assign var=key value="category_main_image"}-->
                <input type="hidden" name="image_key" value="<!--{$key}-->">
                <!--{if $arrFile[$key].filepath != ""}-->
                <br /><img src="<!--{$arrFile[$key].filepath}-->" width="300" alt="<!--{$arrForm.name|escape}-->" />　<a href="" onclick="fnModeSubmit('delete_image', '', ''); return false;">[画像の取り消し]</a><br />
                <!--{/if}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <input type="file" name="<!--{$key}-->" size="40" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                <input type="button" name="btn" onclick="fnModeSubmit('upload_image', '', '')" value="アップロード"><br /><br />

                カテゴリ画像ALT
                <span class="attention"><!--{$arrErr.category_main_image_alt}--></span>
                <input type="text" name="category_main_image_alt" id="category_main_image_alt" class="box30" value="<!--{$arrForm.category_main_image_alt|escape}-->">
                <span class="attention"> (上限<!--{$smarty.const.LTEXT_LEN}-->文字)</span>
                <!--{/if}-->
*}-->
                <!--{*## CATEGORY 情報 ## ADD END*}-->
                <br /><br /><a class="btn-normal" href="javascript:;" onclick="fnModeSubmit('edit','',''); return false;"><span class="btn-next">登録</span></a>
        </div>

        <h2><!--{* jQuery で挿入される *}--></h2>
        <!--{if count($arrList) > 0}-->

        <table class="list" id="categoryTable">
            <col width="5%" />
            <col width="60%" />
            <col width="10%" />
            <col width="10%" />
            <col width="25%" />
            <tr class="nodrop nodrag">
                <th>ID</th>
                <th>カテゴリ名</th>
                <th class="edit">編集</th>
                <th class="delete">削除</th>
                <th>移動</th>
            </tr>

            <!--{section name=cnt loop=$arrList}-->
            <tr id="<!--{$arrList[cnt].category_id}-->" style="background:<!--{if $arrForm.category_id != $arrList[cnt].category_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->;" align="left">
                <td class="center"><!--{$arrList[cnt].category_id}--></td>
                <td>
                <!--{if $arrList[cnt].level != $smarty.const.LEVEL_MAX}-->
                    <a href="?" onclick="fnModeSubmit('tree', 'parent_category_id', <!--{$arrList[cnt].category_id}-->); return false"><!--{$arrList[cnt].category_name|h}--></a>
                <!--{else}-->
                    <!--{$arrList[cnt].category_name|h}-->
                <!--{/if}-->
                </td>
                <td class="center">
                    <!--{if $arrForm.category_id != $arrList[cnt].category_id}-->
                    <a href="?" onclick="fnModeSubmit('pre_edit', 'category_id', <!--{$arrList[cnt].category_id}-->); return false;">編集</a>
                    <!--{else}-->
                    編集中
                    <!--{/if}-->
                </td>
                <td class="center">
                    <a href="?" onclick="fnModeSubmit('delete', 'category_id', <!--{$arrList[cnt].category_id}-->); return false;">削除</a>
                </td>
                <td class="center">
                <!--{* 移動 *}-->
                <!--{if $smarty.section.cnt.iteration != 1}-->
                <a href="?" onclick="fnModeSubmit('up','category_id', <!--{$arrList[cnt].category_id}-->); return false;">上へ</a>
                <!--{/if}-->
                <!--{if $smarty.section.cnt.iteration != $smarty.section.cnt.last}-->
                <a href="?" onclick="fnModeSubmit('down','category_id', <!--{$arrList[cnt].category_id}-->); return false;">下へ</a>
                <!--{/if}-->
                </td>

            </tr>
            <!--{/section}-->
        </table>
        <!--{else}-->
        <p>この階層には、カテゴリが登録されていません。</p>
        <!--{/if}-->
    </div>
    <!--{* ▲画面右 *}-->

</div>
</form>
