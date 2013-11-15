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
require_once CLASS_REALDIR . 'pages/admin/products/LC_Page_Admin_Products_UploadCSVCategory.php';

/**
 * CSV アップロード のページクラス(拡張)
 *
 * LC_Page_Admin_Products_UploadCSV をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $$Id: LC_Page_Admin_Products_UploadCSVCategory_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $$
 */
class LC_Page_Admin_Products_UploadCSVCategory_Ex extends LC_Page_Admin_Products_UploadCSVCategory {

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
	 * カテゴリ登録を行う.
	 *
	 * FIXME: 登録の実処理自体は、LC_Page_Admin_Products_Categoryと共通化して欲しい。
	 *
	 * @param SC_Query $objQuery SC_Queryインスタンス
	 * @param string|integer $line 処理中の行数
	 * @return integer カテゴリID
	 */
	function lfRegistCategory($objQuery, $line, &$objFormParam) {
		// 登録データ対象取得
		$arrList = $objFormParam->getHashArray();
		// 登録時間を生成(DBのCURRENT_TIMESTAMPだとcommitした際、すべて同一の時間になってしまう)
		$arrList['update_date'] = $this->lfGetDbFormatTimeWithLine($line);

		// 登録情報を生成する。
		// テーブルのカラムに存在しているもののうち、Form投入設定されていないデータは上書きしない。
		$sqlval = SC_Utils_Ex::sfArrayIntersectKeys($arrList, $this->arrRegistColumn);

		// 必須入力では無い項目だが、空文字では問題のある特殊なカラム値の初期値設定
		$sqlval = $this->lfSetCategoryDefaultData($sqlval);

		if ($sqlval['category_id'] != '') {
			// 同じidが存在すればupdate存在しなければinsert
			$where = 'category_id = ?';
			$category_exists = $objQuery->exists('dtb_category', $where, array($sqlval['category_id']));
			if ($category_exists) {
				// UPDATEの実行
				$where = 'category_id = ?';
				$objQuery->update('dtb_category', $sqlval, $where, array($sqlval['category_id']));
			} else {
				$sqlval['create_date'] = $arrList['update_date'];
				// 新規登録
				$category_id = $this->registerCategory($sqlval['parent_category_id'],
				$sqlval['category_name'],
				$_SESSION['member_id'],
				$sqlval['category_id']);
				
				/*## カテゴリUPLOAD項目カスタム ADD BEGIN ##*/
                $category_id = $sqlval['category_id'];
				unset($sqlval["category_id"]);
				unset($sqlval["category_name"]);
				unset($sqlval["parent_category_id"]);
			
            	$where = "category_id = ?";
            	$objQuery->update("dtb_category", $sqlval, $where, array($category_id));
            	/*## カテゴリUPLOAD項目カスタム ADD END ##*/  
			}
			$category_id = $sqlval['category_id'];
			// TODO: 削除時処理
		} else {
			// 新規登録
			$category_id = $this->registerCategory($sqlval['parent_category_id'],
			$sqlval['category_name'],
			$_SESSION['member_id']);
			
			/*## カテゴリUPLOAD項目カスタム ADD BEGIN ##*/
			unset($sqlval["category_id"]);
			unset($sqlval["category_name"]);
			unset($sqlval["parent_category_id"]);
			
            $where = "category_id = ?";
            $objQuery->update("dtb_category", $sqlval, $where, array($category_id));
            /*## カテゴリUPLOAD項目カスタム ADD END ##*/ 
		}

		/*## カテゴリUPLOAD項目カスタム ADD BEGIN ##*/
		if(isset($arrList["category_recommend_product_id1"]) &&
			is_int(CATEGORY_RECOMMEND_PRODUCT_MAX) && CATEGORY_RECOMMEND_PRODUCT_MAX > 0){
				
			$arrRecommendProductId = array();
			$arrRecommendProductComment = array();
			for($i = 1; $i <= CATEGORY_RECOMMEND_PRODUCT_MAX; $i++){
				$arrRecommendProductId[] = $arrList["category_recommend_product_id{$i}"];
				$arrRecommendProductComment[] = $arrList["category_recommend_product_comment{$i}"];
			}
			$this->lfRegistRecommend($category_id, $arrRecommendProductId, $arrRecommendProductComment, $objQuery);
		}
		/*## カテゴリUPLOAD項目カスタム ADD END ##*/ 

		return $category_id;
	}

	/*## カテゴリUPLOAD項目カスタム ADD BEGIN ##*/
	/**
	 * カテゴリのおすすめ情報を登録する
	 *
	 * @param $category_id
	 */
	function lfRegistRecommend($category_id, $arrRecommendProductId, $arrRecommendProductComment, &$objQuery = null){

		if(!empty($category_id)){
			if(empty($objQuery)){
				$objQuery =& SC_Query_Ex::getSingletonInstance();
				$commit_flg = 1;
			}
			try{
				if($commit_flg)
					$objQuery->begin();
					
				$sqlval = array();
				$sqlval["category_id"] = $category_id;

				$objQuery->delete("dtb_category_recommend", "category_id = ?", array($category_id));

				$i = 1;
				foreach($arrRecommendProductId as $no => $pid){
					if(!empty($pid)){
						$sqlval["product_id"] = $pid;
						$sqlval["comment"] = $arrRecommendProductComment[$no];
						$sqlval["rank"] = $i++;
						$objQuery->insert("dtb_category_recommend", $sqlval);
					}
				}

				if($commit_flg)
					$objQuery->commit();

			}catch(Exception $e){
				if($commit_flg)
					$objQuery->rollback();
				throw $e;
			}
		}
	}
	/*## カテゴリUPLOAD項目カスタム ADD END ##*/ 
}
