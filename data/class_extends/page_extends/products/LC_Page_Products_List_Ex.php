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
require_once CLASS_REALDIR . 'pages/products/LC_Page_Products_List.php';

/**
 * LC_Page_Products_List のページクラス(拡張).
 *
 * LC_Page_Products_List をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Products_List_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Products_List_Ex extends LC_Page_Products_List {

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
     * Page のAction.
     *
     * @return void
     */
    function action() {
    	/* ## 商品一覧画面でお気に入り商品登録 ADD BEGIN ## */
    	$objCustomer = new SC_Customer_Ex();
    	$this->just_added_favorite = 0;
    	$this->exist_favorite = 0;
    	/* ## 商品一覧画面でお気に入り商品登録 ADD END ## */

        $objQuery   =& SC_Query_Ex::getSingletonInstance();
        $objProduct = new SC_Product_Ex();

        $this->arrForm = $_REQUEST;//時間が無いのでコレで勘弁してください。 tao_s
        //modeの取得
        $this->mode = $this->getMode();

        /*## カテゴリで商品検索 ADD BEGIN ##*/
        //絞込みパラメーター
        $this->arrSelectForm = $this->lfSetSelectParam($_REQUEST);
        /*## カテゴリで商品検索 ADD END ##*/
        
        
        //表示条件の取得
        $this->arrSearchData = array(
            'category_id'   => $this->lfGetCategoryId(intval($this->arrForm['category_id'])),
            'maker_id'      => intval($this->arrForm['maker_id']),
            'name'          => $this->arrForm['name']
        );
        $this->orderby = $this->arrForm['orderby'];

        //ページング設定
        $this->tpl_pageno   = $this->arrForm['pageno'];
        /*## CATEGORY 情報 ## MDF BEGIN*/
        //$this->disp_number  = $this->lfGetDisplayNum($this->arrForm['disp_number']);
        $this->disp_number = PRODUCT_LIST_MAX;
        /*## CATEGORY 情報 ## MDF END*/

        // 画面に表示するサブタイトルの設定
        $this->tpl_subtitle = $this->lfGetPageTitle($this->mode, $this->arrSearchData['category_id']);

        // 画面に表示する検索条件を設定
        $this->arrSearch    = $this->lfGetSearchConditionDisp($this->arrSearchData);

        // 商品一覧データの取得
        $arrSearchCondition = $this->lfGetSearchCondition($this->arrSearchData);
        $this->tpl_linemax  = $this->lfGetProductAllNum($arrSearchCondition);
        $urlParam           = "category_id={$this->arrSearchData['category_id']}&pageno=#page#";
        $this->objNavi      = new SC_PageNavi_Ex($this->tpl_pageno, $this->tpl_linemax, $this->disp_number, 'fnNaviPage', NAVI_PMAX, $urlParam, SC_Display_Ex::detectDevice() !== DEVICE_TYPE_MOBILE);
        $this->arrProducts  = $this->lfGetProductsList($arrSearchCondition, $this->disp_number, $this->objNavi->start_row, $this->tpl_linemax, $objProduct);

        /*## CATEGORY 情報 ## ADD BEGIN*/
		$this->arrPagenavi = $this->objNavi->arrPagenavi;
		/*## CATEGORY 情報 ## ADD END*/
		
        switch($this->getMode()){
            case "json":
                   $this->arrProducts = $this->setStatusDataTo($this->arrProducts, $this->arrSTATUS, $this->arrSTATUS_IMAGE);
                   $this->arrProducts = $objProduct->setPriceTaxTo($this->arrProducts);

                   // 一覧メイン画像の指定が無い商品のための処理
                   foreach ($this->arrProducts as $key=>$val) {
                       $this->arrProducts[$key]['main_list_image'] = SC_Utils_Ex::sfNoImageMainList($val['main_list_image']);
                   }

                   echo SC_Utils_Ex::jsonEncode($this->arrProducts);
                   exit;
               break;
               
			/* ## 商品一覧画面でお気に入り商品登録 ADD BEGIN ## */
            case "add_favorite":        		
        		// ログイン中のユーザが商品をお気に入りにいれる処理
        		if ($objCustomer->isLoginSuccess() === true &&
        		$this->arrForm['favorite_product_id'] > 0 ) {
        			// 商品IDの正当性チェック
        			if (!SC_Utils_Ex::sfIsInt($this->arrForm['favorite_product_id'])
        			|| !SC_Helper_DB_Ex::sfIsRecord("dtb_products", "product_id", $this->arrForm['favorite_product_id'], "del_flg = 0 AND status = 1")) {
        				SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
        			}
        			$this->lfRegistFavoriteProduct($this->arrForm['favorite_product_id'], $objCustomer->getValue('customer_id'));
        			$anchor_hash = $this->getAnchorHash("product". $this->arrForm['favorite_product_id']);
        			$this->tpl_onload .= $anchor_hash;
        		}
        		/* ## 商品一覧画面でお気に入り商品登録 ADD END ## */
        		
            default:

                //商品一覧の表示処理
                $strnavi            = $this->objNavi->strnavi;
                // 表示文字列
                $this->tpl_strnavi  = empty($strnavi) ? "&nbsp;" : $strnavi;

                // 規格1クラス名
                $this->tpl_class_name1  = $objProduct->className1;

                // 規格2クラス名
                $this->tpl_class_name2  = $objProduct->className2;

                // 規格1
                $this->arrClassCat1     = $objProduct->classCats1;

                // 規格1が設定されている
                $this->tpl_classcat_find1 = $objProduct->classCat1_find;
                // 規格2が設定されている
                $this->tpl_classcat_find2 = $objProduct->classCat2_find;

                $this->tpl_stock_find       = $objProduct->stock_find;
                $this->tpl_product_class_id = $objProduct->product_class_id;
                $this->tpl_product_type     = $objProduct->product_type;

                // 商品ステータスを取得
                $this->productStatus = $this->arrProducts['productStatus'];
                unset($this->arrProducts['productStatus']);
                $this->tpl_javascript .= 'var productsClassCategories = ' . SC_Utils_Ex::jsonEncode($objProduct->classCategories) . ';';
                //onloadスクリプトを設定. 在庫ありの商品のみ出力する
                foreach ($this->arrProducts as $arrProduct) {
                    if ($arrProduct['stock_unlimited_max'] || $arrProduct['stock_max'] > 0) {
                        $js_fnOnLoad .= "fnSetClassCategories(document.product_form{$arrProduct['product_id']});";
                    }
                }

                //カート処理
                $target_product_id = intval($this->arrForm['product_id']);
                if ( $target_product_id > 0) {
                    // 商品IDの正当性チェック
                    if (!SC_Utils_Ex::sfIsInt($this->arrForm['product_id'])
                        || !SC_Helper_DB_Ex::sfIsRecord("dtb_products", "product_id", $this->arrForm['product_id'], "del_flg = 0 AND status = 1")) {
                        SC_Utils_Ex::sfDispSiteError(PRODUCT_NOT_FOUND);
                    }

                    // 入力内容のチェック
                    $arrErr = $this->lfCheckError($target_product_id, $this->arrForm, $this->tpl_classcat_find1, $this->tpl_classcat_find2);
                    if (empty($arrErr)) {
                        $this->lfAddCart($this->arrForm, $_SERVER['HTTP_REFERER']);
                        SC_Response_Ex::sendRedirect(CART_URLPATH);
                        exit;
                    }
                    $js_fnOnLoad .= $this->lfSetSelectedData($this->arrProducts, $this->arrForm, $arrErr, $target_product_id);
                } else {
                    // カート「戻るボタン」用に保持
                    $netURL = new Net_URL();
                    //該当メソッドが無いため、$_SESSIONに直接セット
                    $_SESSION['cart_referer_url'] = $netURL->getURL();
                }

                $this->tpl_javascript   .= 'function fnOnLoad(){' . $js_fnOnLoad . '}';
                $this->tpl_onload       .= 'fnOnLoad(); ';
                break;
        }

        $this->tpl_rnd          = SC_Utils_Ex::sfGetRandomString(3);
        
        /* ## 商品一覧画面でお気に入り商品登録 ADD BEGIN ## */
        if ($objCustomer->isLoginSuccess() === true){
        	$arrFavorites = $objCustomer->getFavoriteProducts($objCustomer->getValue("customer_id"));
        	$this->arrFavorites = array_fill_keys($arrFavorites, "1");
        }
        /* ## 商品一覧画面でお気に入り商品登録 ADD END ## */
        
        /*## SEO管理 ## ADD BEGIN*/
        $this->lfSetPageInfo($this->arrSearchData['category_id']);
        /*## SEO管理 ## ADD END*/
        
        /*## カテゴリお勧め商品 ADD BEGIN ##*/
        $this->lfPreGetRecommend($this->arrSearchData['category_id']);
        /*## カテゴリお勧め商品 ADD END ##*/

    }

    
    /*## SEO管理 ## ADD BEGIN*/
    function lfSetPageInfo($category_id){
    	$objQuery = new SC_Query();
    	$objDb = new SC_Helper_DB_Ex();
    	
    	$sql = "SELECT * FROM dtb_category WHERE category_id = ?";
    	$arrRet = $objQuery->getall($sql,array($category_id));
    	if(constant("USE_SEO") === true){
    		$objLayout = new SC_Helper_PageLayout_Ex();
    		$objLayout->sfSetPageInfo($this, $arrRet[0]);
    	}
    	
    	/*## CATEGORY 情報 ## ADD BEGIN*/
    	if(constant("USE_CATEGORY_INFO") === true){
    		$this->arrCategory = $arrRet[0];
    		if(is_array($this->arrCategory)){
    			foreach($this->arrCategory as $name => $val){
    				$objDb->sfChangeConsts($this->arrCategory[$name]);
    			}
    		}
    	}
    	/*## CATEGORY 情報 ## ADD END*/
    }
    /*## SEO管理 ## ADD END*/

	
    /*## カテゴリお勧め商品 ADD BEGIN ##*/
	/**
	 * 登録済おすすめ情報を取得する
	 *
	 * @param $category_id
	 */
    function lfPreGetRecommend($category_id){
    	if(!empty($category_id) &&
    	defined("CATEGORY_RECOMMEND_PRODUCT_MAX")
    	&& CATEGORY_RECOMMEND_PRODUCT_MAX > 0){
    		$objProduct = new SC_Product_Ex();
    		$objQuery =& SC_Query_Ex::getSingletonInstance();
    			
    		$objQuery->setOrder("rank DESC");
    		$arrRecommendData = $objQuery->select("product_id", "dtb_category_recommend", "category_id = ?", array($category_id));

    		$arrRecommendProductId = array();
    		foreach($arrRecommendData as $recommend){
    			$arrRecommendProductId[] = $recommend["product_id"];
    		}

    		$objQuery =& SC_Query_Ex::getSingletonInstance();
    		$arrProducts = $objProduct->getListByProductIds($objQuery, $arrRecommendProductId);

    		$arrPdctStatus = $objProduct->getProductStatus($arrRecommendProductId);

    		//取得している並び順で並び替え
    		// FIXME SC_Productあたりにソート処理はもってくべき
    		$arrProducts2 = array();
    		foreach($arrProducts as $item) {
    			$arrProducts2[ $item['product_id'] ] = $item;
    		}

    		$arrRecommend = array();
    		foreach($arrRecommendProductId as $product_id) {
    			$arrProducts2[$product_id]['product_status'] = $arrPdctStatus[$product_id];
    			$arrRecommend[] = $arrProducts2[$product_id];
    		}
    		$this->arrRecommend = $arrRecommend;
    	}
    }
	/*## カテゴリお勧め商品 ADD END ##*/
    
    
    /**
     * 検索条件のwhere文とかを取得
     *
     * @return array
     */
    function lfGetSearchCondition($arrSearchData){
        $searchCondition = array(
            'where'             => "",
            'arrval'            => array(),
            "where_category"    => "",
            'arrvalCategory'    => array()
        );

        // カテゴリからのWHERE文字列取得
        if ($arrSearchData["category_id"] != 0) {
            list($searchCondition["where_category"], $searchCondition['arrvalCategory']) = SC_Helper_DB_Ex::sfGetCatWhere($arrSearchData["category_id"]);
        }
        
        /*## カテゴリで商品検索 ADD BEGIN ##*/
        $selectWhere = "";
        $arrSelectCatId = array();

       	if(count($this->arrSelectForm)){
       		$arrSelectWhere = array();
       		$i=0;
       		foreach($this->arrSelectForm as $cat_id){
       			$using = "";
       			if($i > 0){
       				$using = "USING(product_id)";
       			}
       			// AND (..OR..)
       			if(is_array($cat_id)){
       				if(count($cat_id)){
       					$sqlcol = join(",", array_fill(0, count($cat_id), "?"));
       					$arrSelectWhere[] = " (SELECT product_id FROM dtb_product_categories WHERE category_id IN ($sqlcol)) Tcats{$cat_id[0]} $using";
       					$arrSelectCatId = array_merge($arrSelectCatId, $cat_id);
       				}
       			// AND ()
       			}elseif(!empty($cat_id)){
       				$arrSelectWhere[] = " (SELECT product_id FROM dtb_product_categories WHERE category_id=?) T$cat_id $using";
       				$arrSelectCatId[] = $cat_id;
       			}
       			$i++;
       		}
       		if(count($arrSelectWhere) > 0){
       			$selectWhere = join(" INNER JOIN ", $arrSelectWhere);
       			$selectWhere = "(SELECT product_id FROM ". $selectWhere .")";
       		}
       	}
       	/*## カテゴリで商品検索 ADD END ##*/
       	
        // ▼対象商品IDの抽出
        // 商品検索条件の作成（未削除、表示）
        $searchCondition['where'] = "alldtl.del_flg = 0 AND alldtl.status = 1 ";

        // 在庫無し商品の非表示
        if (NOSTOCK_HIDDEN === true) {
            $searchCondition['where'] .= ' AND (stock >= 1 OR stock_unlimited = 1)';
        }

        /*## カテゴリで商品検索 MDF BEGIN ##*/
        if(!empty($selectWhere)){
        	$searchCondition['where'] .= " AND alldtl.product_id IN $selectWhere";
        	$searchCondition['arrval'] = array_merge($searchCondition['arrval'], $arrSelectCatId);
        }
        elseif (strlen($searchCondition["where_category"]) >= 1) {
            $searchCondition['where'] .= ' AND EXISTS (SELECT * FROM dtb_product_categories WHERE ' . $searchCondition['where_category'] . ' AND product_id = alldtl.product_id)';
            $searchCondition['arrval'] = array_merge($searchCondition['arrval'], $searchCondition['arrvalCategory']);
        }
        /*## カテゴリで商品検索 MDF END ##*/

        // 商品名をwhere文に
        $name = $arrSearchData['name'];
        $name = str_replace(",", "", $name);
        // 全角スペースを半角スペースに変換
        $name = str_replace('　', ' ', $name);
        // スペースでキーワードを分割
        $names = preg_split("/ +/", $name);
        // 分割したキーワードを一つずつwhere文に追加
        foreach ($names as $val) {
            if ( strlen($val) > 0 ) {
                $searchCondition['where']    .= " AND ( alldtl.name ILIKE ? OR alldtl.comment3 ILIKE ?) ";
                $searchCondition['arrval'][]  = "%$val%";
                $searchCondition['arrval'][]  = "%$val%";
            }
        }

        // メーカーらのWHERE文字列取得
        if ($arrSearchData['maker_id']) {
            $searchCondition['where']   .= " AND alldtl.maker_id = ? ";
            $searchCondition['arrval'][] = $arrSearchData['maker_id'];
        }
        return $searchCondition;
    }
    
    /*## カテゴリで商品検索 ADD BEGIN ##*/
    function lfSetSelectParam($array){
    	$i = 1;
    	$arrSelectForm = array();
    	do{
    		$key = "sel$i";
    		if(!isset($array[$key]))
    			break;
			
    		if(!empty($array[$key]))
    			$arrSelectForm[$key] = $array[$key];
    			
    		$i++;
    	}while(1);
    	return $arrSelectForm;
    }
    /*## カテゴリで商品検索 ADD END ##*/
    
    /* 商品一覧の表示 */
    function lfGetProductsList($searchCondition, $disp_number, $startno, $linemax, &$objProduct) {

        $arrOrderVal = array();

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // 表示順序
        switch ($this->orderby) {
            // 販売価格が安い順
            case 'price_up':
                $objProduct->setProductsOrder('price02', 'dtb_products_class', 'ASC');
                break;
            /*## 商品一覧で価格高い順 ADD END ##*/
            // 販売価格が高い順
            case 'price_down':
                $objProduct->setProductsOrder('price02', 'dtb_products_class', 'DESC');
                break;
			/*## 商品一覧で価格高い順 ADD END ##*/
            // 新着順
            case 'date':
                $objProduct->setProductsOrder('create_date', 'dtb_products', 'DESC');
                break;

            default:
                if (strlen($searchCondition['where_category']) >= 1) {
                    $dtb_product_categories = '(SELECT * FROM dtb_product_categories WHERE '.$searchCondition['where_category'].')';
                    $arrOrderVal           = $searchCondition['arrvalCategory'];
                } else {
                    $dtb_product_categories = 'dtb_product_categories';
                }
                $order = <<< __EOS__
                    (
                        SELECT
                            T3.rank * 2147483648 + T2.rank
                        FROM
                            $dtb_product_categories T2
                            JOIN dtb_category T3
                              ON T2.category_id = T3.category_id
                        WHERE T2.product_id = alldtl.product_id
                        ORDER BY T3.rank DESC, T2.rank DESC
                        LIMIT 1
                    ) DESC
                    ,product_id DESC
__EOS__;
                    $objQuery->setOrder($order);
                break;
        }
        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setLimitOffset($disp_number, $startno);
        $objQuery->setWhere($searchCondition['where']);

        // 表示すべきIDとそのIDの並び順を一気に取得
        $arrProductId = $objProduct->findProductIdsOrder($objQuery, array_merge($searchCondition['arrval'], $arrOrderVal));

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrProducts = $objProduct->getListByProductIds($objQuery, $arrProductId);

        // 規格を設定
        $objProduct->setProductsClassByProductIds($arrProductId);
        $arrProducts['productStatus'] = $objProduct->getProductStatus($arrProductId);
        return $arrProducts;
    }
    
    /* ## 商品一覧画面でお気に入り商品登録 ADD BEGIN ## */
    /*
     * お気に入り商品登録
     * @return void
     */
    function lfRegistFavoriteProduct($favorite_product_id,$customer_id) {

    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	$exists = $objQuery->exists('dtb_customer_favorite_products', 'customer_id = ? AND product_id = ?', array($customer_id, $favorite_product_id));

    	if (!$exists) {
    		$sqlval['customer_id'] = $customer_id;
    		$sqlval['product_id'] = $favorite_product_id;
    		$sqlval['update_date'] = 'CURRENT_TIMESTAMP';
    		$sqlval['create_date'] = 'CURRENT_TIMESTAMP';

    		$objQuery->begin();
    		$objQuery->insert('dtb_customer_favorite_products', $sqlval);
    		$objQuery->commit();

    		$this->just_added_favorite = $favorite_product_id;
    	}else{
    		$this->exist_favorite = $favorite_product_id;
    	}
    }
    
    /**
     * アンカーハッシュ文字列を取得する
     * アンカーキーをサニタイジングする
     *
     * @param string $anchor_key フォーム入力パラメーターで受け取ったアンカーキー
     * @return <type>
     */
    function getAnchorHash($anchor_key) {
        if ($anchor_key != '') {
            return "location.hash='#" . htmlspecialchars($anchor_key) . "';";
        } else {
            return '';
        }
    }
    /* ## 商品一覧画面でお気に入り商品登録 ADD END ## */
}
?>
