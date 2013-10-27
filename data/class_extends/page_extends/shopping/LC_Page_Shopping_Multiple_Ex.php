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
require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Multiple.php';

/**
 * お届け先の複数指定 のページクラス(拡張).
 *
 * LC_Page_Shopping_Multiple をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Shopping_Multiple_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Shopping_Multiple_Ex extends LC_Page_Shopping_Multiple {

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
     * 配送住所のプルダウン用連想配列を取得する.
     *
     * 会員ログイン済みの場合は, 会員登録住所及び追加登録住所を取得する.
     * 非会員の場合は, 「お届け先の指定」画面で入力した住所を取得する.
     *
     * @param SC_Customer $objCustomer SC_Customer インスタンス
     * @param SC_Helper_Purchase $objPurchase SC_Helper_Purchase インスタンス
     * @param integer $uniqid 受注一時テーブルのユニークID
     * @return array 配送住所のプルダウン用連想配列
     */
    function getDelivAddrs(&$objCustomer, &$objPurchase, $uniqid) {
        $masterData = new SC_DB_MasterData_Ex();
        $arrPref = $masterData->getMasterData('mtb_pref');

        $arrResults = array('' => '選択してください');
        // 会員ログイン時
        if ($objCustomer->isLoginSuccess(true)) {
            $arrAddrs = $objCustomer->getCustomerAddress($objCustomer->getValue('customer_id'));
            foreach ($arrAddrs as $val) {
                $other_deliv_id = SC_Utils_Ex::isBlank($val['other_deliv_id']) ? 0 : $val['other_deliv_id'];
                $arrResults[$other_deliv_id] = $val['name01'] . $val['name02']
                    . " " . $arrPref[$val['pref']] . $val['addr01'] . $val['addr02'];
                
                /*## 顧客法人管理 ADD BEGIN ##*/
                if(constant("USE_CUSTOMER_COMPANY") === true){
                	$arrResults[$other_deliv_id] .= " ". $val['company'] ." ".  $val['company_department'];
                }
                /*## 顧客法人管理 ADD END ##*/
            }
        }
        // 非会員
        else {
            $arrShippings = $objPurchase->getShippingTemp();
            foreach ($arrShippings as $shipping_id => $val) {
                $arrResults[$shipping_id] = $val['shipping_name01'] . $val['shipping_name02']
                    . " " . $arrPref[$val['shipping_pref']]
                    . $val['shipping_addr01'] . $val['shipping_addr02'];
                    
                /*## 顧客法人管理 ADD BEGIN ##*/
                if(constant("USE_CUSTOMER_COMPANY") === true){
                	$arrResults[$shipping_id] .= " ". $val['company'] ." ".  $val['company_department'];
                }
                /*## 顧客法人管理 ADD END ##*/                    
            }
        }
        return $arrResults;
    }    
}
?>
