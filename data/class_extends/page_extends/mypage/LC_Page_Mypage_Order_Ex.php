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
require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_Order.php';

/**
 * 受注履歴からカート遷移 のページクラス(拡張).
 *
 * LC_Page_Mypage_Order をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Mypage_Order_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Mypage_Order_Ex extends LC_Page_Mypage_Order {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
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

        //受注詳細データの取得
        $arrOrderDetail = $this->lfGetOrderDetail($_POST['order_id']);

        //ログインしていない、またはDBに情報が無い場合
        if (empty($arrOrderDetail)) {
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }

        switch($this->getMode()){
        	case "pdf":
        		$this->createPdf($arrOrderDetail, $_POST['order_id']);
        		break;
        	case "addcart":
        	default:
        		$this->lfAddCartProducts($arrOrderDetail);
        		SC_Response_Ex::sendRedirect(CART_URLPATH);
        		break;
        }
    }
    
   // 受注詳細データの取得
    function lfGetOrderDetail($order_id) {
        $objQuery       = SC_Query_Ex::getSingletonInstance();

        $objCustomer    = new SC_Customer_Ex();
        //customer_idを検証
        $customer_id    = $objCustomer->getValue("customer_id");
        $order_count    = $objQuery->count("dtb_order", "order_id = ? and customer_id = ?", array($order_id, $customer_id));
        if ($order_count != 1) return array();

        $col    = "product_class_id, quantity";
        /*## 追加規格 ADD BEGIN ##*/
        if(USE_EXTRA_CLASS === true){
        	$col .= ", extra_info";
        }
        /*## 追加規格 ADD END ##*/
        $table  = "dtb_order_detail LEFT JOIN dtb_products_class USING(product_class_id)";
        $where  = "order_id = ?";
        $objQuery->setOrder("order_detail_id");
        $arrOrderDetail = $objQuery->select($col, $table, $where, array($order_id));
        return $arrOrderDetail;
    }

    // 商品をカートに追加
    function lfAddCartProducts($arrOrderDetail) {

        $objCartSess = new SC_CartSession_Ex();
        foreach($arrOrderDetail as $order_row) {
        	/*## 追加規格 ## MDF BEGIN*/
        	if(USE_EXTRA_CLASS === true){
        		$extra_info = unserialize($order_row['extra_info']);
        		$objCartSess->addProduct($order_row['product_class_id'],
        							$order_row['quantity'], 
        							$extra_info);
        	}else{
        		$objCartSess->addProduct($order_row['product_class_id'],
        							$order_row['quantity']);        		
        	}
        	/*## 追加規格 ## MDF END*/
        }
    }

    /**
     *
     * PDFの作成
     * @param $arrOrderDetail
     */
    function createPdf($arrOrderDetail, $order_id="") {
    	$objFpdf = new SC_Fpdf_Ex(1, "納品書");
    	
    	$arrPdfData = array();
    	$arrPdfData["detail"] = $arrOrderDetail;
    	$arrPdfData["order_id"] = $order_id;
    	
    	$objFpdf->setData($arrPdfData);
    	$objFpdf->createPdf();
    	return true;
    }
}
?>
