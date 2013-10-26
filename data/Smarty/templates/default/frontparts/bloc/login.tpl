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
<!--▼ログインここから-->
<div class="left_box">
 <div class="left_inner">
  <h2 class="left_ttl"><img src="<!--{$TPL_URLPATH}-->img/side/left_ttl05.gif" width="206" height="43" alt="ログイン"></h2>
  <h3 class="clearfixl ta_center"><img src="<!--{$TPL_URLPATH}-->img/side/left_new_btn.gif" width="180" height="20" alt="新規登録はこちら"></h3>
  <div id="loginarea" class="left_cont">
   <form name="login_form" id="login_form" method="post" action="<!--{$smarty.const.HTTPS_URL}-->frontparts/login_check.php" onsubmit="return fnCheckLogin('login_form')">
    <input type="hidden" name="mode" value="login" />
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="url" value="<!--{$smarty.server.PHP_SELF|escape}-->" />
    <div id="login">
      <!--{if $tpl_login}-->
      <p>ようこそ<br />
       <!--{$tpl_name1|escape}--> <!--{$tpl_name2|escape}--> 様 <br />
       <!--{if $smarty.const.USE_POINT === true}-->所持ポイント：<span class="price"> <!--{$tpl_user_point|number_format|default:0}--> pt</span>
       <!--{/if}-->
      </p>
      <!--{if !$tpl_disable_logout}-->
      <p class="btn">
       <a href="<!--{$smarty.server.PHP_SELF|escape}-->" onclick="fnFormModeSubmit('login_form', 'logout', '', ''); return false;"><img src="<!--{$TPL_DIR}-->img/header/logout.gif" width="44" height="21" alt="ログアウト" /></a>
      </p>
     </div>
      <!--{/if}-->
      <!--{else}-->
     <p>メールアドレス<brs><input type="text" name="login_email" class="box96" value="<!--{$tpl_login_email|escape}-->" style="ime-mode: disabled;"/></p>
     <p>パスワード<br /><input type="password" name="login_pass" class="box96" /></p>
    </div>
    <input type="checkbox" name="login_memory" value="1" <!--{$tpl_login_memory|sfGetChecked:1}--> />
    <img src="<!--{$TPL_DIR}-->img/header/memory.gif" width="18" height="9" alt="記憶" />
    <p class="btn">
     <input type="image" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/side/left_login_btn.gif',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/side/left_login_btn.gif',this)" src="<!--{$TPL_URLPATH}-->img/side/left_login_btn.gif" class="box51" alt="ログイン" name="subm" />
    </p>
    <p class="mini">
     <a href="<!--{$smarty.const.SSL_URL|sfTrimURL}-->/forgot/index.php" onclick="win01('<!--{$smarty.const.SSL_URL|sfTrimURL}-->/forgot/index.php','forget','600','400'); return false;" target="_blank">&gt;&gt;パスワードを忘れた方はこちら</a>
    </p>
      <!--{/if}-->
     <!--ログインフォーム-->
   </form>
  </div>
 </div>
</div>
<!--▲ログインここまで-->