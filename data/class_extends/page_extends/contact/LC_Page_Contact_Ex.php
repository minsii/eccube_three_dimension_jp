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
require_once CLASS_REALDIR . 'pages/contact/LC_Page_Contact.php';

/**
 * お問い合わせ のページクラス(拡張).
 *
 * LC_Page_Contact をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Contact_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $
 */
class LC_Page_Contact_Ex extends LC_Page_Contact {

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
     * Page のアクション.
     *
     * @return void
     */
    function action() {

        $objDb = new SC_Helper_DB_Ex();
        $objFormParam = new SC_FormParam_Ex();

        $this->arrData = isset($_SESSION['customer']) ? $_SESSION['customer'] : '';

        switch ($this->getMode()) {
            case 'confirm':
                // エラーチェック
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $objFormParam->convParam();
                $objFormParam->toLower('email');
                $objFormParam->toLower('email02');
                $this->arrErr = $this->lfCheckError($objFormParam);
                // 入力値の取得
                $this->arrForm = $objFormParam->getFormParamList();

                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    // エラー無しで完了画面
                    $this->tpl_mainpage = 'contact/confirm.tpl';
                    $this->tpl_title = 'お問い合わせ(確認ページ)';
                }

                break;

            case 'return':
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $this->arrForm = $objFormParam->getFormParamList();

                break;

            case 'complete':
                $this->lfInitParam($objFormParam);
                $objFormParam->setParam($_POST);
                $this->arrErr = $objFormParam->checkError();
                $this->arrForm = $objFormParam->getFormParamList();
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $this->lfSendMail($this);


                    // 完了ページへ移動する
                    SC_Response_Ex::sendRedirect('complete.php');
                    SC_Response_Ex::actionExit();
                } else {
                    SC_Utils_Ex::sfDispSiteError(CUSTOMER_ERROR);
                    SC_Response_Ex::actionExit();
                }
                break;

            default:
            	/*## 商品問い合わせ ADD BEGIN ##*/
            	$this->lfInitParam($objFormParam);
            	$objFormParam->setParam($_GET);
            	$objFormParam->convParam();
            	$this->arrForm = $objFormParam->getFormParamList();
            	/*## 商品問い合わせ ADD END ##*/
                break;
        }

    }
    
    /**
     * お問い合わせ入力時のパラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
    	parent::lfInitParam(&$objFormParam);
    	
    	/*## 商品問い合わせ ADD BEGIN ##*/
    	if(constant("USE_PRODUCT_CONTACT") === true){
    		$objFormParam->addParam("お問い合わせ対象商品名", 'product_name');
    	}
        /*## 商品問い合わせ ADD END ##*/
    	
    	/*## 事例問い合わせ ADD BEGIN ##*/
    	if(constant("USE_JIREI_CONTACT") === true){
    		$objFormParam->addParam("お問い合わせ対象事例名", 'example_name');
    	}
        /*## 事例問い合わせ ADD END ##*/
    	
        $objFormParam->addParam('介護保護サービス指定事業所名', 'company', STEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('介護保護サービス指定事業所番号', 'company_no', STEXT_LEN, 'KVa', array('EXIST_CHECK','SPTAB_CHECK','MAX_LENGTH_CHECK'));
    }
}
