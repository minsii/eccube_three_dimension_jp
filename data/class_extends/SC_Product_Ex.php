<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
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

require_once CLASS_REALDIR . 'SC_Product.php';

class SC_Product_Ex extends SC_Product {
	
	/*## 商品マスタ一覧で公開状態変更 ADD BEGIN ##*/
	/**
	 * 商品公開・非公開状態を切替える
	 *
	 * @param integer $productId 商品ID
	 * @return void
	 */
	function changeDisp($productId, $dispMaster = null, &$objQuery = null) {
		if($objQuery == null){
			$objQuery =& SC_Query_Ex::getSingletonInstance();
		}

		if($dispMaster == null){
			$masterData = new SC_DB_MasterData_Ex();
			$dispMaster = $masterData->getMasterData('mtb_disp');
		}

		$disp_count = count($dispMaster);
		$curr_status = $objQuery->get("status", "dtb_products", "product_id = ? AND del_flg = 0", array($productId));

		$sqlval['status']     = ($curr_status + 1) % ($disp_count + 1);
		if($sqlval['status'] == 0) $sqlval['status'] = 1;
		$sqlval['update_date'] = 'CURRENT_TIMESTAMP';

		$objQuery->update('dtb_products', $sqlval, "product_id = ?", array($productId));
	}
	/*## 商品マスタ一覧で公開状態変更 ADD END ##*/
    
	/*## 追加規格 ADD BEGIN ##*/
    /**
     * 商品の追加規格一覧を取得する.
     *
     * @param integer $productId 商品ID
     * @return array 商品追加規格一覧の配列
     */
    function getExtraClass($productId, &$objQuery = null) {
    	if(empty($objQuery))
        	$objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->setOrder("product_extra_class_id");
        $result = $objQuery->select("T1.*, T2.name AS extra_class_name", "dtb_products_extra_class T1 LEFT JOIN dtb_extra_class T2 USING(extra_class_id)",
                                    "product_id = ?",
                                    array($productId));
        return $result;
    }	
    /*## 追加規格 ADD END ##*/
    
 	/*## 商品支払方法指定 ADD BEGIN ##*/
 	function setProductPayment($objQuery = null, $arrPaymentId, $product_id){
        if($objQuery == null){
    		$cmt = true;
        	$objQuery =& SC_Query_Ex::getSingletonInstance();
        	$objQuery->begin();
    	}
    	
		$objQuery->delete("dtb_product_payment", "product_id = ?", array($product_id));  
		$sqlval = array();
		$sqlval["product_id"] = $product_id;
        foreach ($arrPaymentId as $pid) {
            if($pid == '') continue;
            $sqlval['payment_id'] = $pid;
            
            $objQuery->insert('dtb_product_payment', $sqlval);
        }

        if($cmt){
        	$objQuery->commit();
        }
    }
    
    /**
     * 商品IDをキーにした, 商品支払方法IDの配列を取得する.
     *
     * @param array 商品ID の配列
     * @return array 商品IDをキーにした商品支払方法IDの配列
     */
    function getProductPayment($productIds) {
        if (empty($productIds)) {
            return array();
        }
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $cols = 'product_id, payment_id';
        $from = 'dtb_product_payment';
        $where = 'product_id IN (' . implode(', ', array_pad(array(), count($productIds), '?')) . ')';
        $arrRet = $objQuery->select($cols, $from, $where, $productIds);
        $results = array_fill_keys($productIds, array());
        foreach ($arrRet as $row) {
            $results[$row['product_id']][] = $row['payment_id'];
        }
        return $results;
    }
    /*## 商品支払方法指定 ADD END ##*/
    
    /*## 商品配送方法指定 ADD BEGIN ##*/
 	function setProductDeliv($objQuery = null, $arrDelivId, $product_id){
        if($objQuery == null){
    		$cmt = true;
        	$objQuery =& SC_Query_Ex::getSingletonInstance();
        	$objQuery->begin();
    	}
    	
		$objQuery->delete("dtb_product_deliv", "product_id = ?", array($product_id));  
		$sqlval = array();
		$sqlval["product_id"] = $product_id;
        foreach ($arrDelivId as $did) {
            if($did == '') continue;
            $sqlval['deliv_id'] = $did;
            
            $objQuery->insert('dtb_product_deliv', $sqlval);
        }

        if($cmt){
        	$objQuery->commit();
        }
    }
    
    /**
     * 商品IDをキーにした, 商品配送方法IDの配列を取得する.
     *
     * @param array 商品ID の配列
     * @return array 商品IDをキーにした商品配送方法IDの配列
     */
    function getProductDeliv($productIds) {
        if (empty($productIds)) {
            return array();
        }
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $cols = 'product_id, deliv_id';
        $from = 'dtb_product_deliv';
        $where = 'product_id IN (' . implode(', ', array_pad(array(), count($productIds), '?')) . ')';
        $arrRet = $objQuery->select($cols, $from, $where, $productIds);
        $results = array_fill_keys($productIds, array());
        foreach ($arrRet as $row) {
            $results[$row['product_id']][] = $row['deliv_id'];
        }
        return $results;
    }
    /*## 商品配送方法指定 ADD END ##*/
    
    /*## 商品マスタ一覧で在庫変更 ADD BEGIN ##*/
    function changeStock($product_id, $classcategory_id1=0, $classcategory_id2=0, $stock=0, $stock_unlimited=0, &$objQuery){
    	if($objQuery == null) $objQuery =& SC_Query_Ex::getSingletonInstance();
    	
    	if($stock_unlimited == 1){
    		$sqlval["stock"] = null;
    		$sqlval["stock_unlimited"] = 1;
    	}else{
    		$sqlval["stock"] = $stock;
    		$sqlval["stock_unlimited"] = "0";
    	}
    	$sqlval['update_date'] = 'CURRENT_TIMESTAMP';
    	$objQuery->update("dtb_products_class", $sqlval, "product_id=? AND classcategory_id1=? AND classcategory_id2=?",
    						array($product_id, $classcategory_id1, $classcategory_id2));
    }
    /*## 商品マスタ一覧で在庫変更 ADD END ##*/
}

?>
