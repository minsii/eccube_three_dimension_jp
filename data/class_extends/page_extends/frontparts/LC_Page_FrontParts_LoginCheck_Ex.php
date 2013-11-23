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
require_once CLASS_REALDIR . 'pages/frontparts/LC_Page_FrontParts_LoginCheck.php';

/**
 * ログインチェック のページクラス(拡張).
 *
 * LC_Page_FrontParts_LoginCheck をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_FrontParts_LoginCheck_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_FrontParts_LoginCheck_Ex extends LC_Page_FrontParts_LoginCheck {

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

        // 会員管理クラス
        $objCustomer = new SC_Customer_Ex();
        // クッキー管理クラス
        $objCookie = new SC_Cookie_Ex();
        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();

        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);

        // リクエスト値をフォームにセット
        $objFormParam->setParam($_POST);

        $url = htmlspecialchars($_POST['url'], ENT_QUOTES);

        // モードによって分岐
        switch ($this->getMode()) {
            case 'login':
                // --- ログイン

                // 入力値のエラーチェック
                $objFormParam->trimParam();
                $objFormParam->toLower('login_email');
                $arrErr = $objFormParam->checkError();

                /*## 事業者番号でログイン ADD BEGIN ##*/
                $arrErr = array_merge($arrErr, $this->lfCheckError($objFormParam));
                /*## 事業者番号でログイン ADD END ##*/
                
                // エラーの場合はエラー画面に遷移
                if (count($arrErr) > 0) {
                    if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
                        echo $this->lfGetErrorMessage(TEMP_LOGIN_ERROR);
                        SC_Response_Ex::actionExit();
                    } else {
                        SC_Utils_Ex::sfDispSiteError(TEMP_LOGIN_ERROR);
                        SC_Response_Ex::actionExit();
                    }
                }

                // 入力チェック後の値を取得
                $arrForm = $objFormParam->getHashArray();

                // クッキー保存判定
                if ($arrForm['login_memory'] == '1' && $arrForm['login_email'] != '') {
                    $objCookie->setCookie('login_email', $arrForm['login_email']);
                } else {
                    $objCookie->setCookie('login_email', '');
                }

                /*## 事業者番号でログイン ADD BEGIN ##*/
                if ($arrForm['login_memory'] == '1' && $arrForm['login_company_no'] != '') {
                	$objCookie->setCookie('login_company_no', $arrForm['login_company_no']);
                } else {
                	$objCookie->setCookie('login_company_no', '');
                }
                /*## 事業者番号でログイン ADD END ##*/
                
                // 遷移先の制御
                if (count($arrErr) == 0) {
                    
                    // ログイン処理
                    if ($objCustomer->doLogin($arrForm['login_email'], $arrForm['login_pass']
                    /*## 事業者番号でログイン ADD BEGIN ##*/
                    , $arrForm["login_company_no"]
                    /*## 事業者番号でログイン ADD END ##*/
                    )) {
                        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
                            // ログインが成功した場合は携帯端末IDを保存する。
                            $objCustomer->updateMobilePhoneId();

                            /*
                             * email がモバイルドメインでは無く,
                             * 携帯メールアドレスが登録されていない場合
                             */
                            $objMobile = new SC_Helper_Mobile_Ex();
                            if (!$objMobile->gfIsMobileMailAddress($objCustomer->getValue('email'))) {
                                if (!$objCustomer->hasValue('email_mobile')) {

                                    SC_Response_Ex::sendRedirectFromUrlPath('entry/email_mobile.php');
                                    SC_Response_Ex::actionExit();
                                }
                            }
                        }

                        // --- ログインに成功した場合
                        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
                            echo SC_Utils_Ex::jsonEncode(array('success' => $url));
                        } else {
                            SC_Response_Ex::sendRedirect($url);
                        }                        
                        SC_Response_Ex::actionExit();
                    } else {
                        // --- ログインに失敗した場合

                        // ブルートフォースアタック対策
                        // ログイン失敗時に遅延させる
                        sleep(LOGIN_RETRY_INTERVAL);

                        $arrForm['login_email'] = strtolower($arrForm['login_email']);
                        $objQuery = SC_Query_Ex::getSingletonInstance();
                        $where = '(email = ? OR email_mobile = ?) AND status = 1 AND del_flg = 0';
                        
                        /*## 事業者番号でログイン MDF BEGIN ##*/
                        if(!empty($arrForm['login_email'])){
                        	$exists = $objQuery->exists('dtb_customer', $where, array($arrForm['login_email'], $arrForm['login_email']));
                        }
                        else if(!empty($arrForm['login_company_no'])){
                        	$where = 'company_no = ? AND status = 1 AND del_flg = 0';
                        	$exists = $objQuery->exists('dtb_customer', $where, array($arrForm['login_company_no']));
                        }
                        /*## 事業者番号でログイン MDF END ##*/
                        
                        // ログインエラー表示 TODO リファクタリング
                        if ($exists) {
                            if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
                                echo $this->lfGetErrorMessage(TEMP_LOGIN_ERROR);
                                SC_Response_Ex::actionExit();
                            } else {
                                SC_Utils_Ex::sfDispSiteError(TEMP_LOGIN_ERROR);
                                SC_Response_Ex::actionExit();
                            }
                        } else {
                            if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
                                echo $this->lfGetErrorMessage(SITE_LOGIN_ERROR);
                                SC_Response_Ex::actionExit();
                            } else {
                                SC_Utils_Ex::sfDispSiteError(SITE_LOGIN_ERROR);
                                SC_Response_Ex::actionExit();
                            }
                        }
                    }
                } else {
                    // XXX 到達しない？
                    // 入力エラーの場合、元のアドレスに戻す。
                    SC_Response_Ex::sendRedirect($url);
                    SC_Response_Ex::actionExit();
                }

                break;
            case 'logout':
                // --- ログアウト

                // ログイン情報の解放
                $objCustomer->EndSession();
                // 画面遷移の制御
                $mypage_url_search = strpos('.'.$url, 'mypage');
                if ($mypage_url_search == 2) {

                    // マイページログイン中はログイン画面へ移行
                    SC_Response_Ex::sendRedirectFromUrlPath('mypage/login.php');
                } else {

                    // 上記以外の場合、トップへ遷移
                    SC_Response_Ex::sendRedirect(HTTP_URL);
                }
                SC_Response_Ex::actionExit();

                break;
            default:
                break;
        }

    }
    
    
    /**
     * パラメーター情報の初期化.
     *
     * @param SC_FormParam $objFormParam パラメーター管理クラス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('記憶する', 'login_memory', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        /*## 事業者番号でログイン MDF BEGIN ##*/
//        $objFormParam->addParam('メールアドレス', 'login_email', MTEXT_LEN, 'a', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
		$objFormParam->addParam('メールアドレス', 'login_email', MTEXT_LEN, 'a', array('MAX_LENGTH_CHECK'));
		$objFormParam->addParam('事業者番号', 'login_company_no', MTEXT_LEN, 'a', array('MAX_LENGTH_CHECK'));
		/*## 事業者番号でログイン MDF END ##*/
        $objFormParam->addParam('パスワード', 'login_pass', PASSWORD_MAX_LEN, '', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
    }

    /*## 事業者番号でログイン ADD BEGIN ##*/
    function lfCheckError($objFormParam) {
    	$arrForm = $objFormParam->getHashArray();
    	$objErr = new SC_CheckError_Ex($arrForm);

    	$objErr->doFunc(array('メールアドレスまたは事業者番号', 'login_email', 'login_company_no'), array('ONE_EXIST_CHECK'));

    	return $objErr->arrErr;
    }
    /*## 事業者番号でログイン ADD END ##*/
}
