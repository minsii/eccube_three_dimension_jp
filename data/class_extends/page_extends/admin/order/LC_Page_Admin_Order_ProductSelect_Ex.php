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
require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order_ProductSelect.php';

/**
 * 商品選択 のページクラス(拡張).
 *
 * LC_Page_Admin_Order_ProductSelect をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_ProductSelect_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Admin_Order_ProductSelect_Ex extends LC_Page_Admin_Order_ProductSelect {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        /*## 追加規格 ADD BEGIN ##*/
        if(USE_EXTRA_CLASS === true){
        	$objDb = new SC_Helper_DB_Ex();
        	$this->arrAllExtraClass = $objDb->lfGetAllExtraClass();
        	$this->arrAllExtraClassCat = $objDb->lfGetAllExtraClassCategory();
        }
        /*## 追加規格 ADD END ##*/        
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
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $this->tpl_no = $this->getNo(array($_GET,$_POST));

        switch ($this->getMode()) {
            case 'search':
                $objProduct = new SC_Product_Ex();
                $this->arrForm = $objFormParam->getHashArray();
                $wheres = $this->createWhere($objFormParam,$objDb);
                $this->tpl_linemax = $this->getLineCount($wheres,$objProduct);

                //ぶった斬りポイント==================================================================
                // ページ送りの処理
                $page_max = SC_Utils_Ex::sfGetSearchPageMax($_POST['search_page_max']);

                // ページ送りの取得
                $objNavi = new SC_PageNavi_Ex($_POST['search_pageno'], $this->tpl_linemax, $page_max, 'fnNaviSearchOnlyPage', NAVI_PMAX);
                $this->tpl_strnavi = $objNavi->strnavi;     // 表示文字列
                $startno = $objNavi->start_row;
                $arrProduct_id = $this->getProducts($wheres, $objProduct, $page_max, $startno);
                $productList = $this->getProductList($arrProduct_id,$objProduct);
                //取得している並び順で並び替え
                $this->arrProducts = $this->sortProducts($arrProduct_id,$productList);
                $objProduct->setProductsClassByProductIds($arrProduct_id);
                $this->tpl_javascript .= $this->getTplJavascript($objProduct);
                $js_fnOnLoad = $this->getFnOnload($this->arrProducts);
                $this->tpl_javascript .= 'function fnOnLoad(){' . $js_fnOnLoad . '}';
                $this->tpl_onload .= 'fnOnLoad();';
                // 規格1クラス名
                $this->tpl_class_name1 = $objProduct->className1;
                // 規格2クラス名
                $this->tpl_class_name2 = $objProduct->className2;
                // 規格1
                $this->arrClassCat1 = $objProduct->classCats1;
                // 規格1が設定されている
                $this->tpl_classcat_find1 = $objProduct->classCat1_find;
                // 規格2が設定されている
                $this->tpl_classcat_find2 = $objProduct->classCat2_find;
                $this->tpl_product_class_id = $objProduct->product_class_id;
                $this->tpl_stock_find = $objProduct->stock_find;
                /*## 追加規格 ADD BEGIN ##*/
                if(USE_EXTRA_CLASS === true){
                	foreach($arrProduct_id as $product_id){
                		$this->tpl_extra_class[$product_id] = $objProduct->getExtraClass($product_id);
                	}
                }
                /*## 追加規格 ADD END ##*/
                break;
            default:
                break;
        }

        // カテゴリ取得
        $this->arrCatList = $objDb->sfGetCategoryList();
        $this->setTemplate($this->tpl_mainpage);
    }
    
}
?>
