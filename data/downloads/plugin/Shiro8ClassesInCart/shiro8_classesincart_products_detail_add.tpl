<!--{*
 * Shiro8ProductContents
 * Copyright (C) 2012 Shiro8. All Rights Reserved.
 * http://www.shiro8.net/
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *}-->
<!--PLG:Shiro8ClassesInCart↓-->

<!--{if $tpl_classcat_find1}-->
<div class="classlist">
	<p class="red">※おまとめご購入は「まとめ買い」ボタンをクリックして、規格選択後「購入」にチェックを入れてカゴに入れて下さい。</p>
	<table id="classTable" class="product_style_tbl">
	  <tr>
		  <th>カタログ番号</th>
	    <!--★規格1★-->
	    <!--{if $tpl_classcat_find1}-->
	    <th><!--{$tpl_class_name1}--></th>
	    <!--{/if}-->
	    <!--★規格2★-->
	    <!--{if $tpl_classcat_find2}-->
	    <th><!--{$tpl_class_name2}--></th>
	    <!--{/if}-->
      <th>価格<!--{if $arrProductOther.taxfree == 1}-->(税抜)<!--{else}-->(税込)<!--{/if}--></th>
      <th>在庫</th>
      <th>個数</th>
      <th>購入</th>
	  </tr>
    <!--{section name=cnt loop=$arrProductsClass}-->
	  <tr>
      <!--{assign var=row value=$smarty.section.cnt.iteration}-->
      <!--{assign var=key_quantity value="quantity_`$row`"}-->
      <!--{assign var=key_classcategory1 value="classcategory_id1_`$row`"}-->
      <!--{assign var=key_classcategory2 value="classcategory_id2_`$row`"}-->
      <!--{assign var=find_stock value=$arrProductsClass[cnt].find_stock}-->
      <td><!--{$arrProductsClass[cnt].product_code|h}--></td>
	    <!--★規格1★-->
      <!--{if $tpl_classcat_find1}-->
      <td><!--{$arrProductsClass[cnt].classcategory_name1|h}--></td>
      <!--{/if}-->
      <!--★規格2★-->
      <!--{if $tpl_classcat_find2}-->
      <td><!--{$arrProductsClass[cnt].classcategory_name2|h}--></td>
      <!--{/if}-->
      <td>
        <!--{if $tpl_is_login == true}-->
        <!--{if $arrProductOther.taxfree == 1}-->
          ￥<!--{$arrProductsClass[cnt].price02|number_format}-->
        <!--{else}-->
          ￥<!--{$arrProductsClass[cnt].price02|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
        <!--{/if}-->
        <!--{else}-->
        <span style="color:#FB6C04">会員のみ公開</span>
        <!--{/if}-->
      </td>
      <td><!--{if $find_stock}-->○<!--{else}-->×<!--{/if}--></td>
      <td>
        <!--{if $arrErr[$key_quantity] != ""}-->
        <span class="attention"><!--{$arrErr[$key_quantity]}--></span>
        <!--{/if}-->
        <!--{if $arrErr[$key_classcategory1] != ""}-->
        <span class="attention"><!--{$arrErr[$key_classcategory1]}--></span>
        <!--{/if}-->
        <!--{if $arrErr[$key_classcategory2] != ""}-->
        <span class="attention"><!--{$arrErr[$key_classcategory2]}--></span>
        <!--{/if}-->
        <input type="hidden" name="classcategory_id1_<!--{$row}-->" value="<!--{$arrProductsClass[cnt].classcategory_id1|h}-->" />
        <input type="hidden" name="classcategory_id2_<!--{$row}-->" value="<!--{$arrProductsClass[cnt].classcategory_id2|h}-->" />
        <input type="text" class="box30" name="<!--{$key_quantity}-->" value="<!--{$arrPluginForm[$key_quantity]|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr[$key_quantity]|sfGetErrorColor}-->" <!--{if !$find_stock}-->disabled<!--{/if}-->/>個
      </td>
      <td id="buyFlgArea_rowNum">
      <!--{if $tpl_is_login == true}-->
        <input type="checkbox" name="buyFlg[]" value="<!--{$row}-->" <!--{if !$find_stock}-->disabled<!--{else}--><!--{if $arrCheckedBuyFlg[$row]}-->checked<!--{/if}--><!--{/if}-->/>
      <!--{else}-->
      <a href="<!--{$smarty.const.TOP_URLPATH}-->entry/kiyaku.php">
        <img src="<!--{$TPL_URLPATH}-->img/page/list/productlist/btn_regist.png" width="147" height="34" alt="会員登録" />
      </a>
      <!--{/if}-->
      </td>
    </tr>
    <!--{/section}-->
	</table>
</div>
<!--{else}-->
  <!--{if $tpl_stock_find}-->
  <!--★数量★-->
  <div class="pure-g-r">
  <div class="pure-u-1-4"></div>
  <div class="pure-u-1-4"></div>
  <div class="pure-u-1-4"></div>
  <div class="pure-u-1-4 r-btn">
    <h3>数量：
    <input type="text" class="box60" name="quantity" value="<!--{$arrForm.quantity.value|default:1|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->" />
        <!--{if $arrErr.quantity != ""}-->
            <br /><span class="attention"><!--{$arrErr.quantity}--></span>
        <!--{/if}-->
    </h3>
    <br />
  </div>
  </div>
  <!--{/if}-->
<!--{/if}-->
<!--PLG:Shiro8ClassesInCart↑-->