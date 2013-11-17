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
require_once CLASS_REALDIR . 'pages/entry/LC_Page_Entry.php';

/**
 * 会員登録(入力ページ) のページクラス(拡張).
 *
 * LC_Page_Entry をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Entry_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Entry_Ex extends LC_Page_Entry {

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
     * 会員登録に必要なSQLパラメーターの配列を生成する.
     *
     * フォームに入力された情報を元に, SQLパラメーターの配列を生成する.
     * モバイル端末の場合は, email を email_mobile にコピーし,
     * mobile_phone_id に携帯端末IDを格納する.
     *
     * @param mixed $objFormParam
     * @access private
     * @return $arrResults
     */
    function lfMakeSqlVal(&$objFormParam) {
        $arrResults             = parent::lfMakeSqlVal($objFormParam);
        $arrForm                = $objFormParam->getHashArray();

        /*## 会員登録項目カスタマイズ ADD BEGIN ##*/
        $arrResults['company_certified_date'] = SC_Utils_Ex::sfGetTimestamp($arrForm['company_certified_date_year'], $arrForm['company_certified_date_month'], 1);
        $arrResults['company_open_date'] = SC_Utils_Ex::sfGetTimestamp($arrForm['company_open_date_year'], $arrForm['company_open_date_month'], 1);
        /*## 会員登録項目カスタマイズ ADD END ##*/
         
        return $arrResults;
    }
    
}
