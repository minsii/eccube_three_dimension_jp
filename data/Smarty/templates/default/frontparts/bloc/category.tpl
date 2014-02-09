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
 *}--> <!--商品カテゴリここから-->   <script type="text/javascript">//<![CDATA[
  $(function() {
	
	$("#categorytree li").hover(function() {
		$(this).children('ul').show();
	}, function() {
		$(this).children('ul').hide();
	});

  });
//]]></script>
<!--▼カテゴリここから-->
<p><section class="category_box">
<h2>カテゴリ</h2>
<div id="categoryarea">
<ul id="categorytree">
<!--{assign var=preLev value=1}-->
<!--{assign var=firstdone value=0}-->
<!--{section name=cnt loop=$arrTree}-->

    <!--{assign var=level value=`$arrTree[cnt].level`}-->
    <!--{assign var=levdiff value=`$level-$preLev`}-->
    <!--{if $levdiff > 0}-->
        <ul>
    <!--{elseif $levdiff == 0 && $firstdone == 1}-->
    <!--{elseif $levdiff < 0}-->
      <!--{section name=d loop=`$levdiff*-1`}-->
      </ul>
      <!--{/section}-->
    <!--{/if}-->
    <li class="level<!--{$level}--><!--{if in_array($arrTree[cnt].category_id, $tpl_category_id) }--> onmark<!--{/if}-->">
    <!--{if $level == 1}-->
      <p><a href="<!--{$smarty.const.P_LIST_URLPATH|sfGetFormattedUrl:$arrTree[cnt].category_id}-->">
      <img src="<!--{$TPL_URLPATH}-->img/page/bloc/category_box/bnr_category_<!--{$arrTree[cnt].category_id|string_format:'%02d'}-->.png" width="199" height="48" alt="<!--{$arrTree[cnt].category_name|escape}-->" />
      </a></p>
    <!--{else}-->
      <p><span> &gt; </span><a href="<!--{$smarty.const.P_LIST_URLPATH|sfGetFormattedUrl:$arrTree[cnt].category_id}-->"><!--{$arrTree[cnt].category_name|escape}--></a></p>
    <!--{/if}-->
    <!--{if $firstdone == 0}-->
      <!--{assign var=firstdone value=1}-->
    <!--{/if}-->
    <!--{assign var=preLev value=`$level`}-->

    <!--{* セクションの最後に閉じタグを追加 *}-->
    <!--{if $smarty.section.cnt.last}-->
      <!--{if $preLev-1 > 0 }-->
        <!--{section name=d loop=`$preLev-1`}-->
          </li>
        </ul>
        <!--{/section}-->
      <!--{else}-->
      <!--{/if}-->
    <!--{/if}-->
<!--{/section}-->
</div>
</section></p>
<!--▲カテゴリここまで-->