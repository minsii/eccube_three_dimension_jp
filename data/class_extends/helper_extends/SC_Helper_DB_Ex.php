<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2012 LOCKON CO.,LTD. All Rights Reserved.
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
require_once CLASS_REALDIR . 'helper/SC_Helper_DB.php';

/**
 * DB関連のヘルパークラス(拡張).
 *
 * LC_Helper_DB をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author LOCKON CO.,LTD.
 * @version $Id: SC_Helper_DB_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $
 */
class SC_Helper_DB_Ex extends SC_Helper_DB {

	/**
	 * 文字列内に"<!--{$PARAM_NAME}-->"の形で挿入されているシステムパラメータをパラメータの値に変換する
	 *
	 * @param $string
	 */
	function sfChangeConsts(&$string ){

		if(!empty($string)){
			preg_match_all('/<!--{\$([^}]+)}-->/', $string, $matches);
			$TPL_URLPATH = SC_Helper_PageLayout_Ex::getUserDir($device_type_id, SC_Display_Ex::detectDevice());
			
			if(is_array($matches[1])){
				$search = array();
				foreach($matches[1] as $mat){
					$mat = str_replace("smarty.const.", "", $mat);
					if(defined($mat)){
						$search['<!--{$smarty.const.'.$mat.'}-->'] = constant($mat);
					}
					if($mat == "TPL_URLPATH"){
						$search['<!--{$TPL_URLPATH}-->'] = $TPL_URLPATH;
					}
				}
				$keys = array_keys($search);
				$values = array_values($search);
				$string = str_replace($keys, $values, $string);
			}
		}
	}	
	
    /**
     * 追加規格一覧を取得する
     * 
     */
    function lfGetAllExtraClass(){
    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	$objQuery->setOrder("rank DESC");
    	$arrRet = $objQuery->select("extra_class_id, name", "dtb_extra_class");
    	
    	$arrExtraClass = array();
    	if(is_array($arrRet)){
    		foreach($arrRet as $row){
    			$arrExtraClass[$row["extra_class_id"]] = $row["name"]; 
    		}
    	}
    	return $arrExtraClass;
    }
    
    /**
     * 追加規格一覧を取得する
     * 
     */
    function lfGetAllExtraClassCategory(){
    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	$objQuery->setOrder("rank DESC");
    	$arrRet = $objQuery->select("extra_class_id, extra_classcategory_id, name", "dtb_extra_classcategory");
    	
    	$arrExtraClassCat = array();
    	if(is_array($arrRet)){
    		foreach($arrRet as $row){
    			$arrExtraClassCat[$row["extra_class_id"]][$row["extra_classcategory_id"]] = $row["name"]; 
    		}
    	}
    	return $arrExtraClassCat;
    } 

    /*## 商品一覧で子カテゴリを表示 ADD BEGIN ##*/
    /**
     * カテゴリツリーの取得を行う.
     *
     * @param integer $parent_category_id 親カテゴリID
     * @param bool $count_check 登録商品数のチェックを行う場合 true
     * @return array カテゴリツリーの配列
     */
    function sfGetChildCats($parent_category_id, $count_check = false) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = 'cat.*, ttl.product_count';
        $from = 'dtb_category as cat left join dtb_category_total_count as ttl on ttl.category_id = cat.category_id';
        $where = 'parent_category_id = ? AND del_flg = 0';
        // 登録商品数のチェック
        if ($count_check) {
            $where .= ' AND product_count > 0';
        }
        $objQuery->setOption('ORDER BY rank DESC');
        $arrRet = $objQuery->select($col, $from, $where, array($parent_category_id));

        return $arrRet;
    }
    /*## 商品一覧で子カテゴリを表示 ADD END ##*/
    
	/*## 在庫表示 ADD BEGIN ##*/
	/**
	 * 規格IDで規格分類リストを取得する
	 *
	 * @param $class_id
	 */
	function sfGetClassCatList($class_id){
		$objQuery = new SC_Query();
		$arrList = array();
		$objQuery->setOrder("rank DESC");
		$arrRet = $objQuery->select("classcategory_id, name", "dtb_classcategory", "class_id=? AND del_flg=0", array($class_id));
		foreach($arrRet as $row){
			$arrList[$row["classcategory_id"]] = $row["name"];
		}
		return $arrList;
	}
	/*## 在庫表示 ADD END ##*/
	
	/*## 在庫表示 ADD BEGIN ##*/
	/**
	 * 在庫表示を設定する
	 * 
	 * 規格1と規格2の分類をそれぞれ取得し、各組合せの在庫を設定する
	 * テンプレートが在庫表（eg. 規格1縦＋規格2横）を表示する時に使う
	 */
	function lfSetClassCatStockTable($product_id, &$objDetailPage){
		// 規格管理の場合、在庫表を表示する
		if($objDetailPage->tpl_classcat_find1){
        	$objProduct = new SC_Product_Ex();
        	
        	// 商品規格情報の取得
        	$arrProductsClass =  $objProduct->getProductsClassFullByProductId($product_id);
        	
			// 規格1分類名一覧
			$arrAllClassCat1 = $this->sfGetClassCatList($arrProductsClass[0]['class_id1']);

			$objDetailPage->arrStock = array();
			$objDetailPage->arrStockClassCat1 = array();
			$objDetailPage->arrStockClassCat2 = array();
			foreach($arrProductsClass as $pdctCls){
				$cls1 = $pdctCls["classcategory_id1"];
				$cls2 = $objDetailPage->tpl_classcat_find2 ? $pdctCls["classcategory_id2"] : 0;
				//在庫
				$objDetailPage->arrStock[$cls1][$cls2] = $pdctCls["stock_unlimited"] ? "-1" : $pdctCls["stock"];
				//表示する規格1分類
				$objDetailPage->arrStockClassCat1[$cls1] = $arrAllClassCat1[$cls1];
			}
			
			if($objDetailPage->tpl_classcat_find2){
				// 規格2分類名一覧
				$arrAllClassCat2 = $this->sfGetClassCatList($arrProductsClass[0]['class_id2']);
				foreach($arrProductsClass as $pdctCls){
					$cls2 = $pdctCls["classcategory_id2"];
					//表示する規格2分類
					$objDetailPage->arrStockClassCat2[$cls2] = $arrAllClassCat2[$cls2];
				}
			}
		}
	}
	
	/**
	 * 在庫表示を設定する
	 * 
	 * 規格1と規格2の組合せを取得し、各組合せの在庫を設定する
	 * テンプレートが規格組合せの在庫一覧を表示する時に使う
	 */
	function lfSetClassCatStockRows($product_id, &$objDetailPage){
		// 規格管理の場合、在庫表を表示する
		if($objDetailPage->tpl_classcat_find1){
        	$objProduct = new SC_Product_Ex();
        	
        	// 商品規格情報の取得
        	$objDetailPage->arrProductsClass =  $objProduct->getProductsClassFullByProductId($product_id);
        	foreach($objDetailPage->arrProductsClass as $cnt => $row){
        		$objDetailPage->arrProductsClass[$cnt]["find_stock"] = 
        			($row["stock_unlimited"] == 1 || $row["stock"] > 0) ? "1" : "0";
        	}
		}
	}
	/*## 在庫表示 ADD END ##*/
}
?>
