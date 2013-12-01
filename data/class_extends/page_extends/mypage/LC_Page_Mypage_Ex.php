<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
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
require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage.php';

/**
 * MyPage のページクラス(拡張).
 *
 * LC_Page_MyPage をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Mypage_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Mypage_Ex extends LC_Page_Mypage {

	// }}}
	// {{{ functions

	/**
	 * Page を初期化する.
	 *
	 * @return void
	 */
	function init() {
		parent::init();
		
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrSTATUS = $masterData->getMasterData('mtb_status');
        $this->arrSTATUS_IMAGE = $masterData->getMasterData('mtb_status_image');
	}

	/**
	 * Page のプロセス.
	 *
	 * @return void
	 */
	function process() {
		parent::process();
	}

	/**
	 * デストラクタ.
	 *
	 * @return void
	 */
	function destroy() {
		parent::destroy();
	}

	/**
	 * Page のAction.
	 *
	 * @return void
	 */
	function action() {
		parent::action();
		 
		$objCustomer = new SC_Customer_Ex();
		$customer_id = $objCustomer->getvalue('customer_id');

		/*## 最近の受注履歴 ADD BEGIN ##*/
		if(defined("LATEST_ORDER_MAX") && LATEST_ORDER_MAX > 0){
			$this->arrLatestOrder = $this->lfGetLatestOrderHistory($customer_id);
		}
		/*## 最近の受注履歴 ADD END ##*/

		/*## 最近のお気に入り ADD BEGIN ##*/
		if(defined("LATEST_FAVORITE_PRODUCT_MAX") &&LATEST_FAVORITE_PRODUCT_MAX > 0){
			$this->arrLatestFavorite = $this->lfGetFavoriteProduct($customer_id);
		}
		/*## 最近のお気に入り ADD END ##*/
	
		/*## 会員新着情報 ADD BEGIN ##*/
		$this->arrNews = $this->lfGetCustomerNews();
		/*## 会員新着情報 ADD END ##*/
		
		/*## 最近のお気に入り商品を購入 ADD BEGIN ##*/
		switch($this->getMode()){
			case "cart":
				//カート処理
				$this->arrForm = $_POST;
				$target_product_id = intval($this->arrForm['product_id']);
				if ( $target_product_id > 0) {
					// 商品IDの正当性チェック
					if (!SC_Utils_Ex::sfIsInt($this->arrForm['product_id'])
					|| !SC_Helper_DB_Ex::sfIsRecord("dtb_products", "product_id", $this->arrForm['product_id'], "del_flg = 0 AND status = 1")) {
						SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
					}

					// 入力内容のチェック
					$arrErr = $this->lfCheckError($target_product_id, $this->arrForm);
					if (empty($arrErr)) {
						$this->lfAddCart($this->arrForm, $_SERVER['HTTP_REFERER']);
						SC_Response_Ex::sendRedirect(CART_URLPATH);
						exit;
					}
				}
				// カート「戻るボタン」用に保持
				$netURL = new Net_URL();
				//該当メソッドが無いため、$_SESSIONに直接セット
				$_SESSION['cart_referer_url'] = $netURL->getURL();
				break;
		}
		/*## 最近のお気に入り商品を購入 ADD END ##*/
	}

	/*## 最近の受注履歴 ADD BEGIN ##*/
	/**
	 * 最近の受注履歴を返す
	 *
	 * @param mixed $customer_id
	 * @param mixed $startno 0以上の場合は受注履歴を返却する -1の場合は件数を返す
	 * @access private
	 * @return void
	 */
	function lfGetLatestOrderHistory($customer_id) {
		$objQuery   = SC_Query_Ex::getSingletonInstance();
		$objPurchase = new SC_Helper_Purchase_Ex();

		if(!defined("LATEST_ORDER_MAX") || LATEST_ORDER_MAX == false){
			return 0;
		}

		$col        = '*';
		$from       = 'dtb_order';
		$where      = 'del_flg = 0 AND customer_id = ?';
		$arrWhereVal = array($customer_id);
		$order      = 'order_id DESC';

		$objQuery->setLimitOffset(LATEST_ORDER_MAX, 0);
		// 表示順序
		$objQuery->setOrder($order);

		//購入履歴の取得
		$arrOrders = $objQuery->select($col, $from, $where, $arrWhereVal);

		$objQuery =& SC_Query_Ex::getSingletonInstance();
		
		if(is_array($arrOrders)){
			foreach($arrOrders as $no => $order){
				$arrShipping = $objPurchase->getShippings($order["order_id"]);
				$arrOrders[$no]["isMultiple"] = count($arrShipping) > 1;
				$arrOrders[$no]["shipping"] = $arrShipping;

				// 受注商品明細の取得
				$arrOrders[$no]["detail"] = $objPurchase->getOrderDetail($order["order_id"]);
				
				// 購入履歴商品に画像をセット
				foreach ($arrOrders[$no]["detail"] as $i => $arrOrderDetail) {
					$objQuery =& SC_Query_Ex::getSingletonInstance();
					$arrProduct = $objQuery->select('main_list_image', 'dtb_products', 'product_id = ?', array($arrOrderDetail['product_id']));
					$arrOrderDetails[$i]['main_list_image'] = $arrProduct[0]['main_list_image'];
				}
			}
		}
		
		return $arrOrders;
	}
	/*## 最近の受注履歴 ADD END ##*/

	/*## 最近のお気に入り ADD BEGIN ##*/
	/**
	 * 最近のお気に入りを取得する
	 *
	 * @param mixed $customer_id
	 * @param mixed $objPage
	 * @access private
	 * @return array お気に入り商品一覧
	 */
	function lfGetFavoriteProduct($customer_id) {
		$objQuery       = SC_Query_Ex::getSingletonInstance();
		$objProduct     = new SC_Product_Ex();

		if(!defined("LATEST_FAVORITE_PRODUCT_MAX") || LATEST_FAVORITE_PRODUCT_MAX == false){
			return 0;
		}

		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$objQuery->setLimitOffset(LATEST_FAVORITE_PRODUCT_MAX, 0);
		$objQuery->setOrder("create_date DESC");

		$arrFavoriteIds = $objQuery->getCol("product_id", "dtb_customer_favorite_products", "customer_id = ?", array($customer_id));

		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$arrProducts = $objProduct->getListByProductIds($objQuery, $arrFavoriteIds);
		$arrPdctStatus = $objProduct->getProductStatus($arrFavoriteIds);
		
		$arrProductClass = $objProduct->getProductsClassByProductIds($arrFavoriteIds);
		$arProductClassId = array();
		foreach($arrProductClass as $productClass){
			if($productClass["classcategory_id1"] == 0 && $productClass["classcategory_id2"] == 0){
				$arProductClassId[$productClass["product_id"]] = $productClass["product_class_id"];
			}else{
				$arProductClassId[$productClass["product_id"]] = -1;
			}
		}
		
		//取得している並び順で並び替え
		$arrProductsList = array();
		foreach ($arrFavoriteIds as $product_id) {
			$arrProducts[$product_id]["product_status"] = $arrPdctStatus[$product_id];
			$arrProducts[$product_id]["product_class_id"] =  $arProductClassId[$product_id];
			$arrProductsList[] = $arrProducts[$product_id];
		}
		return $arrProductsList;
	}
	/*## 最近のお気に入り ADD END ##*/
	
	/*## 最近のお気に入り商品を購入 ADD BEGIN ##*/
    /* 入力内容のチェック */
    function lfCheckError($product_id, &$arrForm, $tpl_classcat_find1, $tpl_classcat_find2) {

        // 入力データを渡す。
        $objErr = new SC_CheckError_Ex($arrForm);
        // 規格のある商品はここで購入できないため、規格をチェックしない
        $objErr->doFunc(array('商品規格ID', 'product_class_id', INT_LEN), array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objErr->doFunc(array('数量', 'quantity', INT_LEN), array('EXIST_CHECK', 'ZERO_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));

        return $objErr->arrErr;
    }
    
    /**
     * カートに商品を追加
     *
     * @return void
     */
    function lfAddCart($arrForm, $referer) {
    	$objQuery = SC_Query_Ex::getSingletonInstance();

        $product_class_id = $arrForm['product_class_id'];
        $objCartSess = new SC_CartSession_Ex();
        $objCartSess->addProduct($product_class_id, $arrForm['quantity']);
    }
    
    /*## 最近のお気に入り商品を購入 ADD END ##*/
    
    /**
     * 新着情報を取得する.
     *
     * @return array $arrNewsList 新着情報の配列を返す
     */
    function lfGetCustomerNews() {
    	$objQuery = SC_Query_Ex::getSingletonInstance();
    	
        $objQuery->setOrder('rank DESC ');
        $arrNewsList = $objQuery->select('* , cast(news_date as date) as news_date_disp', 'dtb_customer_news' ,'del_flg = 0');

        return $arrNewsList;
    }
}
