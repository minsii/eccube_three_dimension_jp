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
<div class="classlist">
	<p class="red">※おまとめご購入は「まとめ買い」ボタンをクリックして、規格選択後「購入」にチェックを入れてカゴに入れて下さい。</p>
	<table id="classTable" style="background:#FFF;">
	 <thead>
	  <tr>
	   <!--★規格1★-->
	   <!--{if $tpl_classcat_find1}-->
	    <th class="alignC"><!--{$tpl_class_name1}--></th>
	   <!--{/if}-->

	   <!--★規格2★-->
	   <!--{if $tpl_classcat_find2}-->
	    <th class="alignC"><!--{$tpl_class_name2}--></th>
	   <!--{/if}-->
	   <th class="alignC">単価(税抜)</th>
	   <th class="alignC">個数</th>
	   <th class="alignC">購入</th>
	  </tr>
	  <tr id="clone" style="display: none;">
	   <!--★規格1★-->
	   <td>
	    <select name="classcategory_id1_rowNum"
	     id = "classcategory_id1_rowNum"
	              style="<!--{$arrErr.classcategory_id1|sfGetErrorColor}-->"
	              onchange="lnSetSelect('form1', 'classcategory_id1_rowNum', 'classcategory_id2_rowNum', '', 'rowNum'); getClassData(rowNum);">
	         <!--{html_options options=$arrClassCat1 selected=$arrForm.classcategory_id1.value}-->
	       </select>
	       <!--{if $arrErr.classcategory_id1 != ""}-->
	       <br /><span class="attention">※ <!--{$tpl_class_name1}-->を入力して下さい。</span>
	      <!--{/if}-->
	   </td>
	   <!--★規格2★-->
	   <!--{if $tpl_classcat_find2}-->
	   <td>
	    <select name="classcategory_id2_rowNum"
	     id="classcategory_id2_rowNum"
	              style="<!--{$arrErr.classcategory_id2|sfGetErrorColor}-->"
	              onchange="getClassData('rowNum');">
	       </select>
	       <!--{if $arrErr.classcategory_id2 != ""}-->
	       <br /><span class="attention">※ <!--{$tpl_class_name2}-->を入力して下さい。</span>
	       <!--{/if}-->
	   </td>
	   <!--{/if}-->

	   <td class="pricetd">
	    <span id="price_rowNum"></span>
	   </td>
	   <td class="unittd">
	    <input type="text" class="box40" name="quantity_rowNum" id="quantity_rowNum" value="<!--{$arrForm.quantity.value|default:1}-->" maxlength="<!--{$smarty.const.INT_LEN}-->" style="<!--{$arrErr.quantity|sfGetErrorColor}-->" />
	    <!--{if $arrErr.quantity != ""}-->
	        <br /><span class="attention"><!--{$arrErr.quantity}--></span>
	    <!--{/if}-->
	   </td>
	   <td id="buyFlgArea_rowNum" class="checktd">&nbsp;

	   </td>
	  </tr>
	 </thead>
	 <tbody>
	 </tbody>
	</table>
	<p class="classaddBtn"><input type="button" value="まとめ買い" onclick="addRow();" /></p>
</div>

<script type="text/javascript">//<![CDATA[
// セレクトボックスに項目を割り当てる。
function lnSetSelect(form, name1, name2, val, rowNow) {
		var $form = $("#" + form);
        var sele11 = document[form][name1];
        var sele12 = document[form][name2];
        var $sele2 = $form.find('select[name=' + name2 + ']');
        
        // 規格1のみの場合
        if ($sele2.is("*")) {
            index = sele11.selectedIndex;

            // セレクトボックスのクリア
            var i = 0;
            count = sele12.options.length;
            for(i = count; i >= 0; i--) {
                    sele12.options[i] = null;
            }
            
            var classcat2 = classCategories[$(sele11).val()];
            // セレクトボックスに値を割り当てる
            i = 0;
            for (var key in classcat2) {
                var id = classcat2[key]['classcategory_id2'];
                var name = classcat2[key]['name'];
                sele12.options[i] = new Option(name, id);
                if(val != "" && vals[index][i] == val) {
                    sele12.options[i].selected = true;
                }
                i++;
            }
        }
}
//]]>
//<![CDATA[
//規格選択時に価格、在庫情報をAjaxで取得
function getClassData(rowNo) {
 //パラメータオブジェクトの生成
 var pram = {};
 pram["classId1"] = $("#classcategory_id1_" + rowNo).val();
 if ($("#classcategory_id2_" + rowNo).is("*")) {
 	pram["classId2"] = $("#classcategory_id2_" + rowNo).val();
 } else {
 	pram["classId2"] = 0;
 }
 pram["productId"] = $("input[name=product_id]").val();

 $.ajax({
    type: "POST",
    async: false,
    url: "<!--{$smarty.const.PLUGIN_HTML_URLPATH}-->Shiro8ClassesInCart/plg_shiro8ClassesInCart_get_class.php",
    data: pram,
    dataType: "json",
    success: function(result){
     //単価をセット
     if (result.price != "0") {
       $("#price_" + rowNo).html("￥" + result.price);
     } else {
      $("#price_" + rowNo).html("");
     }
   //在庫をセット
     if (result.stock_unlimited == 1 || result.stock > 0) {
      $("#buyFlgArea_" + rowNo).html("<input type=\"checkbox\" name=\"buyFlg[]\" id=\"buyFlg_" + rowNo + "\" value=\"" + result.product_class_id + "_" + rowNo + "\" />");
     } else if (result.stock == 0 && result.product_class_id != 0) {
      nonQty = "<p class=\"attention\">在庫0</p>";
      $("#buyFlgArea_" + rowNo).html(nonQty);
     } else {
      $("#buyFlgArea_" + rowNo).html("&nbsp;");
     }
    }
  });
}

//規格テーブルに列を追加
function addRow() {
 var maxRow = $("#classTable tbody").children().length + 1;
 //id=cloneのtrを元に列をコピー追加
 var objSrcTr= $('#classTable thead > tr#clone');
 var htmlInsert = '<tr>'+objSrcTr.html().replace(/rowNum/g, maxRow)+'</tr>';
 $("#classTable tbody").append(htmlInsert);
}

//規格テーブルに1行目を追加
$(function(){
 try {
  addRow();
  } catch(e) {

  }
});

//]]>
</script>

<!--PLG:Shiro8ClassesInCart↑-->