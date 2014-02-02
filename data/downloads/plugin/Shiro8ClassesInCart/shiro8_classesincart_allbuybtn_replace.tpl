<!--{*
 * Shiro8ProductContents
 * Copyright (C) 2012 Shiro8. All Rights Reserved.
 * http://www.shiro8.net/
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *}-->
<!--PLG:Shiro8ClassesInCart↓-->
<!--{if $tpl_is_login == true && $tpl_classcat_find1}-->
<SCRIPT language=javascript type=text/javascript>
function buyall(){
  $("#classTable").find("input[name^='quantity_']").each(function(){
    if ( this.disabled != true ) $(this).attr("value", "1");
  });
  $("#classTable").find("input[name='buyFlg[]']").each(function(){
    if ( this.disabled != true ) this.checked=true;
  });
}
</SCRIPT>
<div class="pure-u-1-4 allbuy"><a href="javascript:buyall()"><img src="<!--{$TPL_URLPATH}-->img/page/detail/btn_allbuy.png" width="119" height="27" alt="まとめて買う" /></a></div>
<!--{else}-->
<div class="pure-u-1-4 allbuy"></div>
<!--{/if}-->

<!--PLG:Shiro8ClassesInCart↑-->