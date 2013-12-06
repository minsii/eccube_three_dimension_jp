<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2007 LOCKON CO.,LTD. All Rights Reserved.
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
<!--現在のカゴの中ここから-->
<div class="left_box">
 <div class="left_inner">
  <h2 class="left_ttl"><img src="<!--{$TPL_URLPATH}-->img/side/left_ttl01.gif" width="206" height="45" alt="現在のカゴの中"></h2>
  <div class="left_cont cartarea">
    <p class="item">商品数：<!--{$arrCartList.0.TotalQuantity|number_format|default:0}-->点</p>
    <p>合計：<span class="price"><!--{$arrCartList.0.ProductsTotal|number_format|default:0}-->円</span><br />
    <!-- カゴの中に商品がある場合にのみ表示 -->
    <!--{if $arrCartList.0.TotalQuantity > 0 and $arrCartList.0.free_rule > 0}-->
      <!--{if $arrCartList.0.deliv_free > 0}-->
      送料手数料無料まであと<!--{$arrCartList.0.deliv_free|number_format|default:0}-->円（税込）です。
      <!--{else}-->
      <br>現在、送料は「<span class="price">無料</span>」です。
      <!--{/if}-->
    <!--{/if}-->
    </p>
    <p class="btn">
      <a href="<!--{$smarty.const.CART_URLPATH}-->" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/side/left_cart_btn.gif','button_cartin');" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/side/left_cart_btn.gif','button_cartin');">
        <img src="<!--{$TPL_URLPATH}-->img/side/left_cart_btn.gif" width="142" height="25" alt="カゴの中を見る" border="0" name="button_cartin" id="button_cartin" /></a>
     </p>
  </div>
 </div>
</div>
<!--現在のカゴの中ここまで-->