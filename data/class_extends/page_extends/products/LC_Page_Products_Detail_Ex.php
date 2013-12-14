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
require_once CLASS_REALDIR . 'pages/products/LC_Page_Products_Detail.php';

/**
 * LC_Page_Products_Detail のページクラス(拡張).
 *
 * LC_Page_Products_Detail をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Products_Detail_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Products_Detail_Ex extends LC_Page_Products_Detail {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        
        /*## パンくず ## ADD BEGIN*/
        $this->tpl_mainno = "products";
        /*## パンくず ## ADD END*/
        
        /*## 追加規格 ADD BEGIN ##*/
        if(USE_EXTRA_CLASS === true){
        	$objDb = new SC_Helper_DB_Ex();
        	$this->arrAllExtraClass = $objDb->lfGetAllExtraClass();
        	$this->arrAllExtraClassCat = $objDb->lfGetAllExtraClassCategory();
        }
        /*## 追加規格 ADD END ##*/
		
		/*## 商品ステータス2、ステータス3を追加 ADD BEGIN ##*/
        $masterData = new SC_DB_MasterData();
		$this->arrSTATUS2 = $masterData->getMasterData('mtb_status2');
		$this->arrSTATUS_IMAGE2 = $masterData->getMasterData('mtb_status_image2');
		$this->arrSTATUS3 = $masterData->getMasterData('mtb_status3');
		$this->arrSTATUS_IMAGE3 = $masterData->getMasterData('mtb_status_image3');
		/*## 商品ステータス2、ステータス3を追加 ADD END ##*/
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
        // 会員クラス
        $objCustomer = new SC_Customer_Ex();

        // パラメーター管理クラス
        $this->objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->arrForm = $this->lfInitParam($this->objFormParam);
        // ファイル管理クラス
        $this->objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);
        // ファイル情報の初期化
        $this->objUpFile = $this->lfInitFile($this->objUpFile);

        // プロダクトIDの正当性チェック
        $product_id = $this->lfCheckProductId($this->objFormParam->getValue('admin'),$this->objFormParam->getValue('product_id'));
        $this->mode = $this->getMode();

        $objProduct = new SC_Product_Ex();
        $objProduct->setProductsClassByProductIds(array($product_id));

        /*## 追加商品詳細情報 ADD BEGIN ##*/
        $this->arrProductOther = $this->lfGetProductOtherInfo($product_id);
        /*## 追加商品詳細情報 ADD END ##*/
        
        /*## 追加規格 ADD BEGIN ##*/
        if(USE_EXTRA_CLASS === true){
        	$this->arrForm = $this->lfInitExtraParam($this->objFormParam);
        }
        /*## 追加規格 ADD END ##*/
        
        // 規格1クラス名
        $this->tpl_class_name1 = $objProduct->className1[$product_id];

        // 規格2クラス名
        $this->tpl_class_name2 = $objProduct->className2[$product_id];

        // 規格1
        $this->arrClassCat1 = $objProduct->classCats1[$product_id];

        // 規格1が設定されている
        $this->tpl_classcat_find1 = $objProduct->classCat1_find[$product_id];
        // 規格2が設定されている
        $this->tpl_classcat_find2 = $objProduct->classCat2_find[$product_id];

        $this->tpl_stock_find = $objProduct->stock_find[$product_id];
        $this->tpl_product_class_id = $objProduct->classCategories[$product_id]['__unselected']['__unselected']['product_class_id'];
        $this->tpl_product_type = $objProduct->classCategories[$product_id]['__unselected']['__unselected']['product_type'];

        // 在庫が無い場合は、OnLoadしない。(javascriptエラー防止)
        if ($this->tpl_stock_find) {
            // 規格選択セレクトボックスの作成
            $this->js_lnOnload .= $this->lfMakeSelect();
        }
        
        $this->tpl_javascript .= 'classCategories = ' . SC_Utils_Ex::jsonEncode($objProduct->classCategories[$product_id]) . ';';
        $this->tpl_javascript .= 'function lnOnLoad(){' . $this->js_lnOnload . '}';
        $this->tpl_onload .= 'lnOnLoad();';

        // モバイル用 規格選択セレクトボックスの作成
        if(SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            $this->lfMakeSelectMobile($this, $product_id,$this->objFormParam->getValue('classcategory_id1'));
        }

        // 商品IDをFORM内に保持する
        $this->tpl_product_id = $product_id;

        switch ($this->mode) {
            case 'cart':
                $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam,
                                                    $this->tpl_classcat_find1,
                                                    $this->tpl_classcat_find2);
                /*## まとめ買いカスタマイズ ## ADD BEGIN*/
				/*
				 * FIXME:ここで呼ばれて正しい？
				 * 今のロジックは：
				 * 規格なしの場合、buyFlg変数がないため、そのまま続く
				 * 規格ある場合、プラグインが処理し、エラーなしの場合そのまま終わる、エラーがある場合はここに返却して表示する
				 */
            	$objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
            	$objPlugin->doAction('LC_Page_Products_Detail_action_cart', array($this));
            	/*## まとめ買いカスタマイズ ## ADD END*/

                if (count($this->arrErr) == 0) {
                    $objCartSess = new SC_CartSession_Ex();
                    $product_class_id = $this->objFormParam->getValue('product_class_id');
					
					/*## 追加規格 ## MDF BEGIN*/
                    if(USE_EXTRA_CLASS === true){
                    	$arrInfo = $this->lfGetProductExtraInfo();
                    	$objCartSess->addProduct($product_class_id, $this->objFormParam->getValue('quantity'), $arrInfo);
                    }else{
                    	$objCartSess->addProduct($product_class_id, $this->objFormParam->getValue('quantity'));
                    }
                    /*## 追加規格 ## MDF END*/

                    SC_Response_Ex::sendRedirect(CART_URLPATH);
                    SC_Response_Ex::actionExit();
                }
                break;
            case "add_favorite":
                // ログイン中のユーザが商品をお気に入りにいれる処理
                if ($objCustomer->isLoginSuccess() === true && $this->objFormParam->getValue('favorite_product_id') > 0 ) {
                    $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam);
                    if(count($this->arrErr) == 0){
                        if(!$this->lfRegistFavoriteProduct($this->objFormParam->getValue('favorite_product_id'),$objCustomer->getValue('customer_id'))){
                            $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
                            $objPlugin->doAction('LC_Page_Products_Detail_action_add_favorite', array($this));

                            SC_Response_Ex::actionExit();
                        }
                    }
                }
                break;

            case "add_favorite_sphone":
                // ログイン中のユーザが商品をお気に入りにいれる処理(スマートフォン用)
                if ($objCustomer->isLoginSuccess() === true && $this->objFormParam->getValue('favorite_product_id') > 0 ) {
                    $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam);
                    if(count($this->arrErr) == 0){
                        if($this->lfRegistFavoriteProduct($this->objFormParam->getValue('favorite_product_id'),$objCustomer->getValue('customer_id'))){
                            $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance($this->plugin_activate_flg);
                            $objPlugin->doAction('LC_Page_Products_Detail_action_add_favorite_sphone', array($this));

                            print 'true';
                            SC_Response_Ex::actionExit();
                        }
                    }
                    print 'error';
                    SC_Response_Ex::actionExit();
                }
                break;
                
            case 'select':
            case 'select2':
            case 'selectItem':
                /**
                 * モバイルの数量指定・規格選択の際に、
                 * $_SESSION['cart_referer_url'] を上書きさせないために、
                 * 何もせずbreakする。
                 */
                break;
                
            default:
                // カート「戻るボタン」用に保持
                $netURL = new Net_URL();
                $_SESSION['cart_referer_url'] = $netURL->getURL();
                break;
        }

        // モバイル用 ポストバック処理
        if(SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            switch($this->mode) {
                case 'select':
                    // 規格1が設定されている場合
                    if($this->tpl_classcat_find1) {
                        // templateの変更
                        $this->tpl_mainpage = "products/select_find1.tpl";
                        break;
                    }

                    // 数量の入力を行う
                    $this->tpl_mainpage = 'products/select_item.tpl';
                    break;
                    
                case 'select2':
                    $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam,$this->tpl_classcat_find1,$this->tpl_classcat_find2);

                    // 規格1が設定されていて、エラーを検出した場合
                    if($this->tpl_classcat_find1 and $this->arrErr['classcategory_id1']) {
                        // templateの変更
                        $this->tpl_mainpage = "products/select_find1.tpl";
                        break;
                    }

                    // 規格2が設定されている場合
                    if($this->tpl_classcat_find2) {
                        $this->arrErr = array();

                        $this->tpl_mainpage = "products/select_find2.tpl";
                        break;
                    }

                case 'selectItem':
                    $this->arrErr = $this->lfCheckError($this->mode,$this->objFormParam,$this->tpl_classcat_find1,$this->tpl_classcat_find2);

                    // 規格2が設定されていて、エラーを検出した場合
                    if ($this->tpl_classcat_find2 and $this->arrErr['classcategory_id2']) {
                        // templateの変更
                        $this->tpl_mainpage = 'products/select_find2.tpl';
                        break;
                    }

                    $value1 = $this->objFormParam->getValue('classcategory_id1');
                    
                    // 規格2が設定されている場合.
                    if (SC_Utils_Ex::isBlank($this->objFormParam->getValue('classcategory_id2')) == false){
                        $value2 = '#' . $this->objFormParam->getValue('classcategory_id2');
                    } else {
                        $value2 = '#0';
                    }
                    
                    if (strlen($value1) === 0) {
                        $value1 = '__unselected';
                    }

                    $this->tpl_product_class_id = $objProduct->classCategories[$product_id][$value1][$value2]['product_class_id'];

                    // この段階では、数量の入力チェックエラーを出させない。
                    unset($this->arrErr['quantity']);

                    // 数量の入力を行う
                    $this->tpl_mainpage = 'products/select_item.tpl';
                    break;

                case 'cart':
                    // この段階でエラーが出る場合は、数量の入力エラーのはず
                    if (count($this->arrErr)) {
                        // 数量の入力を行う
                        $this->tpl_mainpage = 'products/select_item.tpl';
                    }
                    break;

                default:
                    $this->tpl_mainpage = 'products/detail.tpl';
                    break;
            }
        }

        // 商品詳細を取得
        $this->arrProduct = $objProduct->getDetail($product_id);

        // サブタイトルを取得
        $this->tpl_subtitle = $this->arrProduct['name'];

        // 関連カテゴリを取得
        $this->arrRelativeCat = SC_Helper_DB_Ex::sfGetMultiCatTree($product_id);

        // 商品ステータスを取得
        $this->productStatus = $objProduct->getProductStatus($product_id);
        
        /*## 商品ステータス2、ステータス3を追加 ## ADD BEGIN*/
        $this->productStatus2 = $objProduct->getProductStatus2($product_id);
        $this->productStatus3 = $objProduct->getProductStatus2($product_id);
        /*## 商品ステータス2、ステータス3を追加 ADD END ##*/

        // 画像ファイル指定がない場合の置換処理
        $this->arrProduct['main_image']
            = SC_Utils_Ex::sfNoImageMain($this->arrProduct['main_image']);

        /*## 商品詳細カスタマイズ ## MDF BEGIN*/
