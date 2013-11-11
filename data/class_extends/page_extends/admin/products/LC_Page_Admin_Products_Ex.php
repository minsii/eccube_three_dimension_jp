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
require_once CLASS_REALDIR . 'pages/admin/products/LC_Page_Admin_Products.php';

/**
 * 商品管理 のページクラス(拡張).
 *
 * LC_Page_Admin_Products をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Products_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Admin_Products_Ex extends LC_Page_Admin_Products {

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
		$objProduct = new SC_Product_Ex();
		$objQuery =& SC_Query_Ex::getSingletonInstance();

		// パラメーター情報の初期化
		$this->lfInitParam($objFormParam);
		$objFormParam->setParam($_POST);
		$this->arrHidden = $objFormParam->getSearchArray();
		$this->arrForm = $objFormParam->getFormParamList();

		switch ($this->getMode()) {
			case 'delete':
				// 商品、子テーブル(商品規格)、会員お気に入り商品の削除
				$this->doDelete('product_id = ?', array($objFormParam->getValue('product_id')));
				// 件数カウントバッチ実行
				$objDb->sfCountCategory($objQuery);
				$objDb->sfCountMaker($objQuery);
				// 削除後に検索結果を表示するため breakしない

				/*## 商品マスタ一覧で公開状態変更 ADD BEGIN ##*/
			case 'change_disp':
				// 'delete'もここに来る
				if(USE_PRODUCT_MASTER_DISP_EDIT === true && $this->getMode() == 'change_disp'){
					$objProduct->changeDisp($objFormParam->getValue('product_id'), $this->arrDISP, $objQuery);
					$_POST["mode"] = "search";
				}
				/*## 商品マスタ一覧で公開状態変更 ADD END ##*/
							
				/*## 商品マスタ一覧で在庫変更 ADD BEGIN ##*/
			case 'change_all_stock':
				if(USE_PRODUCT_MASTER_STOCK_EDIT === true){
					$this->changeProductsStock();
					$_POST["mode"] = "search";
				}
				/*## 商品マスタ一覧で在庫変更 ADD END ##*/
				
				// 検索パラメーター生成後に処理実行するため breakしない
			case 'csv':
			case 'delete_all':
			case 'search':
				$objFormParam->convParam();
				$objFormParam->trimParam();
				$this->arrErr = $this->lfCheckError($objFormParam);
				$arrParam = $objFormParam->getHashArray();

				if (count($this->arrErr) == 0) {
					$where = 'del_flg = 0';
					$arrWhereVal = array();
					foreach ($arrParam as $key => $val) {
						if ($val == '') {
							continue;
						}
						$this->buildQuery($key, $where, $arrWhereVal, $objFormParam, $objDb);
					}

					$order = 'update_date DESC';

					/* -----------------------------------------------
					 * 処理を実行
					 * ----------------------------------------------- */
					switch ($this->getMode()) {
						// CSVを送信する。
						case 'csv':
							$objCSV = new SC_Helper_CSV_Ex();
							// CSVを送信する。正常終了の場合、終了。
							$objCSV->sfDownloadCsv(1, $where, $arrWhereVal, $order, true);
							SC_Response_Ex::actionExit();

							// 全件削除(ADMIN_MODE)
						case 'delete_all':
							$this->doDelete($where, $arrWhereVal);
							break;

							// 検索実行
						default:
							// 行数の取得
							$this->tpl_linemax = $this->getNumberOfLines($where, $arrWhereVal);
							// ページ送りの処理
							$page_max = SC_Utils_Ex::sfGetSearchPageMax($objFormParam->getValue('search_page_max'));
							// ページ送りの取得
							$objNavi = new SC_PageNavi_Ex($this->arrHidden['search_pageno'],
							$this->tpl_linemax, $page_max,
                                                          'fnNaviSearchPage', NAVI_PMAX);
							$this->arrPagenavi = $objNavi->arrPagenavi;

							// 検索結果の取得
							$this->arrProducts = $this->findProducts($where, $arrWhereVal, $page_max, $objNavi->start_row,
							$order, $objProduct);

							// 各商品ごとのカテゴリIDを取得
							if (count($this->arrProducts) > 0) {
								foreach ($this->arrProducts as $key => $val) {
									$this->arrProducts[$key]['categories'] = $objDb->sfGetCategoryId($val['product_id'], 0, true);
									$objDb->g_category_on = false;
								}
							}
					}
				}
				break;
		}

		// カテゴリの読込
		list($this->arrCatKey, $this->arrCatVal) = $objDb->sfGetLevelCatList(false);
		$this->arrCatList = $this->lfGetIDName($this->arrCatKey, $this->arrCatVal);

	}

	/**
	 * 商品を検索する.
	 *
	 * @param string $where 検索条件の WHERE 句
	 * @param array $arrValues 検索条件のパラメーター
	 * @param integer $limit 表示件数
	 * @param integer $offset 開始件数
	 * @param string $order 検索結果の並び順
	 * @param SC_Product $objProduct SC_Product インスタンス
	 * @return array 商品の検索結果
	 */
	function findProducts($where, $arrValues, $limit, $offset, $order, &$objProduct) {
		$objQuery =& SC_Query_Ex::getSingletonInstance();

		// 読み込む列とテーブルの指定
		$col = 'product_id, name, main_list_image, status, product_code_min, product_code_max, price02_min, price02_max, stock_min, stock_max, stock_unlimited_min, stock_unlimited_max, update_date';
		$from = $objProduct->alldtlSQL();

		$objQuery->setLimitOffset($limit, $offset);
		$objQuery->setOrder($order);

		/*## 商品マスタ一覧で規格表示 ADD BEGIN ##*/
		if(USE_PRODUCT_MASTER_SHOW_CLASS === true){
			// 規格有無チェック
			$col .= ", classcategory_id1_min, classcategory_id2_min";
			$from .= " JOIN (
                        SELECT product_id AS dtb_cls__product_id,
                            MIN(classcategory_id1) AS classcategory_id1_min,
                            MIN(classcategory_id2) AS classcategory_id2_min
                        FROM dtb_products_class 
                        WHERE del_flg = 0
                        GROUP BY product_id
                    ) AS Tpdctclass ON alldtl.product_id = Tpdctclass.dtb_cls__product_id";
			
			if(USE_EXTRA_CLASS === true){
				// 追加規格有無チェック
				$col .= ", extra_class_count";
				$from .= " LEFT JOIN (
                        SELECT product_id AS dtb_extracls__product_id,
                            COUNT(product_extra_class_id) AS extra_class_count
                        FROM dtb_products_extra_class
                        GROUP BY product_id
                    ) AS Textraclass ON alldtl.product_id = Textraclass.dtb_extracls__product_id";
			}
			
			$arrRet = $objQuery->select($col, $from, $where, $arrValues);
			if(is_array($arrRet)){
				foreach($arrRet as $no =>$row){
					// 規格名表示
					$objQuery->setOrder("");
					$col = "T2.name";
					$from = "dtb_classcategory T1 LEFT JOIN dtb_class T2 USING(class_id)";
					if($row["classcategory_id1_min"] > 0){
						$arrRet[$no]["class_id1_name"] = $objQuery->get($col, $from, "classcategory_id = ?", array($row["classcategory_id1_min"]));
					}
					if($row["classcategory_id2_min"] > 0){
						$arrRet[$no]["class_id2_name"] = $objQuery->get($col, $from, "classcategory_id = ?", array($row["classcategory_id2_min"]));
					}
						
					if(USE_EXTRA_CLASS === true){
						// 追加規格名表示
						$arrExtraClass= $objProduct->getExtraClass($row["product_id"], $objQuery);
						
						$arrRet[$no]["extra_class_names"] = null;
						if(is_array($arrExtraClass) && count($arrExtraClass) > 0){
							$arrRet[$no]["extra_class_names"] = array();
							foreach($arrExtraClass as $extra){
								$arrRet[$no]["extra_class_names"][] = $extra["extra_class_name"];
							}
						}
					}
				}
			}

			return $arrRet;
		}
		else{ 
			return $objQuery->select($col, $from, $where, $arrValues);
		}
		/*## 商品マスタ一覧で規格有無表示 ADD END ##*/
	}
	
	/*## 商品マスタ一覧で在庫変更 ADD BEGIN ##*/
	function changeProductsStock(){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$objProduct = new SC_Product_Ex();
		$objQuery->begin();

		foreach($_POST as $name => $value){
			if(preg_match("/^product_stock_(\d+)/", $name, $matchs)){
				$stock = $value;
				$stock_unlimited = null;

				if($value == "-1"){
					$stock = 0;
					$stock_unlimited = 1;
				}
				$objProduct->changeStock($matchs[1], 0, 0, $stock, $stock_unlimited, $objQuery);
			}
		}

		// 件数カウントバッチ実行
		$objDb = new SC_Helper_DB_Ex();
		$objDb->sfCountCategory($objQuery);
		$objDb->sfCountMaker($objQuery);

		$objQuery->commit();
	}
	/*## 商品マスタ一覧で在庫変更 ADD END ##*/

}
