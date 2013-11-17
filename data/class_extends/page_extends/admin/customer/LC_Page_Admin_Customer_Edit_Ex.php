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
}
