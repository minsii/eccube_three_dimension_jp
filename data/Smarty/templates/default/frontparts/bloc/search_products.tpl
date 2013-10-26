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
<!--▼検索条件ここから-->
<div class="left_box">
 <div class="left_inner">
  <h2 class="left_ttl"><img src="<!--{$TPL_URLPATH}-->img/side/left_ttl04.gif" width="206" height="43" alt="商品検索"></h2>
  <div id="searcharea" class="left_cont">
<!--検索フォーム-->
   <form name="search_form" id="search_form" method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
    <p><input type="text" name="name" class="box142" maxlength="50" value="<!--{$smarty.get.name|escape}-->" /><span class="btn"><input type="image" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/side/left_search_btn.gif',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/side/left_search_btn.gif',this)" src="<!--{$TPL_URLPATH}-->img/side/left_search_btn.gif" class="box51" alt="検索" name="search" /></span></p>
   </form>
  </div>
 </div>
</div>
<!--▲検索条件ここまで-->