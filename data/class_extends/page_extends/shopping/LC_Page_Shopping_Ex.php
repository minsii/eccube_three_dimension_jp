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
require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping.php';

/**
 * ショッピングログイン のページクラス(拡張).
 *
 * LC_Page_Shopping をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Shopping_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $
 */
class LC_Page_Shopping_Ex extends LC_Page_Shopping {

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
     * お客様情報入力時のパラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {

    	/*## 顧客法人管理 ADD BEGIN ##*/
    	if(constant("USE_CUSTOMER_COMPANY") === true){
    		$objFormParam->addParam("法人名", 'order_company', STEXT_LEN, 'aKV', array("MAX_LENGTH_CHECK"));
    		$objFormParam->addParam("法人名(フリガナ)", 'order_company_kana', STEXT_LEN, 'CKV', array("MAX_LENGTH_CHECK", "KANA_CHECK"));
    		$objFormParam->addParam("部署名", 'order_company_department', STEXT_LEN, 'aKV', array("MAX_LENGTH_CHECK"));
    	}
    	/*## 顧客法人管理 ADD END ##*/
        $objFormParam->addParam("お名前(姓)", "order_name01", STEXT_LEN, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お名前(名)", "order_name02", STEXT_LEN, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お名前(フリガナ・姓)", "order_kana01", STEXT_LEN, 'KVCa', array("EXIST_CHECK", "KANA_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お名前(フリガナ・名)", "order_kana02", STEXT_LEN, 'KVCa', array("EXIST_CHECK", "KANA_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("郵便番号1", "order_zip01", ZIP01_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $objFormParam->addParam("郵便番号2", "order_zip02", ZIP02_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "NUM_COUNT_CHECK"));
        $objFormParam->addParam("都道府県", "order_pref", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("住所1", "order_addr01", MTEXT_LEN, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("住所2", "order_addr02", MTEXT_LEN, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("電話番号1", "order_tel01", TEL_ITEM_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("電話番号2", "order_tel02", TEL_ITEM_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("電話番号3", "order_tel03", TEL_ITEM_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("FAX番号1", "order_fax01", TEL_ITEM_LEN, 'n', array("MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("FAX番号2", "order_fax02", TEL_ITEM_LEN, 'n', array("MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("FAX番号3", "order_fax03", TEL_ITEM_LEN, 'n', array("MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("メールアドレス", "order_email", null, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "NO_SPTAB", "EMAIL_CHECK", "EMAIL_CHAR_CHECK"));
        $objFormParam->addParam("メールアドレス（確認）", "order_email02", null, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "NO_SPTAB", "EMAIL_CHECK", "EMAIL_CHAR_CHECK"), "", false);
        $objFormParam->addParam("年", 'year', INT_LEN, 'n', array("MAX_LENGTH_CHECK"), "", false);
        $objFormParam->addParam("月", 'month', INT_LEN, 'n', array("MAX_LENGTH_CHECK"), "", false);
        $objFormParam->addParam("日", 'day', INT_LEN, 'n', array("MAX_LENGTH_CHECK"), "", false);
        $objFormParam->addParam("性別", "order_sex", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("職業", "order_job", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("別のお届け先", "deliv_check", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        
   		/*## 顧客法人管理 ADD BEGIN ##*/
    	if(constant("USE_CUSTOMER_COMPANY") === true){
    		$objFormParam->addParam("法人名", 'shipping_company', STEXT_LEN, 'aKV', array("MAX_LENGTH_CHECK"));
    		$objFormParam->addParam("法人名(フリガナ)", 'shipping_company_kana', STEXT_LEN, 'CKV', array("MAX_LENGTH_CHECK", "KANA_CHECK"));
    		$objFormParam->addParam("部署名", 'shipping_company_department', STEXT_LEN, 'aKV', array("MAX_LENGTH_CHECK"));
    	}
    	/*## 顧客法人管理 ADD END ##*/         
        $objFormParam->addParam("お名前(姓)", "shipping_name01", STEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お名前(名)", "shipping_name02", STEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お名前(フリガナ・姓)", "shipping_kana01", STEXT_LEN, 'KVCa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("お名前(フリガナ・名)", "shipping_kana02", STEXT_LEN, 'KVCa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("郵便番号1", "shipping_zip01", ZIP01_LEN, 'n', array("NUM_CHECK", "NUM_COUNT_CHECK"));
        $objFormParam->addParam("郵便番号2", "shipping_zip02", ZIP02_LEN, 'n', array("NUM_CHECK", "NUM_COUNT_CHECK"));
        $objFormParam->addParam("都道府県", "shipping_pref", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("住所1", "shipping_addr01", MTEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("住所2", "shipping_addr02", MTEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("電話番号1", "shipping_tel01", TEL_ITEM_LEN, 'n', array("MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("電話番号2", "shipping_tel02", TEL_ITEM_LEN, 'n', array("MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("電話番号3", "shipping_tel03", TEL_ITEM_LEN, 'n', array("MAX_LENGTH_CHECK" ,"NUM_CHECK"));
        $objFormParam->addParam("メールマガジン", "mail_flag", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"), 1);
    }    
}
?>
