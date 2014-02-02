<?php
/*
 * Shiro8ClassesInCart
 * Copyright (C) 2012 Shiro8. All Rights Reserved.
 * http://www.shiro8.net/
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


/* 
 * カテゴリ毎にコンテンツを設定する事ができます。
 */
class Shiro8ClassesInCart extends SC_Plugin_Base {

    /**
     * コンストラクタ
     * プラグイン情報(dtb_plugin)をメンバ変数をセットします.
     * @param array $arrSelfInfo dtb_pluginの情報配列
     * @return void
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * インストール時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function install($arrPlugin) {
        
        // ロゴファイルをhtmlディレクトリにコピーします.
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/logo.png", PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . "/logo.png");
        
        // 規格情報取得ページをhtmlディレクトリにコピーします.
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/plg_shiro8ClassesInCart_get_class.php", PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . "/plg_shiro8ClassesInCart_get_class.php");
    }

    /**
     * 削除時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function uninstall($arrPlugin) {
        
    }
    
    /**
     * 有効にした際に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function enable($arrPlugin) {
        
    }

    /**
     * 無効にした際に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function disable($arrPlugin) {
        
    }
    
    /**
     * カートに商品を登録をします.
     * @param LC_Page_Products_Detail $objPage カート登録.
     * @return void
     */
    function cart_set($objPage) {
        $post = $_POST;
        switch ($post['mode']) {
            // カート投入時
            case 'cart':
            
                if (is_array($_POST['buyFlg'])) {
            
                    $objPage->arrErr = $this->lfCheckError($objPage->objFormParam,
                                            $objPage->tpl_classcat_find1,
                                            $objPage->tpl_classcat_find2);

                    /*## まとめ買いカスタマイズ ## ADD BEGIN*/
					$arrCheckedBuyFlg = array();
					foreach ($_POST['buyFlg'] as $buyFlg) {
						$arrCheckedBuyFlg[$buyFlg] = 1;
					}
					$objPage->arrCheckedBuyFlg = $arrCheckedBuyFlg;
					
					// パラメーター情報の初期化
					$objPage->arrPluginForm = array();
					foreach ($_POST['buyFlg'] as $rowNum) {
						$objPage->arrPluginForm['quantity_'.$rowNum] = $_POST['quantity_'.$rowNum];
					}
                    /*## まとめ買いカスタマイズ ## ADD END*/
                                            
                    if (count($objPage->arrErr) == 0) {
                        $objCartSess = new SC_CartSession_Ex();
                        //選択した規格の数だけ繰り返し
                        foreach ($_POST['buyFlg'] as $buyFlg) {

                        	/*## まとめ買いカスタマイズ ## MDF BEGIN*/
//                            $buyFlgs = explode('_', $buyFlg);
                            $rowNum = $buyFlg;
							/*## まとめ買いカスタマイズ ## MDF END*/
                            
                            $classcategory_id1 = $_POST['classcategory_id1_'.$rowNum];
                            $classcategory_id2 = $_POST['classcategory_id2_'.$rowNum];

                            if (!empty($_POST['gmo_oneclick'])) {
                                $objCartSess->delAllProducts();
                            }

                            // 規格1が設定されていない場合
                            if(!$objPage->tpl_classcat_find1) {
                                $classcategory_id1 = '0';
                            }

                            // 規格2が設定されていない場合
                            if(!$objPage->tpl_classcat_find2) {
                                $classcategory_id2 = '0';
                            }
                            
                            $objQuery =& SC_Query_Ex::getSingletonInstance();
                            $arrCat = $objQuery->getRow("product_class_id", "dtb_products_class", "product_id = ? AND classcategory_id1 = ? AND classcategory_id2 = ? ", 
                                                        array($_POST['product_id'], $classcategory_id1, $classcategory_id2));
                            if ($arrCat["product_class_id"] != '') {
                                $product_class_id = $arrCat["product_class_id"];
                            } else {
                                $product_class_id = 0;
                            }

                            $objCartSess->setPrevURL($_SERVER['REQUEST_URI']);
                            $objCartSess->addProduct($product_class_id, $_POST['quantity_'.$rowNum]);
                        }
                        //カートの中へ遷移
                        SC_Response_Ex::sendRedirect(CART_URLPATH);
                        SC_Response_Ex::actionExit();
                    }
                }
                break;
            default:
                break;
        }
    }
    
    /**
     * prefilterコールバック関数
     * テンプレートの変更処理を行います.
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        // SC_Helper_Transformのインスタンスを生成.
        $objTransform = new SC_Helper_Transform($source);
        // 呼び出し元テンプレートを判定します.
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE: // モバイル
            case DEVICE_TYPE_SMARTPHONE: // スマホ
                break;
            case DEVICE_TYPE_PC: // PC
                // 商品詳細画面
                if (strpos($filename, 'products/detail.tpl') !== false) {
                    // 規格選択リストのレイアウトを変更します.
                    $template_dir = PLUGIN_UPLOAD_REALDIR . $this->arrSelfInfo['plugin_code'] . '/';
                    $objTransform->select('div.classlist', NULL, false)->replaceElement(file_get_contents($template_dir . 'shiro8_classesincart_products_detail_add.tpl'));
                    $objTransform->select('dl.quantity', NULL, false)->removeElement();
                    /*## まとめ買いカスタマイズ ## MDF BEGIN*/
                    $objTransform->select('div.allbuy', NULL, false)->replaceElement(file_get_contents($template_dir . 'shiro8_classesincart_allbuybtn_replace.tpl'));
//                    $objTransform->select('div#cartbtn_default', NULL, false)->replaceElement(file_get_contents($template_dir . 'shiro8_classesincart_cartbtn_replace.tpl'));
                    /*## まとめ買いカスタマイズ ## MDF END*/  
                }
                break;
            case DEVICE_TYPE_ADMIN: // 管理画面
            default:
                break;
        }

        // 変更を実行します
        $source = $objTransform->getHTML();
    }
    
    /* 入力内容のチェック */
    function lfCheckError(&$objFormParam,$tpl_classcat_find1 = null ,$tpl_classcat_find2 = null) {

        // 入力データを渡す。
        $arrRet =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex();
        //$objErr->arrErr = $objFormParam->checkError();

        //選択した規格の数だけ繰り返し
        if (is_array($_POST['buyFlg'])) {

            foreach ($_POST['buyFlg'] as $buyFlg) {
            	/*## まとめ買いカスタマイズ ## MDF BEGIN*/
            	//$buyFlgs = explode('_', $buyFlg);
            	$rowNum = $buyFlg;
            	/*## まとめ買いカスタマイズ ## MDF END*/

                $objErr->doFunc(array("個数", "quantity_".$rowNum), array("EXIST_CHECK", "ZERO_CHECK", "NUM_CHECK"));

                // 複数項目チェック
                if ($tpl_classcat_find1) {
                    $objErr->doFunc(array("規格1", "classcategory_id1_".$rowNum), array("EXIST_CHECK"));
                }
                if ($tpl_classcat_find2) {
                    $objErr->doFunc(array("規格2", "classcategory_id2_".$rowNum), array("EXIST_CHECK"));
                }
            }
        }
           	
        return $objErr->arrErr;
    }
}

?>