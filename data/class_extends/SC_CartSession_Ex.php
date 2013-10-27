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

require_once CLASS_REALDIR . 'SC_CartSession.php';

class SC_CartSession_Ex extends SC_CartSession {
	
    // カートへの商品追加
    function addProduct($product_class_id, $quantity, $arrExtraInfo=array()) {
        $objProduct = new SC_Product_Ex();
        $arrProduct = $objProduct->getProductsClass($product_class_id);
        $productTypeId = $arrProduct['product_type_id'];
        $find = false;
        $max = $this->getMax($productTypeId);
        for($i = 0; $i <= $max; $i++) {

            if($this->cartSession[$productTypeId][$i]['id'] == $product_class_id &&
            	$this->cartSession[$productTypeId][$max+1]['extra_info'] == $arrExtraInfo) {
                $val = $this->cartSession[$productTypeId][$i]['quantity'] + $quantity;
                if(strlen($val) <= INT_LEN) {
                    $this->cartSession[$productTypeId][$i]['quantity'] += $quantity;
                }
                $find = true;
            }
        }
        if(!$find) {
            $this->cartSession[$productTypeId][$max+1]['id'] = $product_class_id;
            $this->cartSession[$productTypeId][$max+1]['quantity'] = $quantity;
            $this->cartSession[$productTypeId][$max+1]['cart_no'] = $this->getNextCartID($productTypeId);
            
            /*## 追加規格 ADD BEGIN ##*/
            $this->cartSession[$productTypeId][$max+1]['extra_info'] = $arrExtraInfo;
            /*## 追加規格 ADD END ##*/
        }
    }	
    
    
    /**
     * 商品種別ごとにカート内商品の一覧を取得する.
     *
     * @param integer $productTypeId 商品種別ID
     * @return array カート内商品一覧の配列
     */
    function getCartList($productTypeId) {
        $objProduct = new SC_Product_Ex();
        $max = $this->getMax($productTypeId);
        $arrRet = array();
        for ($i = 0; $i <= $max; $i++) {
            if (isset($this->cartSession[$productTypeId][$i]['cart_no'])
                && $this->cartSession[$productTypeId][$i]['cart_no'] != '') {

                // 商品情報は常に取得
                // TODO 同一インスタンス内では1回のみ呼ぶようにしたい
                $this->cartSession[$productTypeId][$i]['productsClass']
                    =& $objProduct->getDetailAndProductsClass($this->cartSession[$productTypeId][$i]['id']);

                $price = $this->cartSession[$productTypeId][$i]['productsClass']['price02'];
                $this->cartSession[$productTypeId][$i]['price'] = $price;

                $this->cartSession[$productTypeId][$i]['point_rate']
                    = $this->cartSession[$productTypeId][$i]['productsClass']['point_rate'];

                $quantity = $this->cartSession[$productTypeId][$i]['quantity'];
                $incTax = SC_Helper_DB_Ex::sfCalcIncTax($price);
                $total = $incTax * $quantity;
                
                /*## 追加規格 ADD BEGIN ##*/
                $extra_classcategory = array();
                foreach($this->cartSession[$productTypeId][$i]['extra_info']["extra_classcategory_id"] 
                	as $extcls_id=>$extclscat_id){
                	$extra_classcategory["extra_class_name$extcls_id"] = $arrAllExtraClass[$extcls_id];
                	$extra_classcategory["extra_classcategory_name$extcls_id"] = $arrAllExtraClassCat[$extcls_id][$extclscat_id];
                }
                $this->cartSession[$productTypeId][$i]	["extra_info"]["extra_classcategory"] = $extra_classcategory;
                /*## 追加規格 ADD END ##*/
                
                $this->cartSession[$productTypeId][$i]['total_inctax'] = $total;

                $arrRet[] = $this->cartSession[$productTypeId][$i];

                // セッション変数のデータ量を抑制するため、一部の商品情報を切り捨てる
                // XXX 上で「常に取得」するのだから、丸ごと切り捨てて良さそうにも感じる。
                $this->adjustSessionProductsClass($this->cartSession[$productTypeId][$i]['productsClass']);
            }
        }
        return $arrRet;
    }
    
    
     /**
     * セッション中の商品情報データの調整。
     * productsClass項目から、不必要な項目を削除する。
     */
    function adjustSessionProductsClass(&$arrProductsClass) {
        $arrNecessaryItems = array(
            'product_id'          => true,
            'product_class_id'    => true,
            'name'                => true,
            'price02'             => true,
            'point_rate'          => true,
            'main_list_image'     => true,
            'main_image'          => true,
            'product_code'        => true,
            'stock'               => true,
            'stock_unlimited'     => true,
            'sale_limit'          => true,
            'class_name1'         => true,
            'classcategory_name1' => true,
            'class_name2'         => true,
            'classcategory_name2' => true,
        /*## お届け日付非表示のバグ修正 ADD BEGIN ##*/
        	'deliv_date_id' => true,
        /*## お届け日付非表示のバグ修正 ADD END ##*/
        );

        // 必要な項目以外を削除。
        foreach (array_keys($arrProductsClass) as $key) {
            if (!isset($arrNecessaryItems[$key])) {
                unset($arrProductsClass[$key]);
            }
        }
    }
 
    /*## 配送ランク ADD BEGIN ##*/
    /**
     * 都道府県から配送料金を取得する.
     *
     * @param integer|array $pref_id 都道府県ID 又は都道府県IDの配列
     * @param integer $deliv_id 配送業者ID
     * @return string 指定の都道府県, 配送業者の配送料金
     */
    function sfGetDelivFee($pref_id, $deliv_id = 0) {
        if(USE_DELIV_RANK !== true){
			return parent::sfGetDelivFee($pref_id, $deliv_id);
		}
		
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		if (!is_array($pref_id)) {
			$pref_id = array($pref_id);
		}
		$sql = <<< __EOS__
			SELECT T1.fee AS fee
			FROM dtb_delivfee T1
			JOIN dtb_deliv T2
			  ON T1.deliv_id = T2.deliv_id
			JOIN dtb_products T3
			  ON T1.deliv_rank = T3.deliv_rank
			JOIN dtb_products_class T4
			  ON T3.product_id = T4.product_id
			WHERE T1.pref = ?
			  AND T1.deliv_id = ?
			  AND T4.product_class_id = ?
			  AND T2.del_flg = 0
__EOS__;
		$result = 0;

		if ($_SESSION['shipping'] && count($_SESSION['shipping']) > 1){
			//複数配送
			foreach ($_SESSION['shipping'] as $shipping){
				$place_deliv_fee = 0;
				foreach ($shipping['shipment_item'] as $product_class_id => $shipment_item) {
					//該当商品の送料
					$tmp = $objQuery->getOne($sql, array($shipping['shipping_pref'], $deliv_id, $product_class_id));
					if ($tmp > $place_deliv_fee){
						//該当配送先の送料を、商品の送料に設定
						$place_deliv_fee = $tmp;
					}
				}
				$result += $place_deliv_fee;
			}
		}else{
			//複数配送でない
			$objCartSession = new SC_CartSession_Ex();
			$cartProductClassIDs = $objCartSession->getAllProductClassID(1);		//実商品
			for ($i = 0; $i < count($cartProductClassIDs); $i++) {
				$tmp = $objQuery->getOne($sql, array($pref_id[0], $deliv_id, $cartProductClassIDs[$i]));
				if ($tmp > $result){
					//該当配送先の送料を、商品の送料に設定
					$result = $tmp;
				}
			}
		}
		return $result;
    }
    /*## 配送ランク ADD END ##*/
}

?>
