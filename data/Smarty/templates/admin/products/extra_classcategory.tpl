
<form name="form1" id="form1" method="post" action="">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit" />
<input type="hidden" name="extra_classcategory_id" value="<!--{$tpl_extra_classcategory_id}-->" />
<!--{foreach key=key item=item from=$arrHidden}-->
    <input type="hidden" name="<!--{$key}-->" value="<!--{$item|h}-->" />
<!--{/foreach}-->
<div id="products" class="contents-main">

    <table>
        <tr>
            <th>追加規格名</th>
            <td><!--{$tpl_extra_class_name}--></td>
        </tr>
        <tr>
            <th>分類名<span class="attention"> *</span></th>
            <td>
                <!--{if $arrErr.name}-->
                    <span class="attention"><!--{$arrErr.name}--></span>
                <!--{/if}-->
                <input type="text" name="name" value="<!--{$arrForm.name|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" style="" size="30" class="box30" />
                <span class="attention"> (上限<!--{$smarty.const.STEXT_LEN}-->文字)</span>
            </td>
        </tr>
    </table>
    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
        </ul>
    </div>

    <table class="list">
        <colgroup width="55%">
        <colgroup width="10%">
        <colgroup width="10%">
        <colgroup width="15%">
        <tr>
            <th>分類名</th>
            <th class="edit">編集</th>
            <th class="delete">削除</th>
            <th>移動</th>
        </tr>
        <!--{section name=cnt loop=$arrExtraClassCat}-->
            <tr style="background:<!--{if $tpl_extra_classcategory_id != $arrExtraClassCat[cnt].extra_classcategory_id}-->#ffffff<!--{else}--><!--{$smarty.const.SELECT_RGB}--><!--{/if}-->;">
                <td><!--{* 規格名 *}--><!--{$arrExtraClassCat[cnt].name|h}--></td>
                <td align="center" >
                    <!--{if $tpl_extra_classcategory_id != $arrExtraClassCat[cnt].extra_classcategory_id}-->
                        <a href="?" onclick="fnModeSubmit('pre_edit','extra_classcategory_id', <!--{$arrExtraClassCat[cnt].extra_classcategory_id}-->); return false;">編集</a>
                    <!--{else}-->
                        編集中
                    <!--{/if}-->
                </td>
                <td align="center">
                    <a href="?" onclick="fnModeSubmit('delete','extra_classcategory_id', <!--{$arrExtraClassCat[cnt].extra_classcategory_id}-->); return false;">削除</a>
                </td>
                <td align="center">
                    <!--{if $smarty.section.cnt.iteration != 1}-->
                        <a href="?" onclick="fnModeSubmit('up','extra_classcategory_id', <!--{$arrExtraClassCat[cnt].extra_classcategory_id}-->); return false;">上へ</a>
                    <!--{/if}-->
                    <!--{if $smarty.section.cnt.iteration != $smarty.section.cnt.last}-->
                        <a href="?" onclick="fnModeSubmit('down','extra_classcategory_id', <!--{$arrExtraClassCat[cnt].extra_classcategory_id}-->); return false;">下へ</a>
                    <!--{/if}-->
                </td>
            </tr>
        <!--{/section}-->
    </table>
    <div class="btn">
        <a class="btn-action" href="./extra_class.php"><span class="btn-prev">規格一覧に戻る</span></a>
    </div>
</div>
</form>    
