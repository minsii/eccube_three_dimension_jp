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
<!--{if $tpl_classcat_find1}-->
<!--{else}-->
<!--★数量★-->
<dl class="quantity alignL">
    <dt>数量：</dt>
    <dd><input type="text" class="box60" name="quantity" value="<!--{$arrForm.quantity.value|default:1|h}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->" />
        <!--{if $arrErr.quantity != ""}-->
            <br /><span class="attention"><!--{$arrErr.quantity}--></span>
        <!--{/if}-->
    </dd>
</dl>

<!--{/if}-->

<div id="cartbtn_default">
    <!--★カゴに入れる★-->
    <a href="javascript:void(submitCheck())" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_cartin_on.jpg','cart');" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg','cart');">
        <img src="<!--{$TPL_URLPATH}-->img/button/btn_cartin.jpg" alt="カゴに入れる" name="cart" id="cart" /></a>
</div>

<script type="text/javascript">//<![CDATA[

//入力値チェック
function submitCheck() {
 var msg = "";
 var msgArray = new Array();
 var i;
 var class1Flg = 0;
 var class2Flg = 0;
 var quantityFlg = 0;

 if (document.getElementById("classTable") != null) {
  var maxRow = $("#classTable tbody").children().length;
  for (i = 1; i <= maxRow; i++) {
   //購入にチェックが付いているか判定
   if ($("#buyFlg_" + i).attr('checked')) {
    if ($("#classcategory_id1_" + i).val() == "") {
     if (class1Flg == 0) {
      msgArray.push("規格1を選択してください。");
      class1Flg = 1;
     }
     $("#classcategory_id1_" + i).css("background-color","ffe8e8");
    } else {
     $("#classcategory_id1_" + i).css("background-color","ffffff");
    }
    if ($("#classcategory_id2_" + i) && $("#classcategory_id2_" + i).val() == "") {
     if (class2Flg == 0) {
      msgArray.push("規格2を選択してください。");
      class2Flg = 1;
     }
     $("#classcategory_id2_" + i).css("background-color","ffe8e8");
    } else {
     $("#classcategory_id2_" + i).css("background-color","ffffff");
    }
    if ($("#quantity_" + i).val() == "" || $("#quantity_" + i).val() == "0") {
     if (quantityFlg == 0) {
      msgArray.push("個数を入力してください。");
      quantityFlg = 1;
     }
     $("#quantity_" + i).css("background-color","ffe8e8");
    } else {
     $("#quantity_" + i).css("background-color","ffffff");
    }

   }
  }
 } else {
  if ($("#quantity_1").val() == "" || $("#quantity_1").val() == "0") {
   msgArray.push("個数を入力してください。");
   $("#quantity_1").css("background-color","ffe8e8");
  } else {
   $("#quantity_1").css("background-color","ffffff");
  }
 }

 msg = msgArray.join("\r\n");
 if (msg != '') {
  alert(msg);
  return;
 }

 document.form1.submit();
}
//]]>
</script>
<!--PLG:Shiro8ClassesInCart↑-->