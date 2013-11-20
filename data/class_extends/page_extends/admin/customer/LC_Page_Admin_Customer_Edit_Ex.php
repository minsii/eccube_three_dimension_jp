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
require_once CLASS_REALDIR . 'pages/admin/customer/LC_Page_Admin_Customer_Edit.php';

/**
 * 会員情報修正 のページクラス(拡張).
 *
 * LC_Page_Admin_Customer_Edit をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Customer_Edit_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Admin_Customer_Edit_Ex extends LC_Page_Admin_Customer_Edit {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        
        /*## 会員登録項目カスタマイズ ADD BEGIN ##*/
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrCAMPANY_TYPE = $masterData->getMasterData('mtb_company_type');
        /*## 会員登録項目カスタマイズ ADD END ##*/
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
     * Page のアクション.
     *
     * @return void
     */
    function action() {

        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // 検索引き継ぎ用パラメーター管理クラス
        $objFormSearchParam = new SC_FormParam_Ex();

        // モードによる処理切り替え
        switch ($this->getMode()) {
            case 'edit_search':
                // 検索引き継ぎ用パラメーター処理
                $this->lfInitSearchParam($objFormSearchParam);
                $objFormSearchParam->setParam($_REQUEST);
                $this->arrErr = $this->lfCheckErrorSearchParam($objFormSearchParam);
                $this->arrSearchData = $objFormSearchParam->getSearchArray();
                if (!SC_Utils_Ex::isBlank($this->arrErr)) {
                    return;
                }
                // 指定会員の情報をセット
                $this->arrForm = SC_Helper_Customer_Ex::sfGetCustomerData($objFormSearchParam->getValue('edit_customer_id'), true);
                // 購入履歴情報の取得
                list($this->tpl_linemax, $this->arrPurchaseHistory, $this->objNavi) = $this->lfPurchaseHistory($objFormSearchParam->getValue('edit_customer_id'));
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                $this->arrPagenavi['mode'] = 'return';
                $this->tpl_pageno = '0';
                break;
            case 'confirm':
                // パラメーター処理
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                // 入力パラメーターチェック
                $this->arrErr = $this->lfCheckError($objFormParam);
                $this->arrForm = $objFormParam->getHashArray();
                // 検索引き継ぎ用パラメーター処理
                $this->lfInitSearchParam($objFormSearchParam);
                $objFormSearchParam->setParam($objFormParam->getValue('search_data'));
                $this->arrSearchErr = $this->lfCheckErrorSearchParam($objFormSearchParam);
                $this->arrSearchData = $objFormSearchParam->getSearchArray();
                if (!SC_Utils_Ex::isBlank($this->arrErr) or !SC_Utils_Ex::isBlank($this->arrSearchErr)) {
                    return;
                }
                // 確認画面テンプレートに切り替え
                $this->tpl_mainpage = 'customer/edit_confirm.tpl';
                break;
            case 'return':
                // パラメーター処理
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                // 入力パラメーターチェック
                $this->arrErr = $this->lfCheckError($objFormParam);
                $this->arrForm = $objFormParam->getHashArray();
                // 検索引き継ぎ用パラメーター処理
                $this->lfInitSearchParam($objFormSearchParam);
                $objFormSearchParam->setParam($objFormParam->getValue('search_data'));
                $this->arrSearchErr = $this->lfCheckErrorSearchParam($objFormSearchParam);
                $this->arrSearchData = $objFormSearchParam->getSearchArray();
                if (!SC_Utils_Ex::isBlank($this->arrErr) or !SC_Utils_Ex::isBlank($this->arrSearchErr)) {
                    return;
                }
                // 購入履歴情報の取得
                list($this->tpl_linemax, $this->arrPurchaseHistory, $this->objNavi) = $this->lfPurchaseHistory($objFormParam->getValue('customer_id'), $objFormParam->getValue('search_pageno'));
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                $this->arrPagenavi['mode'] = 'return';
                $this->tpl_pageno = $objFormParam->getValue('search_pageno');

                break;
            case 'complete':
                // 登録・保存処理
                // パラメーター処理
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                // 入力パラメーターチェック
                $this->arrErr = $this->lfCheckError($objFormParam);
                $this->arrForm = $objFormParam->getHashArray();
                // 検索引き継ぎ用パラメーター処理
                $this->lfInitSearchParam($objFormSearchParam);
                $objFormSearchParam->setParam($objFormParam->getValue('search_data'));
                $this->arrSearchErr = $this->lfCheckErrorSearchParam($objFormSearchParam);
                $this->arrSearchData = $objFormSearchParam->getSearchArray();
                if (!SC_Utils_Ex::isBlank($this->arrErr) or !SC_Utils_Ex::isBlank($this->arrSearchErr)) {
                    return;
                }

                /*## 本会員承認 ADD BEGIN ##*/
                if(USE_ADMIN_CUSTOMER_APPROVAL === true){
                	$old_status = SC_Helper_Customer_Ex::sfGetCustomerStatus($this->arrForm['customer_id']);
                }
                /*## 本会員承認 ADD END ##*/

                $this->lfRegistData($objFormParam);
                $this->tpl_mainpage = 'customer/edit_complete.tpl';
                
                /*## 本会員承認 ADD BEGIN ##*/
                if(USE_ADMIN_CUSTOMER_APPROVAL === true){
                	$new_status = $this->arrForm['status'];
                	if($new_status == "2" && $old_status == "1"){
                		// 本会員登録メール送信
                		$this->lfSendRegistMail($this->arrForm);
                	}
                }
                /*## 本会員承認 ADD END ##*/
                
                
                break;
            case 'complete_return':
                // 入力パラメーターチェック
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                // 検索引き継ぎ用パラメーター処理
                $this->lfInitSearchParam($objFormSearchParam);
                $objFormSearchParam->setParam($objFormParam->getValue('search_data'));
                $this->arrSearchErr = $this->lfCheckErrorSearchParam($objFormSearchParam);
                $this->arrSearchData = $objFormSearchParam->getSearchArray();
                if (!SC_Utils_Ex::isBlank($this->arrSearchErr)) {
                    return;
                }
            default:
                $this->lfInitParam($objFormParam);
                $this->arrForm = $objFormParam->getHashArray();
                break;
        }

    }

    /**
     * 登録処理
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return array エラー配列
     */
    function lfRegistData(&$objFormParam) {
        $objQuery   =& SC_Query_Ex::getSingletonInstance();
        // 登録用データ取得
        $arrData = $objFormParam->getDbArray();
        // 足りないものを作る
        if (!SC_Utils_Ex::isBlank($objFormParam->getValue('year'))) {
            $arrData['birth'] = $objFormParam->getValue('year') . '/'
                            . $objFormParam->getValue('month') . '/'
                            . $objFormParam->getValue('day')
                            . ' 00:00:00';
        }
        
        /*## 会員登録項目カスタマイズ ADD BEGIN ##*/
        if (!SC_Utils_Ex::isBlank($objFormParam->getValue('company_certified_date_year'))) {
        	$arrData['company_certified_date'] = SC_Utils_Ex::sfGetTimestamp(
        							$objFormParam->getValue('company_certified_date_year'), 
        							$objFormParam->getValue('company_certified_date_month'), 1);
        }
        if (!SC_Utils_Ex::isBlank($objFormParam->getValue('company_open_date_year'))) {
        	$arrData['company_open_date'] = SC_Utils_Ex::sfGetTimestamp(
        							$objFormParam->getValue('company_open_date_year'), 
        							$objFormParam->getValue('company_open_date_month'), 1);
        }
        /*## 会員登録項目カスタマイズ ADD END ##*/
        
        if (!is_numeric($arrData['customer_id'])) {
            $arrData['secret_key'] = SC_Utils_Ex::sfGetUniqRandomId('r');
        } else {
            $arrOldCustomerData = SC_Helper_Customer_Ex::sfGetCustomerData($arrData['customer_id']);
            if ($arrOldCustomerData['status'] != $arrData['status']) {
                $arrData['secret_key'] = SC_Utils_Ex::sfGetUniqRandomId('r');
            }
        }
        return SC_Helper_Customer_Ex::sfEditCustomerData($arrData, $arrData['customer_id']);
    }
    
    /*## 本会員承認 ADD BEGIN ##*/
    /**
     * 正会員登録完了メール送信
     *
     * @param mixed $registSecretKey
     * @access private
     * @return void
     */
    function lfSendRegistMail($data) {
        $objQuery       = SC_Query_Ex::getSingletonInstance();
        $objCustomer    = new SC_Customer_Ex();
        $objHelperMail  = new SC_Helper_Mail_Ex();
        $objHelperMail->setPage($this);
        $CONF           = SC_Helper_DB_Ex::sfGetBasisData();
        
		//--　メール送信
        $objMailText    = new SC_SiteView_Ex();
        $objMailText->setPage($this);
        $objMailText->assign('CONF', $CONF);
        $objMailText->assign('name01', $data['name01']);
        $objMailText->assign('name02', $data['name02']);
        $toCustomerMail = $objMailText->fetch('mail_templates/customer_regist_mail.tpl');
        $subject = $objHelperMail->sfMakesubject('本会員登録が完了しました。');
        $objMail = new SC_SendMail_Ex();

        $objMail->setItem(
                              ''                                // 宛先
                            , $subject                  // サブジェクト
                            , $toCustomerMail           // 本文
                            , $CONF['email03']          // 配送元アドレス
                            , $CONF['shop_name']        // 配送元 名前
                            , $CONF['email03']          // reply_to
                            , $CONF['email04']          // return_path
                            , $CONF['email04']          // Errors_to
        );
        // 宛先の設定
        $name = $data['name01'] . $data['name02'] .' 様';
        $objMail->setTo($data['email'], $name);
        $objMail->sendMail();
    }
    /*## 本会員承認 ADD END ##*/
}
