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
require_once CLASS_REALDIR . 'pages/mypage/LC_Page_Mypage_Change.php';

/**
 * 登録内容変更 のページクラス(拡張).
 *
 * LC_Page_Mypage_Change をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Mypage_Change_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Mypage_Change_Ex extends LC_Page_Mypage_Change {

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
     *  会員情報を登録する
     *
     * @param mixed $objFormParam
     * @param mixed $customer_id
     * @access private
     * @return void
     */
    function lfRegistCustomerData(&$objFormParam, $customer_id) {
        $arrRet             = $objFormParam->getHashArray();
        $sqlval             = $objFormParam->getDbArray();
        $sqlval['birth']    = SC_Utils_Ex::sfGetTimestamp($arrRet['year'], $arrRet['month'], $arrRet['day']);

        /*## 会員登録項目カスタマイズ ADD BEGIN ##*/
        $sqlval['company_certified_date']    = SC_Utils_Ex::sfGetTimestamp($arrRet['company_certified_date_year'], $arrRet['company_certified_date_month'], 1);
        $sqlval['company_open_date']    = SC_Utils_Ex::sfGetTimestamp($arrRet['company_open_date_year'], $arrRet['company_open_date_month'], 1);
        /*## 会員登録項目カスタマイズ ADD END ##*/
        
        SC_Helper_Customer_Ex::sfEditCustomerData($sqlval, $customer_id);
    }
    
}
