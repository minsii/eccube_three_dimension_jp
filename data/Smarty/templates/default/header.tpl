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

<div id="wrap_all">

    <!--▼HEADER-->
    <div id="header_wrap">
        <div id="header" class="clearfix">
            <p id="site_description">
                <span> <!--{$arrSiteInfo.shop_name|escape}-->/<!--{$tpl_title|escape}-->
                </span>
            </p>
            <div id="logo_area">
                <h1>
                    <a href="<!--{$smarty.const.TOP_URLPATH}-->"><span>three/</span>
                    </a>
                </h1>
            </div>
            <div id="header_utility">
                <div id="header_navi">
                    <ul>
                        <li><a
                            href="<!--{$smarty.const.TOP_URLPATH}-->contact/index.php"><img
                                src="<!--{$TPL_URLPATH}-->img/page/header/btn_otoiawase.png"
                                width="126" height="34" alt="お問い合わせ" />
                        </a>
                        </li>
                        <li><a
                            href="<!--{$smarty.const.TOP_URLPATH}-->user_data/catalog.php"><img
                                src="<!--{$TPL_URLPATH}-->img/page/header/btn_kagarogu.png"
                                width="126" height="34" alt="カタログ" /> </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="hd_lnk_fax">
                <a href="<!--{$smarty.const.TOP_URLPATH}-->user_data/fax.php">FAXからのご注文はコチラ▶</a>
            </div>
        </div>


<nav class="navi">
    <ul class="glo_navi">
        <li><a href="<!--{$smarty.const.TOP_URLPATH}-->"><img onmouseover="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_top_on.png')" onmouseout="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_top.png')" 
                src="<!--{$TPL_URLPATH}-->img/page/navi/btn_top.png" 
                width="123" height="51" alt="トップ" /> </a></li>
        <li><a
            href="<!--{$smarty.const.TOP_URLPATH}-->user_data/goliyouannai.php"><img onmouseover="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_guide_on.png')" onmouseout="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_guide.png')" 
                src="<!--{$TPL_URLPATH}-->img/page/navi/btn_guide.png"
                width="122" height="51" alt="ガイド" /> </a></li>
        <li><a href="<!--{$smarty.const.TOP_URLPATH}-->abouts/index.php"><img onmouseover="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_company_on.png')" onmouseout="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_company.png')" 
                src="<!--{$TPL_URLPATH}-->img/page/navi/btn_company.png"
                width="122" height="51" alt="会社概要" /> </a></li>
        <li><a
            href="<!--{$smarty.const.TOP_URLPATH}-->products/list.php"><img onmouseover="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_item_on.png')" onmouseout="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_item.png')" 
                src="<!--{$TPL_URLPATH}-->img/page/navi/btn_item.png"
                width="122" height="51" alt="商品一覧" /> </a></li>
        <li><a href="<!--{$smarty.const.TOP_URLPATH}-->mypage/"><img onmouseover="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_mypage_on.png')" onmouseout="$(this).attr('src','<!--{$TPL_URLPATH}-->img/page/navi/btn_mypage.png')" 
                src="<!--{$TPL_URLPATH}-->img/page/navi/btn_mypage.png"
                width="122" height="51" alt="マイページ" /> </a></li>
        <li class="regist"><a
            href="<!--{$smarty.const.TOP_URLPATH}-->entry/kiyaku.php"><img
                src="<!--{$TPL_URLPATH}-->img/page/navi/btn_regist.png"
                width="122" height="51" alt="会員登録" /> </a></li>
        <!--{*
        <li class="login"><a
            href="<!--{$smarty.const.TOP_URLPATH}-->mypage/login.php"><img
                src="<!--{$TPL_URLPATH}-->img/page/navi/btn_login.png"
                width="225" height="51" alt="ログイン" /> </a></li>
                *}-->
         <li class="login"><!--{if $smarty.session.customer.customer_id}-->
              <form name="header_login_form" id="header_login_form" method="post" action="<!--{$smarty.const.HTTPS_URL}-->frontparts/login_check.php" onsubmit="return fnCheckLogin('login_form')">
                <input type="hidden" name="mode" value="login" />
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                <input type="hidden" name="url" value="<!--{$smarty.server.PHP_SELF|escape}-->" />
              </form>
              <a href="<!--{$smarty.const.HTTP_URL}-->" onclick="fnFormModeSubmit('header_login_form', 'logout', '', ''); return false;"><img src="<!--{$TPL_URLPATH}-->img/page/navi/btn_logout.png" alt="ログアウト" width="225" height="51" /></a>
            <!--{else}-->
              <a href="<!--{$smarty.const.HTTPS_URL}-->mypage/login.php"><img src="<!--{$TPL_URLPATH}-->img/page/navi/btn_login.png" alt="ログイン" width="225" height="51" /></a>
            <!--{/if}--></li>
    </ul>
</nav>

    </div>
    <!--▲HEADER-->