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
require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Deliv.php';

/**
 * お届け先の指定 のページクラス(拡張).
 *
 * LC_Page_Shopping_Deliv をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Shopping_Deliv_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Shopping_Deliv_Ex extends LC_Page_Shopping_Deliv {

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
     * Page のプロセス.
     *
     * @return void
     */
    function action() {

        $objSiteSess = new SC_SiteSession_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objFormParam = new SC_FormParam_Ex();
        $objAddress = new SC_Helper_Address_Ex();

        $this->tpl_uniqid = $objSiteSess->getUniqId();
        $objPurchase->verifyChangeCart($this->tpl_uniqid, $objCartSess);

        $this->cartKey = $objCartSess->getKey();

        // ログインチェック
        if (!$objCustomer->isLoginSuccess(true)) {
            SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
        }

        // ダウンロード商品の場合は、支払方法画面に転送
        if ($this->cartKey == PRODUCT_TYPE_DOWNLOAD) {
            $objPurchase->copyFromCustomer($sqlval, $objCustomer, 'shipping');
            $objPurchase->saveShippingTemp($sqlval);
            $objPurchase->saveOrderTemp($this->tpl_uniqid, $sqlval, $objCustomer);
            $objSiteSess->setRegistFlag();

            SC_Response_Ex::sendRedirect('payment.php');
            SC_Response_Ex::actionExit();
        }

        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $arrErr = $objFormParam->checkError();
        if (!SC_Utils_Ex::isBlank($arrErr)) {
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, '', true);
            SC_Response_Ex::actionExit();
        }

        $arrForm = $objFormParam->getHashArray();

        switch ($this->getMode()) {
            // 削除
            case 'delete':
                $objAddress->deleteAddress($arrForm['other_deliv_id']);
                break;

            // 会員登録住所に送る
            case 'customer_addr':
                $objPurchase->unsetShippingTemp();

                $shipping_id = $arrForm['deliv_check'] == -1 ? 0 : $arrForm['deliv_check'];
                $success = $this->registerDeliv($shipping_id, $this->tpl_uniqid, $objPurchase, $objCustomer, $objAddress);
                if (!$success) {
                    SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, '', true);
                }

                $objPurchase->setShipmentItemTempForSole($objCartSess, $shipping_id);
                $objSiteSess->setRegistFlag();


                SC_Response_Ex::sendRedirect(SHOPPING_PAYMENT_URLPATH);
                SC_Response_Ex::actionExit();
                break;

            // 前のページに戻る
            case 'return':

                // 確認ページへ移動
                SC_Response_Ex::sendRedirect(CART_URLPATH);
                SC_Response_Ex::actionExit();
                break;

            // お届け先複数指定
            case 'multiple':
                // 複数配送先指定が無効な場合はエラー
                if (USE_MULTIPLE_SHIPPING === false) {
                    SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, '', true);
                    SC_Response_Ex::actionExit();
                }

                SC_Response_Ex::sendRedirect('multiple.php');
                SC_Response_Ex::actionExit();
                break;

            default:
                // 配送IDの取得
                $shippingData = $objPurchase->getShippingTemp();
                $arrShippingId = array_keys($shippingData);
                if (isset($arrShippingId[0])) {
                    $this->arrForm['deliv_check']['value'] = $arrShippingId[0] == 0 ? -1 : $arrShippingId[0];
                }
                break;
        }

        // 登録済み住所を取得
        $addr = array(
            array(
                'other_deliv_id'    => NULL,
                'customer_id'       => $objCustomer->getValue('customer_id'),
                'name01'            => $objCustomer->getValue('name01'),
                'name02'            => $objCustomer->getValue('name02'),
                'kana01'            => $objCustomer->getValue('kana01'),
                'kana02'            => $objCustomer->getValue('kana02'),
                'zip01'             => $objCustomer->getValue('zip01'),
                'zip02'             => $objCustomer->getValue('zip02'),
                'pref'              => $objCustomer->getValue('pref'),
                'addr01'            => $objCustomer->getValue('addr01'),
                'addr02'            => $objCustomer->getValue('addr02'),
                'tel01'             => $objCustomer->getValue('tel01'),
                'tel02'             => $objCustomer->getValue('tel02'),
                'tel03'             => $objCustomer->getValue('tel03'),
            )
        );
		
		/*## 顧客お届け先FAX ADD BEGIN ##*/
        if(USE_OTHER_DELIV_FAX === true){
        	$addr[0]['fax01'] = $objCustomer->getValue('fax01');
        	$addr[0]['fax02'] = $objCustomer->getValue('fax02');
        	$addr[0]['fax03'] = $objCustomer->getValue('fax03');
        }
        /*## 顧客お届け先FAX ADD END ##*/
        
        $this->arrAddr = array_merge($addr, $objAddress->getList($objCustomer->getValue('customer_id')));
        $this->tpl_addrmax = count($this->arrAddr);


    }
}
