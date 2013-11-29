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

// {{{ requires
require_once CLASS_REALDIR . 'helper/SC_Helper_Purchase.php';

/**
 * 商品購入関連のヘルパークラス(拡張).
 *
 * LC_Helper_Purchase をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author Kentaro Ohkouchi
 * @version $Id: SC_Helper_Purchase_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class SC_Helper_Purchase_Ex extends SC_Helper_Purchase {
    /**
     * 会員情報を受注情報にコピーする.
     *
     * ユーザーがログインしていない場合は何もしない.
     * 会員情報を $dest の order_* へコピーする.
     * customer_id は強制的にコピーされる.
     *
     * @param array $dest コピー先の配列
     * @param SC_Customer $objCustomer SC_Customer インスタンス
     * @param string $prefix コピー先の接頭辞. デフォルト order
     * @param array $keys コピー対象のキー
     * @return void
     */
    function copyFromCustomer(&$dest, &$objCustomer, $prefix = 'order',
                              $keys = array('name01', 'name02', 'kana01', 'kana02',
                                            'sex', 'zip01', 'zip02', 'pref',
                                            'addr01', 'addr02',
                                            'tel01', 'tel02', 'tel03', 'job',
                                            'birth', 'email')) {
		/*## 顧客法人管理 ADD BEGIN ##*/
		if(constant("USE_CUSTOMER_COMPANY") === true){
			$keys[] = "company";
			$keys[] = "company_kana";
			$keys[] = "company_department";
		}
		/*## 顧客法人管理 ADD END ##*/
		/*## 顧客お届け先FAX ADD BEGIN ##*/
		if(USE_OTHER_DELIV_FAX === true){
			$keys[] = "fax01";
			$keys[] = "fax02";
			$keys[] = "fax03";
		}
		/*## 顧客お届け先FAX ADD END ##*/	
			
        if ($objCustomer->isLoginSuccess(true)) {

            foreach ($keys as $key) {
                if (in_array($key, $keys)) {
                    $dest[$prefix . '_' . $key] = $objCustomer->getValue($key);
                }
            }

            if (Net_UserAgent_Mobile::isMobile()
                && in_array('email', $keys)) {
                $email_mobile = $objCustomer->getValue('email_mobile');
                if (empty($email_mobile)) {
                    $dest[$prefix . '_email'] = $objCustomer->getValue('email');
                } else {
                    $dest[$prefix . '_email'] = $email_mobile;
                }
            }

            $dest['customer_id'] = $objCustomer->getValue('customer_id');
            $dest['update_date'] = 'CURRENT_TIMESTAMP';
        }
    }

    /**
     * 受注情報を配送情報にコピーする.
     *
     * 受注情報($src)を $dest の order_* へコピーする.
     *
     * TODO 汎用的にして SC_Utils へ移動
     *
     * @param array $dest コピー先の配列
     * @param array $src コピー元の配列
     * @param array $keys コピー対象のキー
     * @param string $prefix コピー先の接頭辞. デフォルト shipping
     * @param string $src_prefix コピー元の接頭辞. デフォルト order
     * @return void
     */
    function copyFromOrder(&$dest, $src,
                           $prefix = 'shipping', $src_prefix = 'order',
                           $keys = array('name01', 'name02', 'kana01', 'kana02',
                                         'sex', 'zip01', 'zip02', 'pref',
                                         'addr01', 'addr02',
                                         'tel01', 'tel02', 'tel03')) {
		/*## 顧客法人管理 ADD BEGIN ##*/
		if(constant("USE_CUSTOMER_COMPANY") === true){
			$keys[] = "company";
			$keys[] = "company_kana";
			$keys[] = "company_department";
		}
		/*## 顧客法人管理 ADD END ##*/
		/*## 顧客お届け先FAX ADD BEGIN ##*/
		if(USE_OTHER_DELIV_FAX === true){
			$keys[] = "fax01";
			$keys[] = "fax02";
			$keys[] = "fax03";
		}
		/*## 顧客お届け先FAX ADD END ##*/		
				                           
        if (!SC_Utils_Ex::isBlank($prefix)) {
            $prefix = $prefix . '_';
        }
        if (!SC_Utils_Ex::isBlank($src_prefix)) {
            $src_prefix = $src_prefix . '_';
        }
        foreach ($keys as $key) {
            if (in_array($key, $keys)) {
                $dest[$prefix . $key] = $src[$src_prefix . $key];
            }
        }
    }	
    
   /**
     * 受注登録を完了する.
     *
     * 引数の受注情報を受注テーブル及び受注詳細テーブルに登録する.
     * 登録後, 受注一時テーブルに削除フラグを立てる.
     *
     * @param array $orderParams 登録する受注情報の配列
     * @param SC_CartSession $objCartSession カート情報のインスタンス
     * @param integer $cartKey 登録を行うカート情報のキー
     * @param integer 受注ID
     */
    function registerOrderComplete($orderParams, &$objCartSession, $cartKey) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // 不要な変数を unset
        $unsets = array('mailmaga_flg', 'deliv_check', 'point_check', 'password',
                        'reminder', 'reminder_answer', 'mail_flag', 'session');
        foreach ($unsets as $unset) {
            unset($orderParams[$unset]);
        }

        // 対応状況の指定が無い場合は新規受付
        if(SC_Utils_Ex::isBlank($orderParams['status'])) {
            $orderParams['status'] = ORDER_NEW;
        }

        $orderParams['create_date'] = 'CURRENT_TIMESTAMP';
        $orderParams['update_date'] = 'CURRENT_TIMESTAMP';

        $this->registerOrder($orderParams['order_id'], $orderParams);

        // 詳細情報を取得
        $cartItems = $objCartSession->getCartList($cartKey);

        // 詳細情報を生成
        $objProduct = new SC_Product_Ex();
        $i = 0;
        foreach ($cartItems as $item) {
            $p =& $item['productsClass'];
            $arrDetail[$i]['order_id'] = $orderParams['order_id'];
            $arrDetail[$i]['product_id'] = $p['product_id'];
            $arrDetail[$i]['product_class_id'] = $p['product_class_id'];
            $arrDetail[$i]['product_name'] = $p['name'];
            $arrDetail[$i]['product_code'] = $p['product_code'];
            $arrDetail[$i]['classcategory_name1'] = $p['classcategory_name1'];
            $arrDetail[$i]['classcategory_name2'] = $p['classcategory_name2'];
            $arrDetail[$i]['point_rate'] = $item['point_rate'];
            $arrDetail[$i]['price'] = $item['price'];
            $arrDetail[$i]['quantity'] = $item['quantity'];
			
			/*## 商品非課税 ADD BEGIN ##*/
            if(USE_TAXFREE_PRODUCT === true){
            	$arrDetail[$i]['taxfree'] = $p['taxfree'];
            }
            /*## 商品非課税 ADD END ##*/
            
            /*## 追加規格 ADD BEGIN ##*/
            if(USE_EXTRA_CLASS === true){
            	$arrDetail[$i]['extra_info'] = serialize($item['extra_info']);
            }
            /*## 追加規格 ADD END ##*/
            
            // 在庫の減少処理
            if (!$objProduct->reduceStock($p['product_class_id'], $item['quantity'])) {
                $objQuery->rollback();
                SC_Utils_Ex::sfDispSiteError(SOLD_OUT, "", true);
            }
            $i++;
        }
        $this->registerOrderDetail($orderParams['order_id'], $arrDetail);

        $objQuery->update("dtb_order_temp", array('del_flg' => 1),
                          "order_temp_id = ?",
                          array(SC_SiteSession_Ex::getUniqId()));
        
        
        
        return $orderParams['order_id'];
    }    
    
    /**
     * 受注詳細を取得する.
     *
     * @param integer $order_id 受注ID
     * @param boolean $has_order_status 対応状況, 入金日も含める場合 true
     * @return array 受注詳細の配列
     */
    function getOrderDetail($order_id, $has_order_status = true) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $dbFactory  = SC_DB_DBFactory_Ex::getInstance();
        $col = <<< __EOS__
            T3.product_id,
            T3.product_class_id as product_class_id,
            T3.product_type_id AS product_type_id,
            T2.product_code,
            T2.product_name,
            T2.classcategory_name1 AS classcategory_name1,
            T2.classcategory_name2 AS classcategory_name2,
            T2.price,
            T2.quantity,
            T2.point_rate,
