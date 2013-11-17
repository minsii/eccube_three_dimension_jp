
<!-- ▼MYページ購入詳細画面 -->
<div id="mypagecolumn">
    <h2 class="title_s2">MYページ</h2>

    <div id="mynavi_area">
        <ul class="mynavi_list clearfix">
            <li><a href="<!--{$smarty.const.TOP_URLPATH}-->mypage/"
                class=" "><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_top.png"
                    alt="マイページトップ" width="138" height="45"> </a></li>
            <li><a href="<!--{$smarty.const.TOP_URLPATH}-->mypage/"
                class=" "><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_orderlist.png"
                    alt="購入履歴一覧" width="138" height="45"> </a></li>
            <li><a
                href="<!--{$smarty.const.TOP_URLPATH}-->mypage/favorite.php"
                class=""><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_favorite.png"
                    alt="お気にいり一覧" width="138" height="45"> </a></li>
            <li><a
                href="<!--{$smarty.const.TOP_URLPATH}-->mypage/estimate.php"
                class=""><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_estimatemonth.png"
                    alt="月額予算実績確認" width="138" height="45"> </a></li>
            <li><a
                href="<!--{$smarty.const.TOP_URLPATH}-->mypage/change.php"
                class=""><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_edituser.png"
                    alt="会員登録内容変更" width="138" height="45"> </a></li>
            <li><a
                href="<!--{$smarty.const.TOP_URLPATH}-->mypage/delivery.php"
                class=""><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_address.png"
                    width="138" height="45" alt="お届け先" /> </a></li>
            <li><a
                href="<!--{$smarty.const.TOP_URLPATH}-->mypage/refusal.php"
                class=""><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/navi/btn_unregist.png"
                    width="138" height="45" alt="退会手続き" /> </a></li>
        </ul>
        <!--▼現在のポイント-->
        <div class="point_announce clearfix">
            <p>
                ようこそ&nbsp;／&nbsp;<span class="user_name">テスト 太郎様</span>&nbsp;現在の所持ポイントは&nbsp;<span
                    class="point st">0pt</span>&nbsp;です。
            </p>
        </div>
        <!--▲現在のポイント-->
    </div>
    <!--▲NAVI-->
    <div id="mycontents_area">
        <h3>あああ 様の月額予算実績</h3>

        <table>
            <colgroup>
                <col width="20%" />
                <col />
                <col width="30%" />
            </colgroup>
            <tr>
                <th>期間</th>
                <td>あああ</td>
                <td rowspan="5" class="alignC"><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/btn_copyorder.png"
                    width="178" height="33" alt="この購入日時内容で再発注" /></td>
            </tr>
            <tr>
                <th>月額予算金額</th>
                <td>あああ</td>
            </tr>
        </table>

        <!-- 購入実績合計期間ここから -->
        <table summary="使用ポイント">
            <colgroup>
                <col width="20%">
                <col width="80%">
            </colgroup>
            <tbody>
                <tr>
                    <th class="alignL">ご購入実績合計期間</th>
                    <td>0 pt</td>
                </tr>
                <tr>
                    <th class="alignL">予算残高</th>
                    <td>0 pt</td>
                </tr>
            </tbody>
        </table>
        <!-- 購入実績合計期間ここまで -->

        <!--特集一覧-->
        <section class="special_list">
            <h3>特集一覧</h3>
            <ul class="pure-g">
                <li class="pure-u-1-3"><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png"
                    width="305" height="120" /></li>
                <li class="pure-u-1-3"><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png"
                    width="305" height="120" /></li>
                <li class="pure-u-1-3"><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png"
                    width="305" height="120" /></li>
                <li class="pure-u-1-3"><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png"
                    width="305" height="120" /></li>
                <li class="pure-u-1-3"><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png"
                    width="305" height="120" /></li>
                <li class="pure-u-1-3"><img
                    src="<!--{$TPL_URLPATH}-->img/page/mypage/bnr_01.png"
                    width="305" height="120" /></li>

            </ul>
        </section>
    </div>
</div>
