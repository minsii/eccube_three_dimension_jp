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
require_once CLASS_REALDIR . 'helper/SC_Helper_Customer.php';

/**
 * CSV関連のヘルパークラス(拡張).
 *
 * LC_Helper_Customer をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author LOCKON CO.,LTD.
 * @version $Id:SC_Helper_DB_Ex.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class SC_Helper_Customer_Ex extends SC_Helper_Customer {
	
    /**
     * 会員共通
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @access public
     * @return void
     */
    function sfCustomerCommonParam(&$objFormParam) {
    	/*## 顧客法人管理 ADD BEGIN ##*/
    	if(constant("USE_CUSTOMER_COMPANY") === true){
    		$objFormParam->addParam("法人名", 'company', STEXT_LEN, 'aKV', array("MAX_LENGTH_CHECK"));
    		$objFormParam->addParam("法人名(フリガナ)", 'company_kana', STEXT_LEN, 'CKV', array("MAX_LENGTH_CHECK", "KANA_CHECK"));
    		$objFormParam->addParam("部署名", 'company_department', STEXT_LEN, 'aKV', array("MAX_LENGTH_CHECK"));
    	}
    	/*## 顧客法人管理 ADD END ##*/
    	
    	/*## 会員登録項目カスタマイズ MDF BEGIN ##*/
        $objFormParam->addParam('ご担当者(姓)', 'name01', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' ,'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ご担当者(名)', 'name02', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' , 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ご担当者(フリガナ・姓)', 'kana01', STEXT_LEN, 'CKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' ,'MAX_LENGTH_CHECK', 'KANA_CHECK'));
        $objFormParam->addParam('ご担当者(フリガナ・名)', 'kana02', STEXT_LEN, 'CKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' ,'MAX_LENGTH_CHECK', 'KANA_CHECK'));
		/*## 会員登録項目カスタマイズ MDF END ##*/
        
        $objFormParam->addParam('郵便番号1', 'zip01', ZIP01_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK' ,'NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('郵便番号2', 'zip02', ZIP02_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK' ,'NUM_CHECK', 'NUM_COUNT_CHECK'));
        $objFormParam->addParam('都道府県', 'pref', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('住所1', 'addr01', MTEXT_LEN, 'aKV', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('住所2', 'addr02', MTEXT_LEN, 'aKV', array('EXIST_CHECK', 'SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号1', 'tel01', TEL_ITEM_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号2', 'tel02', TEL_ITEM_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お電話番号3', 'tel03', TEL_ITEM_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('FAX番号1', 'fax01', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('FAX番号2', 'fax02', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('FAX番号3', 'fax03', TEL_ITEM_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
    }
 
    /**
     * お届け先フォーム初期化
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @access public
     * @return void
     */
    function sfCustomerOtherDelivParam (&$objFormParam) {
        SC_Helper_Customer_Ex::sfCustomerCommonParam($objFormParam);
        $objFormParam->addParam("", 'other_deliv_id');
        
   		/*## 顧客お届け先FAX ADD BEGIN ##*/
        if(USE_OTHER_DELIV_FAX === true){
        	$objFormParam->addParam("FAX番号1", 'fax01', TEL_ITEM_LEN, 'n', array("SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        	$objFormParam->addParam("FAX番号2", 'fax02', TEL_ITEM_LEN, 'n', array("SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        	$objFormParam->addParam("FAX番号3", 'fax03', TEL_ITEM_LEN, 'n', array("SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        }
    	/*## 顧客お届け先FAX ADD END ##*/                
    }
    
    /**
     * 会員登録共通
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param boolean $isAdmin true:管理者画面 false:会員向け
     * @param boolean $is_mypage マイページの場合 true
     * @return void
     */
    function sfCustomerRegisterParam(&$objFormParam, $isAdmin = false, $is_mypage = false) {
		parent::sfCustomerRegisterParam($objFormParam, $isAdmin, $is_mypage);
		
		/*## 会員登録項目カスタマイズ ADD BEGIN ##*/
    	$objFormParam->addParam("介護保護サービス指定事業所名", 'company', STEXT_LEN, 'aKV', array('EXIST_CHECK', "MAX_LENGTH_CHECK"));    	
    	$objFormParam->addParam("介護保護サービス指定事業所番号", 'company_no', STEXT_LEN, 'aKV', array('EXIST_CHECK', 'NO_SPTAB', 'SPTAB_CHECK' ,'MAX_LENGTH_CHECK'));
    	
        $objFormParam->addParam('事業者区分', 'company_type', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('指定事業所取得年', 'company_certified_date_year', 4, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('指定事業所取得月', 'company_certified_date_month', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('新規開業予定年', 'company_open_date_year', 4, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('新規開業予定月', 'company_open_date_month', 2, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
        $objFormParam->addParam('通信欄', 'message', LLTEXT_LEN, 'aKV', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('カタログ希望', 'need_category_check', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        /*## 会員登録項目カスタマイズ ADD END ##*/
    }
    
    /**
     * 会員情報の登録・編集処理を行う.
     *
     * @param array $array 登録するデータの配列（SC_FormParamのgetDbArrayの戻り値）
     * @param array $customer_id nullの場合はinsert, 存在する場合はupdate
     * @access public
     * @return integer 登録編集したユーザーのcustomer_id
     */
    function sfEditCustomerData($array, $customer_id = null) {
    	/*## 会員登録項目カスタマイズ ADD BEGIN ##*/
    	$company_type = $array["company_type"];
    	unset($array["company_type"]);
    	
    	$customer_id = parent::sfEditCustomerData($array, $customer_id);
    	
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        $objQuery->delete("dtb_customer_company_type", "customer_id=?", array($customer_id));

        if(is_array($company_type)){
        	$sqlval['customer_id'] = $customer_id;
        	foreach($company_type as $company_type_id){
        		$sqlval["company_type_id"] = $company_type_id;
        		$objQuery->insert("dtb_customer_company_type", $sqlval);
        	}
        }
        
        $objQuery->commit();
        /*## 会員登録項目カスタマイズ ADD END ##*/
        
        return $customer_id;
    }
    
    /**
     * customer_idから会員情報を取得する
     *
     * @param mixed $customer_id
     * @param mixed $mask_flg
     * @access public
     * @return array 会員情報の配列を返す
     */
    function sfGetCustomerData($customer_id, $mask_flg = true) {
		$arrForm = parent::sfGetCustomerData($customer_id, $mask_flg);
		
		/*## 会員登録項目カスタマイズ ADD BEGIN ##*/
        if (isset($arrForm['company_certified_date'])) {
            $date = explode(' ', $arrForm['company_certified_date']);
            list($arrForm['company_certified_date_year'], $arrForm['company_certified_date_month']) = explode('-',$date[0]);
        }
        if (isset($arrForm['company_open_date'])) {
            $date = explode(' ', $arrForm['company_open_date']);
            list($arrForm['company_open_date_year'], $arrForm['company_open_date_month']) = explode('-',$date[0]);
        }
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrForm["company_type"] = $objQuery->getCol("company_type_id", "dtb_customer_company_type", "customer_id=?", array($customer_id));
        /*## 会員登録項目カスタマイズ ADD END ##*/
        
        return $arrForm;
    }
}
?>