//        $this->subImageFlag = $this->lfSetFile($this->objUpFile,$this->arrProduct,$this->arrFile);
		$this->subImageFlag = $this->lfSetFile($this->objUpFile,$this->arrProductOther,$this->arrFile);
		/*## 商品詳細カスタマイズ ## MDF END */
		
        //レビュー情報の取得
        $this->arrReview = $this->lfGetReviewData($product_id);

        //関連商品情報表示
        $this->arrRecommend = $this->lfPreGetRecommendProducts($product_id);

        // ログイン判定
        if ($objCustomer->isLoginSuccess() === true) {
            //お気に入りボタン表示
            $this->tpl_login = true;
            $this->is_favorite = SC_Helper_DB_Ex::sfDataExists('dtb_customer_favorite_products', 'customer_id = ? AND product_id = ?', array($objCustomer->getValue('customer_id'), $product_id));
        }
                
        /*## 在庫表示 ADD BEGIN ##*/
        if(USE_PRODUCT_CLASS_STOCK_TABLE === true){
        	$objDb = new SC_Helper_DB_Ex();
        	$objDb->lfSetClassCatStockRows($product_id, $this);
        }
		/*## 在庫表示 ADD END ##*/
		
        /*## SEO管理 ## ADD BEGIN*/
        $this->lfSetPageInfo($product_id);
        /*## SEO管理 ## ADD END*/
        
    }
        
    /*## 追加商品詳細情報 ADD BEGIN ##*/
    function lfGetProductOtherInfo($product_id){
    	$objQuery = new SC_Query();
    	$objDb = new SC_Helper_DB_Ex();
    	$sql = "SELECT * FROM dtb_products WHERE product_id = ?";
    	$arrRet = $objQuery->getall($sql,array($product_id));
    	
    	if(is_array($arrRet[0])){
    		foreach($arrRet[0] as $name => $val){
    			$objDb->sfChangeConsts($arrRet[0][$name]);
    		}
    	}
        /*## 追加規格 ADD BEGIN ##*/
    	if(USE_EXTRA_CLASS === true){
    		$objProduct = new SC_Product_Ex();
    		$this->arrExtraClass = $objProduct->getExtraClass($product_id, $objQuery);
    	}
        /*## 追加規格 ADD END ##*/    
    	return $arrRet[0];
    }
    /*## 追加商品詳細情報 ADD END ##*/
    
    function lfSetPageInfo($product_id){
		/*## SEO管理 ## ADD BEGIN*/
    	if(constant("USE_SEO") === true){
    		$objLayout = new SC_Helper_PageLayout_Ex();
    		$objLayout->sfSetPageInfo($this, $this->arrProductOther);
    	}
		/*## SEO管理 ## ADD END*/
    	
		/*## パンくず ## ADD BEGIN*/
		$http_ref = $_SERVER["HTTP_REFERER"];

		$this->is_navis_html_url = strpos($http_ref, ".html");
			
		$this->tpl_navis = array();
		//商品一覧からの場合
		if(substr_count($http_ref, "products/list") > 0){

			$arr_temp = parse_url($http_ref);
			if(!empty($arr_temp["query"])){
				parse_str($arr_temp["query"], $query);
			}
			if($this->is_navis_html_url){
				ereg("products/list([0-9]+)", $http_ref, $query_html);
				$query["category_id"] = $query_html[1];
			}
			if ($query['mode'] == 'search') {
				$this->tpl_navis[] = array(
    				"url" => $http_ref,
    				"label" => "検索結果"
    				);
			} elseif (empty($query["category_id"])) {
				$this->tpl_navis[] = array(
    				"url" => $http_ref,
    				"label" => "全商品"
    				);
			} else {
				// 親カテゴリの上層カテゴリを全て取得
				$objQuery = new SC_Query();
				$level = $objQuery->get("level", "dtb_category", "category_id=?", array($query["category_id"]));
				$level_bk = $pref_level = $level;
				$where = "T{$level}.category_id = ? AND T{$level}.del_flg = 0";
				while($level > 0){
					$cols[] = "T{$level}.category_name AS category_name{$level}, T{$level}.category_id AS category_id{$level}";
					$from = "dtb_category T{$level}";
					if($level < $pref_level){
						$from .= " ON T{$level}.category_id = T{$pref_level}.parent_category_id AND T{$level}.del_flg = 0";
					}
					$froms[] = $from;
					$pref_level = $level;
					$level--;
				}
				$sqlcols = join(",", $cols);
				$sqlfroms = join(" LEFT JOIN ", $froms);

				$arrRet = $objQuery->select($sqlcols, $sqlfroms, $where, array($query["category_id"]));

				// 親カテゴリを2階層まで表示する
				if(is_array($arrRet[0])){
					// より上層の親カテゴリが先に表示
					for($i=1; $i<=$level_bk; $i++){
						$this->tpl_navis[] = array(
    						"url" => SC_Utils_Ex::sfGetFormattedUrl(P_LIST_URLPATH, $arrRet[0]["category_id{$i}"]),
    						"label"=> $arrRet[0]["category_name{$i}"]
						);
					}
					// 前ページのurlを最近の親ナビに設定
					$this->tpl_navis[$level_bk-1]["url"] = $http_ref;
				}
			}
		}
		else{
			$this->tpl_navis[0] = array(
    			"url" => SC_Utils_Ex::sfGetFormattedUrl(P_LIST_URLPATH),
    			"label" => "全商品");
		}
		$this->tpl_navis[] = array("label" => $this->arrProduct['name']);
		/*## パンくず ## ADD END*/
    }
    
   /* 登録済み関連商品の読み込み */
    function lfPreGetRecommendProducts($product_id) {
        $objProduct = new SC_Product_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $objQuery->setOrder("rank DESC");
        $arrRecommendData = $objQuery->select("recommend_product_id, comment", "dtb_recommend_products", "product_id = ?", array($product_id));

        $arrRecommendProductId = array();
        foreach($arrRecommendData as $recommend){
            $arrRecommendProductId[] = $recommend["recommend_product_id"];
            $arrRecommendData[$recommend["recommend_product_id"]] = $recommend['comment'];
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrProducts = $objProduct->getListByProductIds($objQuery, $arrRecommendProductId);
        
/*## 追加商品詳細情報 ADD BEGIN ##*/
        $arrPdctStatus = $objProduct->getProductStatus($arrRecommendProductId);
/*## 追加商品詳細情報 ADD END ##*/
        
        //取得している並び順で並び替え
        // FIXME SC_Productあたりにソート処理はもってくべき
        $arrProducts2 = array();
        foreach($arrProducts as $item) {
            $arrProducts2[ $item['product_id'] ] = $item;
        }

        $arrRecommend = array();
        foreach($arrRecommendProductId as $product_id) {
            $arrProducts2[$product_id]['comment'] = $arrRecommendData[$product_id];
/*## 追加商品詳細情報 ADD BEGIN ##*/
            $arrProducts2[$product_id]['product_status'] = $arrPdctStatus[$product_id];
/*## 追加商品詳細情報 ADD END ##*/            
            $arrRecommend[] = $arrProducts2[$product_id];
        }

        return $arrRecommend;
    }    
 
    /*## 追加規格 ADD BEGIN ##*/
    function lfInitExtraParam(&$objFormParam) {
    	if(is_array($this->arrExtraClass)){
    		foreach($this->arrExtraClass as $extra_cls){
        		$objFormParam->addParam($this->arrAllExtraClass[$extra_cls["extra_class_id"]], "extra_classcategory_id{$extra_cls["extra_class_id"]}", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
    		}
    	}
    	
        // 値の取得
        $objFormParam->setParam($_REQUEST);
        // 入力値の変換
        $objFormParam->convParam();
        // 入力情報を渡す
        return $objFormParam->getFormParamList();    	
    }
    
	function lfGetProductExtraInfo(){
		$arrInfo = array();
		$arrRet =  $this->objFormParam->getHashArray();

		//追加規格
		$array = array();
		foreach($this->arrExtraClass as $extra_cls){
			$id = $extra_cls["extra_class_id"];
			$array[$id] = $arrRet["extra_classcategory_id{$id}"];
		}
		$arrInfo["extra_classcategory_id"] = $array;

		return $arrInfo;
	}
	/*## 追加規格 END BEGIN ##*/
}
?>
