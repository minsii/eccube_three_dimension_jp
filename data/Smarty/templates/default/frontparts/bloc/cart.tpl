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
<!--▼カゴ-->
<section class="cart_box">
	<h2>お買い物カゴ</h2>
    <dl>
    	<dt><strong>合計</strong>（税込）</dt>
    	<dd class="price">￥<!--{$arrCartList.0.ProductsTotal|number_format|default:0}--></dd>
    </dl>
    <!--{if $arrCartList.0.TotalQuantity > 0}-->
    <dl>
    	<dt>商品名</dt>
    	<dd>数 販売価格</dd>
    </dl>
    <dl>
      <!--{assign var=arrProducts value=$arrCartList.0.products}-->
      <!--{section name=cnt loop=$arrProducts}-->
    	<dt><a href="#"><!--{$arrProducts[cnt].productsClass.name|h}--></a></dt>
    	<dd><!--{$arrProducts[cnt].quantity|h}--> ￥<!--{$arrProducts[cnt].productsClass.price02|sfCalcIncTax|number_format}--></dd>
      <!--{/section}-->
    </dl>
    <!--{/if}-->
    <!-- カゴの中に商品がある場合にのみ表示 -->
    <!--{if $arrCartList.0.TotalQuantity > 0 and $arrCartList.0.free_rule > 0}-->
      <!--{if $arrCartList.0.deliv_free > 0}-->
      送料手数料無料まであと<!--{$arrCartList.0.deliv_free|number_format|default:0}-->円（税込）です。
      <!--{else}-->
      <br>現在、送料は「<span class="price">無料</span>」です。
      <!--{/if}-->
    <!--{/if}-->
    <div class="btn"> <a href="<!--{$smarty.const.CART_URLPATH}-->"><img src="<!--{$TPL_URLPATH}-->img/page/bloc/cart_box/btn_kagokakunin.png" width="192" height="41" alt="カゴの確認" /></a> </div>
</section>