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
require_once CLASS_REALDIR . 'pages/admin/products/LC_Page_Admin_Products_UploadCSV.php';

/**
 * CSV アップロード のページクラス(拡張).
 *
 * LC_Page_Admin_Products_UploadCSV をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $$Id: LC_Page_Admin_Products_UploadCSV_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $$
 */
class LC_Page_Admin_Products_UploadCSV_Ex extends LC_Page_Admin_Products_UploadCSV {

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
     * 商品登録を行う.
     *
     * FIXME: 商品登録の実処理自体は、LC_Page_Admin_Products_Productと共通化して欲しい。
     *
     * @param SC_Query $objQuery SC_Queryインスタンス
     * @param string|integer $line 処理中の行数
     * @return void
     */
    function lfRegistProduct($objQuery, $line = '', &$objFormParam) {
        $objProduct = new SC_Product_Ex();
        // 登録データ対象取得
        $arrList = $objFormParam->getHashArray();
        // 登録時間を生成(DBのCURRENT_TIMESTAMPだとcommitした際、すべて同一の時間になってしまう)
        $arrList['update_date'] = $this->lfGetDbFormatTimeWithLine($line);

        // 商品登録情報を生成する。
        // 商品テーブルのカラムに存在しているもののうち、Form投入設定されていないデータは上書きしない。
        $sqlval = SC_Utils_Ex::sfArrayIntersectKeys($arrList, $this->arrProductColumn);

        // 必須入力では無い項目だが、空文字では問題のある特殊なカラム値の初期値設定
        $sqlval = $this->lfSetProductDefaultData($sqlval);

        if ($sqlval['product_id'] != '') {
            // 同じidが存在すればupdate存在しなければinsert
            $where = 'product_id = ?';
            $product_exists = $objQuery->exists('dtb_products', $where, array($sqlval['product_id']));
            if ($product_exists) {
                $objQuery->update('dtb_products', $sqlval, $where, array($sqlval['product_id']));
            } else {
                $sqlval['create_date'] = $arrList['update_date'];
                // INSERTの実行
                $objQuery->insert('dtb_products', $sqlval);
                // シーケンスの調整
                $seq_count = $objQuery->currVal('dtb_products_product_id');
                if ($seq_count < $sqlval['product_id']) {
                    $objQuery->setVal('dtb_products_product_id', $sqlval['product_id'] + 1);
                }
            }
            $product_id = $sqlval['product_id'];
        } else {
            // 新規登録
            $sqlval['product_id'] = $objQuery->nextVal('dtb_products_product_id');
            $product_id = $sqlval['product_id'];
            $sqlval['create_date'] = $arrList['update_date'];
            // INSERTの実行
            $objQuery->insert('dtb_products', $sqlval);
        }

        // カテゴリ登録
        if (isset($arrList['category_ids'])) {
            $arrCategory_id = explode(',', $arrList['category_ids']);
            $this->objDb->updateProductCategories($arrCategory_id, $product_id);
        }
        // 商品ステータス登録
        if (isset($arrList['product_statuses'])) {
            $arrStatus_id = explode(',', $arrList['product_statuses']);
            $objProduct->setProductStatus($product_id, $arrStatus_id);
        }

        /*## 商品ステータス2、ステータス3を追加 ADD BEGIN ##*/
        if (isset($arrList['product_statuses2'])) {
            $arrStatus_id = explode(',', $arrList['product_statuses2']);
            $objProduct->setProductStatus2($product_id, $arrStatus_id, $objQuery);
        }

        if (isset($arrList['product_statuses3'])) {
            $arrStatus_id = explode(',', $arrList['product_statuses3']);
            $objProduct->setProductStatus3($product_id, $arrStatus_id, $objQuery);
        }
        /*## 商品ステータス2、ステータス3を追加 ADD END ##*/
        
        /*## 追加規格 ADD BEGIN ##*/
        if(USE_EXTRA_CLASS === true || isset($arrList['extra_class_id'])){
        	$arrExtraClass_id = explode(',', $arrList['extra_class_id']);
        	$this->lfRegisterExtraClass($objQuery, $arrExtraClass_id, $product_id);
        }
        /*## 追加規格 ADD END ##*/
        
        /*## 商品支払方法指定 ADD BEGIN ##*/
        if(USE_PRODUCT_PAYMENT === true || isset($arrList['payment_id'])){
        	$arrPayment_id = explode(',', $arrList['payment_id']);
			$objProduct->setProductPayment($objQuery, $arrPayment_id, $product_id);
        }
        /*## 商品支払方法指定 ADD END ##*/
        
        /*## 商品配送方法指定 ADD BEGIN ##*/
        if(USE_PRODUCT_DELIV === true || isset($arrList['deliv_id'])){
        	$arrDeliv_id = explode(',', $arrList['deliv_id']);
			$objProduct->setProductDeliv($objQuery, $arrDeliv_id, $product_id);
        }
        /*## 商品配送方法指定 ADD END ##*/
        
        // 商品規格情報を登録する
        $this->lfRegistProductClass($objQuery, $arrList, $product_id, $arrList['product_class_id']);

        // 関連商品登録
        $this->lfRegistReccomendProducts($objQuery, $arrList, $product_id);
    }
    
    /*## 追加規格 ADD BEGIN ##*/
    /**
     * 追加規格の更新を行う.
     *
     * @param $objFormParam
     */
    function lfRegisterExtraClass($objQuery, $arrExtraClass_id, $product_id) {
        if(empty($product_id) || count($arrExtraClass_id) <= 0){
        	return false;
        }
        
        $sqlval = array();
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['product_id'] = $product_id;

       	// 全部の追加規格を削除してから、入力を全部新規挿入する
        $objQuery->delete("dtb_products_extra_class", "product_id=?", array($product_id));
      	foreach($arrExtraClass_id as $extraClass_id){
      			if(empty($extraClass_id))
      				continue;
        		$sqlval["extra_class_id"] = $extraClass_id;
        		$objQuery->insert("dtb_products_extra_class", $sqlval);
        }
    }
    /*## 追加規格 ADD END ##*/
}
