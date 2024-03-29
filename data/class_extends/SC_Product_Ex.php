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
    
    /*## 商品ステータス2、ステータス3を追加 ADD BEGIN ##*/    
    /**
     * 商品ステータス2を設定する.
     *
     * @param integer $productId 商品ID
     * @param array $productStatusIds ON にする商品ステータス2のIDの配列
     */
    function setProductStatus2($productId, $productStatusIds, &$objQuery=null) {
    	$cmt = 0;
    	if($objQuery == null){
    		$objQuery =& SC_Query_Ex::getSingletonInstance();
    		$objQuery->begin();
    		$cmt = 1;
    	}
    	$val['product_id'] = $productId;

    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	$objQuery->delete('dtb_product_status2', 'product_id = ?', array($productId));
    	foreach ($productStatusIds as $statusId) {
    		if ($statusId == '') continue;
    		$val['status2_id'] = $statusId;
    		$objQuery->insert('dtb_product_status2', $val);
    	}
    	if($cmt){
    		$objQuery->commit();
    	}
    }
    
    /**
     * 商品ステータス3を設定する.
     *
     * @param integer $productId 商品ID
     * @param array $productStatusIds ON にする商品ステータス3のIDの配列
     */
    function setProductStatus3($productId, $productStatusIds, &$objQuery=null) {
    	$cmt = 0;
    	if($objQuery == null){
    		$objQuery =& SC_Query_Ex::getSingletonInstance();
    		$objQuery->begin();
    		$cmt = 1;
    	}
    	$val['product_id'] = $productId;

    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	$objQuery->delete('dtb_product_status3', 'product_id = ?', array($productId));
    	foreach ($productStatusIds as $statusId) {
    		if ($statusId == '') continue;
    		$val['status3_id'] = $statusId;
    		$objQuery->insert('dtb_product_status3', $val);
    	}
    	if($cmt){
    		$objQuery->commit();
    	}
    }
    
    /**
     * 商品IDをキーにした, 商品ステータス2のIDの配列を取得する.
     *
     * @param array 商品ID の配列
     * @return array 商品IDをキーにした商品ステータス2のIDの配列
     */
    function getProductStatus2($productIds) {
        if (empty($productIds)) {
            return array();
        }
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $cols = 'product_id, status2_id';
        $from = 'dtb_product_status2';
        $where = 'product_id IN (' . SC_Utils_Ex::repeatStrWithSeparator('?', count($productIds)) . ')';
        $productStatus = $objQuery->select($cols, $from, $where, $productIds);
        $results = array();
        foreach ($productStatus as $status) {
            $results[$status['product_id']][] = $status['status2_id'];
        }
        return $results;
    }
    
    /**
     * 商品IDをキーにした, 商品ステータス3のIDの配列を取得する.
     *
     * @param array 商品ID の配列
     * @return array 商品IDをキーにした商品ステータス3のIDの配列
     */
    function getProductStatus3($productIds) {
        if (empty($productIds)) {
            return array();
        }
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $cols = 'product_id, status3_id';
        $from = 'dtb_product_status3';
        $where = 'product_id IN (' . SC_Utils_Ex::repeatStrWithSeparator('?', count($productIds)) . ')';
        $productStatus = $objQuery->select($cols, $from, $where, $productIds);
        $results = array();
        foreach ($productStatus as $status) {
            $results[$status['product_id']][] = $status['status3_id'];
        }
        return $results;
    }
    /*## 商品ステータス2、ステータス3を追加 ADD END ##*/
    
    /*## 商品一覧カスタマイズ ADD BEGIN ##*/
    /**
     * SC_Queryインスタンスに設定された検索条件をもとに商品一覧の配列を取得する.
     *
     * 主に SC_Product::findProductIds() で取得した商品IDを検索条件にし,
     * SC_Query::setOrder() や SC_Query::setLimitOffset() を設定して, 商品一覧
     * の配列を取得する.
     *
     * @param SC_Query $objQuery SC_Query インスタンス
     * @return array 商品一覧の配列
     */
    function lists(&$objQuery) {
        $col = <<< __EOS__
             product_id
            ,product_code_min
            ,product_code_max
            ,name
            ,comment1
            ,comment2
            ,comment3
            ,main_list_comment
            ,main_image
            ,main_list_image
            ,price01_min
            ,price01_max
            ,price02_min
            ,price02_max
            ,stock_min
            ,stock_max
            ,stock_unlimited_min
            ,stock_unlimited_max
            ,deliv_date_id
            ,status
            ,del_flg
            ,update_date
            ,point_rate
            ,class_count
__EOS__;

        /*## 商品非課税指定 ADD BEGIN ##*/
        if(USE_TAXFREE_PRODUCT === true){
        	$col .= ", taxfree";
        }
        /*## 商品非課税指定 ADD END ##*/
        
        $res = $objQuery->select($col, $this->alldtlSQL());
        return $res;
    }
    /*## 商品一覧カスタマイズ ADD END ##*/
    
    /**
     * 商品詳細の SQL を取得する.
     *
     * @param string $where_products_class 商品規格情報の WHERE 句
     * @return string 商品詳細の SQL
     */
    function alldtlSQL($where_products_class = '') {
        if (!SC_Utils_Ex::isBlank($where_products_class)) {
            $where_products_class = 'AND (' . $where_products_class . ')';
        }
        /*
         * point_rate, deliv_fee は商品規格(dtb_products_class)ごとに保持しているが,
         * 商品(dtb_products)ごとの設定なので MAX のみを取得する.
         */
        $col = <<< __EOS__
            (
                SELECT
                     dtb_products.product_id
                    ,dtb_products.name
                    ,dtb_products.maker_id
                    ,dtb_products.status
                    ,dtb_products.comment1
                    ,dtb_products.comment2
                    ,dtb_products.comment3
                    ,dtb_products.comment4
                    ,dtb_products.comment5
                    ,dtb_products.comment6
                    ,dtb_products.note
                    ,dtb_products.main_list_comment
                    ,dtb_products.main_list_image
                    ,dtb_products.main_comment
                    ,dtb_products.main_image
                    ,dtb_products.main_large_image
                    ,dtb_products.sub_title1
                    ,dtb_products.sub_comment1
                    ,dtb_products.sub_image1
                    ,dtb_products.sub_large_image1
                    ,dtb_products.sub_title2
                    ,dtb_products.sub_comment2
                    ,dtb_products.sub_image2
                    ,dtb_products.sub_large_image2
                    ,dtb_products.sub_title3
                    ,dtb_products.sub_comment3
                    ,dtb_products.sub_image3
                    ,dtb_products.sub_large_image3
                    ,dtb_products.sub_title4
                    ,dtb_products.sub_comment4
                    ,dtb_products.sub_image4
                    ,dtb_products.sub_large_image4
                    ,dtb_products.sub_title5
                    ,dtb_products.sub_comment5
                    ,dtb_products.sub_image5
                    ,dtb_products.sub_large_image5
                    ,dtb_products.sub_title6
                    ,dtb_products.sub_comment6
                    ,dtb_products.sub_image6
                    ,dtb_products.sub_large_image6
                    ,dtb_products.del_flg
                    ,dtb_products.creator_id
                    ,dtb_products.create_date
                    ,dtb_products.update_date
                    ,dtb_products.deliv_date_id
                    ,T4.product_code_min
                    ,T4.product_code_max
                    ,T4.price01_min
                    ,T4.price01_max
                    ,T4.price02_min
                    ,T4.price02_max
                    ,T4.stock_min
                    ,T4.stock_max
                    ,T4.stock_unlimited_min
                    ,T4.stock_unlimited_max
                    ,T4.point_rate
                    ,T4.deliv_fee
                    ,T4.class_count
                    ,dtb_maker.name AS maker_name
__EOS__;
        $from = <<< __EOS__
                FROM dtb_products
                    JOIN (
                        SELECT product_id,
                            MIN(product_code) AS product_code_min,
                            MAX(product_code) AS product_code_max,
                            MIN(price01) AS price01_min,
                            MAX(price01) AS price01_max,
                            MIN(price02) AS price02_min,
                            MAX(price02) AS price02_max,
                            MIN(stock) AS stock_min,
                            MAX(stock) AS stock_max,
                            MIN(stock_unlimited) AS stock_unlimited_min,
                            MAX(stock_unlimited) AS stock_unlimited_max,
                            MAX(point_rate) AS point_rate,
                            MAX(deliv_fee) AS deliv_fee,
                            COUNT(*) as class_count
                        FROM dtb_products_class
                        WHERE del_flg = 0 $where_products_class
                        GROUP BY product_id
                    ) AS T4
                        ON dtb_products.product_id = T4.product_id
                    LEFT JOIN dtb_maker
                        ON dtb_products.maker_id = dtb_maker.maker_id
            ) AS alldtl
__EOS__;
        
        /*## 商品非課税指定 ADD BEGIN ##*/
        if(USE_TAXFREE_PRODUCT === true){
        	$col .= ",dtb_products.taxfree";
        }
        /*## 商品非課税指定 ADD END ##*/

        $sql = $col . $from;
        
        return $sql;
    }
}

?>