__EOS__;
/*## 追加規格 ADD BEGIN ##*/
        if(USE_EXTRA_CLASS === true){
        	$col .= "extra_info,";
        }
/*## 追加規格 ADD END ##*/
/*## 商品非課税 ADD BEGIN ##*/
        if(USE_TAXFREE_PRODUCT === true){
        	$col .= "taxfree,";
        }
/*## 商品非課税 ADD END ##*/
        if ($has_order_status) {
            $col .= 'T1.status AS status, T1.payment_date AS payment_date,';

        }
        $col .= <<< __EOS__

            CASE WHEN EXISTS(
                    SELECT * FROM dtb_products
                     WHERE product_id = T3.product_id
                       AND del_flg = 0
                       AND status = 1)
                 THEN '1' ELSE '0'
                  END AS enable,
__EOS__;
        $col .= $dbFactory->getDownloadableDaysWhereSql('T1') . ' AS effective';
        $from = <<< __EOS__
                      dtb_order T1
                 JOIN dtb_order_detail T2
                   ON T1.order_id = T2.order_id
            LEFT JOIN dtb_products_class T3
                   ON T2.product_class_id = T3.product_class_id
__EOS__;
        $objQuery->setOrder('T2.order_detail_id');
        return $objQuery->select($col, $from, 'T1.order_id = ?', array($order_id));
    }    
    
	/*## 商品支払方法指定 ADD BEGIN ##*/
    /**
     * 全ての商品に指定した支払方法から購入金額に応じた支払方法を取得する.
     *
     * @param integer $total 購入金額
     * @param integer $deliv_id 商品ID
     * @return array 購入金額に応じた支払方法の配列
     */
    function getPaymentsByProduct($total, $productIds, $deliv_id) {
        $objProduct = new SC_Product_Ex();
		$arrProductPaymentIds = $objProduct->getProductPayment($productIds);
		$arrDelivPaymentIds = $this->getPayments($deliv_id);

		// 商品指定支払方法がない場合、配送先の支払方法を利用する
		foreach($arrProductPaymentIds as $pdctId => $paymentIds){
			if(!count($paymentIds)){
				$arrProductPaymentIds[$pdctId] = $arrDelivPaymentIds;
			}
		}
		
		// 全ての商品が指定した支払方法を抽出する
		$arrPaymentIds = array();
		foreach($arrProductPaymentIds as $pdctId => $paymentIds){
			if($pdctId == $productIds[0]){
				$arrPaymentIds = $paymentIds;
			}
			else{
				$arrPaymentIds = array_intersect($arrPaymentIds, $paymentIds);
			}
		}

        if (SC_Utils_Ex::isBlank($arrPaymentIds)) {
            return array();
        }
        $arrPaymentIds = array_values($arrPaymentIds);

        $objQuery =& SC_Query_Ex::getSingletonInstance();

    	// 削除されていない支払方法を取得
        $where = 'del_flg = 0 AND payment_id IN (' . SC_Utils_Ex::repeatStrWithSeparator('?', count($arrPaymentIds)) . ')';
        $objQuery->setOrder('rank DESC');
        $payments = $objQuery->select('payment_id, payment_method, rule_max, upper_rule, note, payment_image, charge', 'dtb_payment', $where, $arrPaymentIds);
        $arrPayment = array();
        foreach ($payments as $data) {
            // 下限と上限が設定されている
            if (strlen($data['rule_max']) != 0 && strlen($data['upper_rule']) != 0) {
                if ($data['rule_max'] <= $total && $data['upper_rule'] >= $total) {
                    $arrPayment[] = $data;
                }
            }
            // 下限のみ設定されている
            elseif (strlen($data['rule_max']) != 0) {
                if ($data['rule_max'] <= $total) {
                    $arrPayment[] = $data;
                }
            }
            // 上限のみ設定されている
            elseif (strlen($data['upper_rule']) != 0) {
                if ($data['upper_rule'] >= $total) {
                    $arrPayment[] = $data;
                }
            }
            // いずれも設定なし
            else {
                $arrPayment[] = $data;
            }
        } 
        return $arrPayment;
    }
    /*## 商品支払方法指定 ADD END ##*/
    
    
    /*## 商品配送方法指定 ADD BEGIN ##*/
    /**
     * 商品ID から配送業者を取得する.
     *
     * @param integer $product_type_id 商品種別ID
     * @param integer $productIds 商品ID
     * @return array 配送業者の配列
     */
    function getDelivByProduct($product_type_id, $productIds) {
    	$arrDefaultDeliv = parent::getDeliv($product_type_id);
    	
    	$arrDefaultDelivIds = array();
    	$arrDefaultDelivIndex = array();
    	foreach($arrDefaultDeliv as $deliv){
    		$arrDefaultDelivIds[] = $deliv["deliv_id"];
    		$arrDefaultDelivIndex[$deliv["deliv_id"]] = $deliv;
    	}
    	
    	$objProduct = new SC_Product_Ex();
    	$arrProductDelivIds = $objProduct->getProductDeliv($productIds);
    	    	
    	// 商品指定配送方法がない場合、商品種別で取る配送方法を利用する
		foreach($arrProductDelivIds as $pdctId => $delivIds){
			if(!count($delivIds)){
				$arrProductDelivIds[$pdctId] = $arrDefaultDelivIds;
			}
		}
		
		// 全ての商品が指定した配送方法を抽出する
		$arrDelivIds = array();
		foreach($arrProductDelivIds as $pdctId => $delivIds){
			if($pdctId == $productIds[0]){
				$arrDelivIds = $delivIds;
			}
			else{
				$arrDelivIds = array_intersect($arrDelivIds, $delivIds);
			}
		}
		
		$arrDeliv = array();
		foreach($arrDelivIds as $delivId){
			$arrDeliv[] = $arrDefaultDelivIndex[$delivId];
		}
		
		return $arrDeliv;
     }
    /*## 商品配送方法指定 ADD END ##*/
}
?>
