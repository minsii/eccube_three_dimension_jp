<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2010 LOCKON CO.,LTD. All Rights Reserved.
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
// {{{ requires
require_once("../../require.php");

//カテゴリーデータをJSONで返す
	$objQuery =& SC_Query_Ex::getSingletonInstance();

    // 検索条件を画面に表示
    // カテゴリー検索条件
    $req = '';
    if (strlen($_POST['productId']) != 0) {
    	$arrSearch[] = $_POST['productId'];
    	$arrSearch[] = $_POST['classId1'];
    	if ($_POST['classId2'] != '') {
    		$arrSearch[] = $_POST['classId2'];
    	} else {
    		$arrSearch[] = "0";
    	}
    	$arrCat = $objQuery->getRow("price02, stock, stock_unlimited, product_class_id", "dtb_products_class", "product_id = ? AND classcategory_id1 = ? AND classcategory_id2 = ? ", $arrSearch);
    	if ($arrCat["price02"] != '') {
    		$price = $arrCat["price02"];
    	} else {
    		$price = 0;
    	}
        if ($arrCat["stock"] != '') {
        	$stock = $arrCat["stock"];
        } else {
        	$stock = 0;
        }
    	if ($arrCat["stock_unlimited"] != '') {
    		$stockUnlimited = $arrCat["stock_unlimited"];
    	} else {
    		$stockUnlimited = 0;
    	}
    	if ($arrCat["product_class_id"] != '') {
    		$productClassId = $arrCat["product_class_id"];
    	} else {
    		$productClassId = 0;
    	}

    	$req = '{"price":'.$price.', "stock":'.$stock.', "stock_unlimited":'.$stockUnlimited.', "product_class_id":'.$productClassId.'}';
    }

    print $req;

?>
