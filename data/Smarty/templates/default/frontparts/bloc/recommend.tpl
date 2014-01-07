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

<!--{if count($arrBestProducts) > 0}-->
      <div class="new_product_box pure-form pure-form-stacked">
      	<h2><img src="<!--{$TPL_URLPATH}-->img/page/bloc/new_product_box/title.png" width="742" height="30" /><span><a href="<!--{$smarty.const.TOP_URLPATH}-->products/list.php">全商品一覧はこちら</a></span></h2>
          
        <div class="body pure-g-r">
          <!--{foreach from=$arrBestProducts item=arrProduct name="recommend_products"}-->
              <!--{assign var=id value=$arrProduct.product_id}-->
              <!--{assign var=price01_min value=`$arrProduct.price01_min`}-->
              <!--{assign var=price01_max value=`$arrProduct.price01_max`}-->
              <!--{assign var=price02_min value=`$arrProduct.price02_min`}-->
              <!--{assign var=price02_max value=`$arrProduct.price02_max`}-->
              <!--{assign var=point_rate value=`$arrProduct.point_rate`}-->
              <section class="pure-u-1-4">
                <div class="warp">
                  <div class="heightLine">
                  <h3><!--{if $arrProduct.product_code_min == $arrProduct.product_code_max}-->
                                        <!--{$arrProduct.product_code_min|h}-->
                                    <!--{else}-->
                                        <!--{$arrProduct.product_code_min|h}-->～<!--{$arrProduct.product_code_max|h}-->
                                    <!--{/if}--></h3>
                  <p class="icon">
                    <!--▼商品ステータス-->
                    <!--{assign var=ps value=$productStatus[$id]}-->
                    <!--{foreach from=$ps item=status}-->
                      <img src="<!--{$TPL_URLPATH}--><!--{$arrSTATUS_IMAGE[$status]}-->" width="50" height="14" alt="<!--{$arrSTATUS[$status]}-->"/>
                    <!--{/foreach}-->
                    <!--▲商品ステータス-->
                  </p>
                  <div class="img">
                    <a href="<!--{$smarty.const.P_DETAIL_URLPATH|sfGetFormattedUrl:$arrProduct.product_id}-->">
                        <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/<!--{$arrProduct.main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrProduct.name|h}-->" width="165" />
                    </a>
                  </div>
                  <p class="content"><!--{$arrProduct.name|h}--></p>
                  <p class="price">一般価格　
                  <!--{if $arrProduct.taxfree == 1}-->
                  ￥<!--{if $price01_min == $price01_max}-->
                                <!--{$price01_min|number_format}-->
                            <!--{else}-->
                                <!--{$price01_min|number_format}-->～<!--{$price01_max|number_format}-->
                            <!--{/if}-->(税抜)
                  <!--{else}-->
                  ￥<!--{if $price01_min == $price01_max}-->
                                <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                            <!--{else}-->
                                <!--{$price01_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price01_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                            <!--{/if}-->(税込)
                  <!--{/if}-->
                  </p>
                  <!--{if $tpl_is_login}-->
                    <div class="member_price">
                      <p><em>会員特別価格</em></p>
                      <p>
                      <!--{if $arrProduct.taxfree == 1}-->
                      <strong>￥<!--{if $price02_min == $price02_max}-->
                                    <!--{$price02_min|number_format}-->
                                <!--{else}-->
                                    <!--{$price02_min|number_format}-->～<!--{$price02_max|number_format}-->
                                <!--{/if}--></strong><em>(税抜)</em>
                      <!--{else}-->
                      <strong>￥<!--{if $price02_min == $price02_max}-->
                                <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                            <!--{else}-->
                                <!--{$price02_min|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->～<!--{$price02_max|sfCalcIncTax:$arrSiteInfo.tax:$arrSiteInfo.tax_rule|number_format}-->
                            <!--{/if}--></strong><em>(税込)</em>
                      <!--{/if}-->
                      </p>
                      <!--{if $smarty.const.USE_POINT === true}-->
                      <p>ポイント:<!--{if $price02_min|sfPrePoint:$point_rate == $price02_max|sfPrePoint:$point_rate}-->
                              <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->
                          <!--{else}-->
                              <!--{$price02_min|sfPrePoint:$point_rate|number_format}-->～<!--{$price02_max|sfPrePoint:$point_rate|number_format}-->
                          <!--{/if}-->pt</p>
                      <!--{/if}-->
                      </div>
                    </div>
                  
                  <!--{else}-->
                    <div class="btn_regist">
                        <p>お得な価格は会員のみ公開</p>
                        <a href="<!--{$smarty.const.TOP_URLPATH}-->entry/kiyaku.php"><img src="<!--{$TPL_URLPATH}-->img/page/list/productlist/btn_regist.png" width="147" height="34" alt="会員登録" /></a>
                    </div>
                  <!--{/if}-->
              </section>

          <!--{/foreach}-->
        </div>
      </div>
<!--{/if}-->
