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
                <a href="#">FAXからのご注文はコチラ▶</a>
            </div>
        </div>
        <!--{include file='./navi.tpl'}-->
    </div>
    <!--▲HEADER-->
