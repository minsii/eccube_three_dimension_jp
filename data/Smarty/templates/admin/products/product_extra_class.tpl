<h2>商品追加規格登録</h2>
<form name="form1" id="form1" method="post" action="" enctype="multipart/form-data">
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
<input type="hidden" name="product_id" value="<!--{$arrForm.product_id|h}-->" />

<div id="products" class="contents-main">
<!--{if $tpl_complete}-->
<span class="attention">登録が完了致しました。</span>
<!--{/if}-->
    <table>
        <tr>
            <th>商品名</th>
            <td><!--{$tpl_product_name|h}--></td>
        </tr>
        <!--{section name=cnt loop=$smarty.const.MAX_EXTRA_CLASS}-->
        <tr>
            <th>追加規格<!--{$smarty.section.cnt.iteration}--></th>
            <td>
                <!--{assign var=key value="extra_class_id`$smarty.section.cnt.iteration`"}-->
                <!--{if $arrErr[$key]}-->
                <span class="attention"><!--{$arrErr[$key]}--></span>
                <!--{/if}-->

                <select name="<!--{$key}-->">
                    <option value="">選択してください</option>
                    <!--{html_options options=$arrExtraClass selected=$arrForm[$key]}-->
                </select>
            </td>
        </tr>
        <!--{/section}-->
      </table>
    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="fnChangeAction('<!--{$smarty.const.ADMIN_PRODUCTS_URLPATH}-->'); fnModeSubmit('search','',''); return false;" ><span class="btn-prev">検索結果へ戻る</span></a></li>
            <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('form1', 'edit', '', ''); return false;"><span class="btn-next">登録</span></a></li>
        </ul>
    </div>
</div>
</form>
