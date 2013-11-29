<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
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
<!--{$arrOrder.order_name01}--> <!--{$arrOrder.order_name02}--> 様

<!--{$tpl_header}-->

************************************************
　ご請求金額
************************************************

ご注文番号：<!--{$arrOrder.order_id}-->
お支払合計：￥ <!--{$arrOrder.payment_total|number_format|default:0}-->
ご決済方法：<!--{$arrOrder.payment_method}-->
メッセージ：<!--{$Message_tmp}-->

<!--{if $arrOther.title.value}-->
************************************************
　<!--{$arrOther.title.name}-->情報
************************************************

<!--{foreach key=key item=item from=$arrOther}-->
<!--{if $key != "title"}-->
<!--{if $item.name != ""}--><!--{$item.name}-->：<!--{/if}--><!--{$item.value}-->
<!--{/if}-->
<!--{/foreach}-->
<!--{/if}-->

************************************************
　ご注文商品明細
************************************************

<!--{section name=cnt loop=$arrOrderDetail}-->
商品コード: <!--{$arrOrderDetail[cnt].product_code}-->
商品名: <!--{$arrOrderDetail[cnt].product_name}--> <!--{$arrOrderDetail[cnt].classcategory_name1}--> <!--{$arrOrderDetail[cnt].classcategory_name2}-->
<!--{*## 追加規格 ADD BEGIN ##*}-->
<!--{if $smarty.const.USE_EXTRA_CLASS === true}-->
    <!--{foreach key=extcls_id item=extclscat_id from=$arrOrderDetail[cnt].extra_info.extra_classcategory_id}-->
    <!--{assign var=extraInfo value=$arrOrderDetail[cnt].extra_info.extra_classcategory}-->
    <!--{assign var=extcls_nm_key value="extra_class_name`$extcls_id`"}-->
    <!--{assign var=extclscat_nm_key value="extra_classcategory_name`$extcls_id`"}-->
    <!--{if $extraInfo[$extcls_nm_key]}-->
            <!--{$extraInfo[$extcls_nm_key]}--> : <!--{$extraInfo[$extclscat_nm_key]}-->
    <!--{/if}-->
    <!--{/foreach}-->
<!--{/if}-->
<!--{*## 追加規格 ADD END ##*}-->
<!--{*## 商品非課税 MDF BEGIN ##*}-->
単価：￥ <!--{if $smarty.const.USE_TAXFREE_PRODUCT === true && $arrOrderDetail[cnt].taxfree == 1}--><!--{$arrOrderDetail[cnt].price|number_format}-->円（税抜）
<!--{else}--><!--{$arrOrderDetail[cnt].price|sfCalcIncTax:$arrInfo.tax:$arrInfo.tax_rule|number_format}-->円（税込）
<!--{/if}-->
<!--{*## 商品非課税 MDF END ##*}-->
数量：<!--{$arrOrderDetail[cnt].quantity}-->

<!--{/section}-->
-------------------------------------------------
小　計 ￥ <!--{$arrOrder.subtotal|number_format|default:0}--> (うち消費税 ￥<!--{$arrOrder.tax|number_format|default:0}-->）
値引き ￥ <!--{$arrOrder.use_point*$smarty.const.POINT_VALUE+$arrOrder.discount|number_format|default:0}-->
送　料 ￥ <!--{$arrOrder.deliv_fee|number_format|default:0}-->
手数料 ￥ <!--{$arrOrder.charge|number_format|default:0}-->
============================================
合　計 ￥ <!--{$arrOrder.payment_total|number_format|default:0}-->

************************************************
　注文者情報
************************************************
<!--{if $smarty.const.USE_CUSTOMER_COMPANY === true}-->
　法人名　：<!--{$arrOrder.order_company}--><!--{$arrOrder.order_company_department}-->　様
<!--{/if}-->
　お名前　：<!--{$arrOrder.order_name01}--> <!--{$arrOrder.order_name02}-->　様
　郵便番号：〒<!--{$arrOrder.order_zip01}-->-<!--{$arrOrder.order_zip02}-->
　住所　　：<!--{$arrPref[$arrOrder.order_pref]}--><!--{$arrOrder.order_addr01}--><!--{$arrOrder.order_addr02}-->
　電話番号：<!--{$arrOrder.order_tel01}-->-<!--{$arrOrder.order_tel02}-->-<!--{$arrOrder.order_tel03}-->
<!--{*## 顧客お届け先FAX ADD BEGIN ##*}--><!--{if $smarty.const.USE_OTHER_DELIV_FAX === true}-->
　FAX番号 ：<!--{$arrOrder.order_fax01}-->-<!--{$arrOrder.order_fax02}-->-<!--{$arrOrder.order_fax03}-->
<!--{/if}--><!--{*## 顧客お届け先FAX ADD END ##*}-->


************************************************
　配送情報
************************************************

<!--{foreach item=shipping name=shipping from=$arrShipping}-->
◎お届け先<!--{if count($arrShipping) > 1}--><!--{$smarty.foreach.shipping.iteration}--><!--{/if}-->

　法人名　：<!--{$shipping.shipping_company}-->　様
　お名前　：<!--{$shipping.shipping_name01}--> <!--{$shipping.shipping_name02}-->　様
　郵便番号：〒<!--{$shipping.shipping_zip01}-->-<!--{$shipping.shipping_zip02}-->
　住所　　：<!--{$arrPref[$shipping.shipping_pref]}--><!--{$shipping.shipping_addr01}--><!--{$shipping.shipping_addr02}-->
　電話番号：<!--{$shipping.shipping_tel01}-->-<!--{$shipping.shipping_tel02}-->-<!--{$shipping.shipping_tel03}-->
<!--{*## 顧客お届け先FAX ADD BEGIN ##*}--><!--{if $smarty.const.USE_OTHER_DELIV_FAX === true}-->
　FAX番号 ：<!--{if $shipping.shipping_fax01 > 0}--><!--{$shipping.shipping_fax01}-->-<!--{$shipping.shipping_fax02}-->-<!--{$shipping.shipping_fax03}--><!--{/if}-->
　  
<!--{/if}--><!--{*## 顧客お届け先FAX ADD END ##*}-->
　お届け日：<!--{$shipping.shipping_date|date_format:"%Y/%m/%d"|default:"指定なし"}-->
　お届け時間：<!--{$shipping.shipping_time|default:"指定なし"}-->

<!--{foreach item=item name=item from=$shipping.shipment_item}-->
商品コード: <!--{$item.product_code}-->
商品名: <!--{$item.product_name}--> <!--{$item.classcategory_name1}--> <!--{$item.classcategory_name2}-->
<!--{*## 商品非課税 MDF BEGIN ##*}-->
<!--{if $smarty.const.USE_TAXFREE_PRODUCT !== true}-->
単価：￥ <!--{$item.price|sfCalcIncTax|number_format}-->円（税込）
<!--{/if}-->
<!--{*## 商品非課税 MDF END ##*}-->
数量：<!--{$item.quantity}-->

<!--{/foreach}-->
<!--{/foreach}-->
<!--{if $arrOrder.customer_id && $smarty.const.USE_POINT !== false}-->
============================================
<!--{* ご注文前のポイント {$tpl_user_point} pt *}-->
ご使用ポイント <!--{$arrOrder.use_point|default:0|number_format}--> pt
今回加算される予定のポイント <!--{$arrOrder.add_point|default:0|number_format}--> pt
現在の所持ポイント <!--{$arrCustomer.point|default:0|number_format}--> pt
<!--{/if}-->
<!--{$tpl_footer}-->
