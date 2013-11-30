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

<div id="mynavi_area">
    <!--{strip}-->
        <ul class="mynavi_list clearfix">
            <li><a href="./" class=" "><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_top.png" alt="マイページトップ" width="138" height="45"></a></li>
            <!--{* 会員状態 *}-->
            <!--{if $tpl_login}-->
                <li><a href="./<!--{$smarty.const.DIR_INDEX_PATH}-->" class="<!--{if $tpl_mypageno == 'index'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_orderlist.png" alt="購入履歴一覧" width="138" height="45"></a></li>
                <!--{if $smarty.const.OPTION_FAVORITE_PRODUCT == 1}-->
                    <li><a href="favorite.php" class="<!--{if $tpl_mypageno == 'favorite'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_favorite.png" alt="お気にいり一覧" width="138" height="45"></a></li>
                <!--{/if}-->
                <li><a href="estimate.php" class="<!--{if $tpl_mypageno == 'estimate'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_estimatemonth.png" alt="月額予算実績確認" width="138" height="45"></a></li>
                <li><a href="change.php" class="<!--{if $tpl_mypageno == 'change'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_edituser.png" alt="会員登録内容変更" width="138" height="45"></a></li>
                <li><a href="delivery.php" class="<!--{if $tpl_mypageno == 'delivery'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_address.png" width="138" height="45" alt="お届け先" /></a></li>
                <li><a href="refusal.php" class="<!--{if $tpl_mypageno == 'refusal'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_unregist.png" width="138" height="45" alt="退会手続き" /></a></li>

            <!--{* 退会状態 *}-->
            <!--{else}-->
                <li><a href="<!--{$smarty.const.TOP_URLPATH}-->" class="<!--{if $tpl_mypageno == 'index'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_orderlist.png" alt="購入履歴一覧" width="138" height="45"></a></li>
                <!--{if $smarty.const.OPTION_FAVORITE_PRODUCT == 1}-->
                    <li><a href="<!--{$smarty.const.TOP_URLPATH}-->" class="<!--{if $tpl_mypageno == 'favorite'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_favorite.png" alt="お気にいり一覧" width="138" height="45"></a></li>
                <!--{/if}-->
            <li><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_estimatemonth.png" alt="月額予算実績確認" width="138" height="45"></li>
                <li><a href="<!--{$smarty.const.TOP_URLPATH}-->" class="<!--{if $tpl_mypageno == 'change'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_edituser.png" alt="会員登録内容変更" width="138" height="45"></a></li>
                <li><a href="<!--{$smarty.const.TOP_URLPATH}-->" class="<!--{if $tpl_mypageno == 'delivery'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_address.png" width="138" height="45" alt="お届け先" /></a></li>
                <li><a href="<!--{$smarty.const.TOP_URLPATH}-->" class="<!--{if $tpl_mypageno == 'refusal'}--> selected<!--{/if}-->"><img src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_unregist.png" width="138" height="45" alt="退会手続き" /></a></li>
            <!--{/if}-->
        </ul>

        <!--▼現在のポイント-->
        <!--{if $point_disp !== false}-->
            <div class="point_announce clearfix">
                <p>ようこそ&nbsp;／&nbsp;
                    <span class="user_name"><!--{$CustomerName1|h}--> <!--{$CustomerName2|h}-->様</span>
                    <!--{if $smarty.const.USE_POINT !== false}-->&nbsp;
                        現在の所持ポイントは&nbsp;<span class="point st"><!--{$CustomerPoint|number_format|default:"0"|h}-->pt</span>&nbsp;です。
                    <!--{/if}-->
                </p>
            </div>
        <!--{/if}-->
        <!--▲現在のポイント-->
    <!--{/strip}-->

</div>
<!--▲NAVI-->
