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
require_once CLASS_REALDIR . 'pages/admin/products/LC_Page_Admin_Products_ProductRank.php';

/**
 * 商品並べ替え のページクラス(拡張).
 *
 * LC_Page_Admin_Products_ProductRank をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Products_ProductRank_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $
 */
class LC_Page_Admin_Products_ProductRank_Ex extends LC_Page_Admin_Products_ProductRank {

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

		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$objDb = new SC_Helper_DB_Ex();

		$this->tpl_pageno = isset($_POST['pageno']) ? $_POST['pageno'] : '';

		// 通常時は親カテゴリを0に設定する。
		$this->arrForm['parent_category_id'] =
		isset($_POST['parent_category_id']) ? $_POST['parent_category_id'] : 0;
		$this->arrForm['product_id'] =
		isset($_POST['product_id']) ? $_POST['product_id'] : '';

		switch ($this->getMode()) {
			case 'up':
				$this->lfRankUp($objDb, $this->arrForm['parent_category_id'], $this->arrForm['product_id']);
				break;
			case 'down':
				$this->lfRankDown($objDb, $this->arrForm['parent_category_id'], $this->arrForm['product_id']);
				break;
			case 'move':
				$this->lfRankMove($objDb, $this->arrForm['parent_category_id'], $this->arrForm['product_id']);
				break;
			case 'tree':
				// カテゴリの切替は、ページ番号をクリアする。
				$this->tpl_pageno = '';
				break;
			case 'renumber':
				$this->lfRenumber($this->arrForm['parent_category_id']);
				break;
				/*## 商品一括並び替え ADD BEGIN ##*/
			case 'bulk_rank':
				if(USE_PRODUCT_BULK_RANK === true){
					$this->lfBulkRankMove($this->arrForm['parent_category_id']);
				}
				break;
				/*## 商品一括並び替え ADD END ##*/
			default:
				break;
		}

		$this->arrTree = $objDb->sfGetCatTree($this->arrForm['parent_category_id']);
		$this->arrProductsList = $this->lfGetProduct($this->arrForm['parent_category_id']);
		$arrBread = array();
		$objDb->findTree($this->arrTree, $this->arrForm['parent_category_id'], $arrBread);
		$this->tpl_bread_crumbs = SC_Utils_Ex::jsonEncode($arrBread);

	}

	/*## 商品一括並び替え ADD BEGIN ##*/
	function lfBulkRankMove($parent_category_id){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$where = "category_id = ? AND product_id = ?";
		$moved = false;
		$objQuery->begin();

		try{
			$max = $objQuery->count("dtb_product_categories T1 INNER JOIN dtb_products T2 USING(product_id)", 
				"T1.category_id = ? AND T2.del_flg = 0", array($parent_category_id));
//			print "max $max<br />";

			$matches = null;
			foreach($_POST as $key => $val){
				if(preg_match("/^pos-(\d+)/", $key, $matches)){
					if( $matches[1] > 0 && ($val > 0 || $val === '0')){
						$sqlval = array("rank" => $max - $val + 1);
						$arrval = array($parent_category_id, $matches[1]);

						$objQuery->update("dtb_product_categories", $sqlval, $where, $arrval);
						$moved = true;
//						print "update $parent_category_id-$matches[1] => ". ($max - $val + 1)."<br />";
					}
				}
			}

			// reorder
			if($moved){
				$objQuery->setOrder("rank DESC, product_id DESC");
				$allProducts = $objQuery->select("product_id, rank", "dtb_product_categories", "category_id = ?", array($parent_category_id));

//				var_dump($allProducts);

				foreach($allProducts as $no => $product){
					$rank = $max - $no;
					if($product["rank"] != $rank){
						$sqlval = array("rank" => $rank);
						$arrval = array($parent_category_id, $product["product_id"]);
						$objQuery->update("dtb_product_categories", $sqlval, $where, $arrval);
//						print "reorder update {$parent_category_id}-{$product['product_id']}" .
//							" {$product['rank']} => {$rank}<br />";
					}
				}
			}

		}catch(Exception $e){
			$objQuery->rollback();
			throw $e;
		}

		$objQuery->commit();
		return 0;
	}
	/*## 商品一括並び替え ADD END ##*/
}
