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
require_once CLASS_REALDIR . 'pages/admin/products/LC_Page_Admin_Products_Category.php';

/**
 * カテゴリ管理 のページクラス(拡張).
 *
 * LC_Page_Admin_Products_Category をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Products_Category_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Admin_Products_Category_Ex extends LC_Page_Admin_Products_Category {

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
        $objDb      = new SC_Helper_DB_Ex();
        $objFormParam = new SC_FormParam_Ex();
        
        /*## CATEGORY 情報 ADD BEGIN ##*/
        // アップロードファイル情報の初期化
		$objUpFile = new SC_UploadFile_Ex(IMAGE_TEMP_REALDIR, IMAGE_SAVE_REALDIR);        
        if(constant("USE_CATEGORY_INFO") === true){
        	$this->lfInitFile($objUpFile);
        }
        /*## CATEGORY 情報 ADD END ##*/        
        
		/*## カテゴリお勧め商品 ADD BEGIN ##*/
        $this->objRcmdFormParam = new SC_FormParam_Ex();
        $this->initRecommendParam();
        $this->objRcmdFormParam->setParam($_POST);
        $this->objRcmdFormParam->convParam();
        /*## カテゴリお勧め商品 ADD END ##*/
                
        // 入力パラメーター初期化
        $this->initParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        switch($this->getMode()) {
        // カテゴリ登録/編集実行
        case 'edit':
        	/*## CATEGORY 情報 ADD BEGIN ##*/
        	$objUpFile->setHiddenFileList($_POST);
        	/*## CATEGORY 情報 ADD END ##*/
        	
            $this->doEdit($objFormParam, $objUpFile);     
            
            /*## カテゴリお勧め商品 ADD BEGIN ##*/ 
            if(count($this->arrErr)){
            	$this->arrForm = $objFormParam->getHashArray();
            	$this->lfLoadTempRecommend();
            }      
            /*## カテゴリお勧め商品 ADD END ##*/ 
            break;
        // 入力ボックスへ編集対象のカテゴリ名をセット
        case 'pre_edit':
            $this->doPreEdit($objFormParam, $objUpFile);
            break;
        // カテゴリ削除
        case 'delete':
            $this->doDelete($objFormParam, $objDb);
            break;
        // 表示順を上へ
        case 'up':
            $this->doUp($objFormParam);
            break;
        // 表示順を下へ
        case 'down':
            $this->doDown($objFormParam);
            break;
        // XXX 使われていないコード？
        case 'moveByDnD':
            // DnDしたカテゴリと移動先のセットを分解する
            $keys = explode("-", $_POST['keySet']);
            if ($keys[0] && $keys[1]) {
                $objQuery =& SC_Query_Ex::getSingletonInstance();
                $objQuery->begin();

                // 移動したデータのrank、level、parent_category_idを取得
                $rank   = $objQuery->get('rank', "dtb_category", "category_id = ?", array($keys[0]));
                $level  = $objQuery->get('level', "dtb_category", "category_id = ?", array($keys[0]));
                $parent = $objQuery->get("parent_category_id", "dtb_category", "category_id = ?", array($keys[0]));

                // 同一level内のrank配列を作成
                $objQuery->setOption("ORDER BY rank DESC");
                if ($level == 1) {
                    // 第1階層の時
                    $arrRet = $objQuery->select('rank', "dtb_category", "level = ?", array($level));
                } else {
                    // 第2階層以下の時
                    $arrRet = $objQuery->select('rank', "dtb_category", "level = ? AND parent_category_id = ?", array($level, $parent));
                }
                for ($i = 0; $i < sizeof($arrRet); $i++) {
                    $rankAry[$i + 1] = $arrRet[$i]['rank'];
                }

                // 移動したデータのグループ内データ数
                $my_count = $this->lfCountChilds($objQuery, "dtb_category", "parent_category_id", "category_id", $keys[0]);
                if ($rankAry[$keys[1]] > $rank) {
                    // データが今の位置より上がった時
                    $up_count = $rankAry[$keys[1]] - $rank;
                    $decAry   = $objQuery->select("category_id", "dtb_category", "level = ? AND rank > ? AND rank <= ?", array($level, $rank, $rankAry[$keys[1]]));
                    foreach($decAry as $value){
                        // 上のグループから減算
                        $this->lfDownRankChilds($objQuery, "dtb_category", "parent_category_id", "category_id", $value["category_id"], $my_count);
                    }
                    // 自分のグループに加算
                    $this->lfUpRankChilds($objQuery, "dtb_category", "parent_category_id", "category_id", $keys[0], $up_count);
                } else if($rankAry[$keys[1]] < $rank) {
                    // データが今の位置より下がった時
                    $down_count = 0;
                    $incAry     = $objQuery->select("category_id", "dtb_category", "level = ? AND rank < ? AND rank >= ?", array($level, $rank, $rankAry[$keys[1]]));
                    foreach ($incAry as $value) {
                        // 下のグループに加算
                        $this->lfUpRankChilds($objQuery, "dtb_category", "parent_category_id", "category_id", $value["category_id"], $my_count);
                        // 合計減算値
                        $down_count += $this->lfCountChilds($objQuery, "dtb_category", "parent_category_id", "category_id", $value["category_id"]);
                    }
                    // 自分のグループから減算
                    $this->lfDownRankChilds($objQuery, "dtb_category", "parent_category_id", "category_id", $keys[0], $down_count);
                }
                $objQuery->commit();
            }
            break;
        // カテゴリツリークリック時
        case 'tree':
            break;
         // CSVダウンロード
        case 'csv':
        	// CSVを送信する
        	$objCSV = new SC_Helper_CSV_Ex();

        	$objCSV->sfDownloadCsv('5', '', array(), '', true);
        	SC_Response_Ex::actionExit();
        	break;
/*## カテゴリお勧め商品 ADD BEGIN ##*/            
        case 'recommend_select':
        	$objUpFile->setHiddenFileList($_POST);
        	
        	$this->arrForm = $objFormParam->getHashArray();
        	$this->lfLoadTempRecommend();
        	break;            
/*## カテゴリお勧め商品 ADD END ##*/
        	
/*## CATEGORY 情報 ADD BEGIN ##*/
        	// 画像のアップロード
        case 'upload_image':
        case 'delete_image':
        	if(constant("USE_CATEGORY_INFO") === true){
        		$objUpFile->setHiddenFileList($_POST);
        		
        		$this->arrForm = $objFormParam->getHashArray();
        		$image_key = $this->arrForm['image_key'];
        		switch($this->getMode()){
        			case 'upload_image':
        				// ファイルを一時ディレクトリにアップロード
        				$this->arrErr[$image_key] = $objUpFile->makeTempFile($image_key, IMAGE_RENAME);
        				break;
        			case 'delete_image':
        				$objUpFile->deleteFile($_POST['image_key']);
        				break;
        		}
         	}
         	$this->lfLoadTempRecommend();
        	break;
/*## CATEGORY 情報 ADD END ##*/
        	
        default:
            break;
        }
 
 		/*## CATEGORY 情報 ADD BEGIN ##*/       
        if(constant("USE_CATEGORY_INFO") === true){
        	$this->arrHiddenForm = $objUpFile->getHiddenFileList();
         // 入力画面表示設定
        	$this->arrFile = $objUpFile->getFormFileList(IMAGE_TEMP_URLPATH, IMAGE_SAVE_URLPATH);
        }
        /*## CATEGORY 情報 ADD END ##*/
        
        $parent_category_id = $objFormParam->getValue('parent_category_id');
        // 空の場合は親カテゴリを0にする
        if (empty($parent_category_id)) {
            $parent_category_id = 0;
        }
        // 親カテゴリIDの保持
        $this->arrForm['parent_category_id'] = $parent_category_id;
        // カテゴリ一覧を取得
        $this->arrList = $this->findCategoiesByParentCategoryId($parent_category_id);
        // カテゴリツリーを取得
        $this->arrTree = $objDb->sfGetCatTree($parent_category_id);
        // ぱんくずの生成
        $arrBread = array();
        $objDb->findTree($this->arrTree, $parent_category_id, $arrBread);
        $this->tpl_bread_crumbs = SC_Utils_Ex::jsonEncode($arrBread);
    }

    
    /**
     * カテゴリの削除を実行する.
     *
     * 下記の場合は削除を実施せず、エラーメッセージを表示する.
     *
     * - 削除対象のカテゴリに、子カテゴリが1つ以上ある場合
     * - 削除対象のカテゴリを、登録商品が使用している場合
     *
     * カテゴリの削除は、物理削除で行う.
     *
     * @param SC_FormParam $objFormParam
     * @param SC_Helper_Db $objDb
     * @return void
     */
    function doDelete(&$objFormParam, &$objDb) {
        $category_id = $objFormParam->getValue('category_id');
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // 子カテゴリのチェック
        $where = "parent_category_id = ? AND del_flg = 0";
        $count = $objQuery->count("dtb_category", $where, array($category_id));
        if ($count > 0) {
             $this->arrErr['category_name'] = "※ 子カテゴリーが存在するため削除できません。<br/>";
             return;
        }
        // 登録商品のチェック
        $table = "dtb_product_categories AS T1 LEFT JOIN dtb_products AS T2 ON T1.product_id = T2.product_id";
        $where = "T1.category_id = ? AND T2.del_flg = 0";
        $count = $objQuery->count($table, $where, array($category_id));
        if ($count > 0) {
            $this->arrErr['category_name'] = "※ カテゴリー内に商品が存在するため削除できません。<br/>";
            return;
        }

        // ランク付きレコードの削除(※処理負荷を考慮してレコードごと削除する。)
        $objQuery->begin();
        $objDb->sfDeleteRankRecord("dtb_category", "category_id", $category_id, "", true);
        
		/*## カテゴリお勧め商品 ADD BEGIN ##*/
        if(defined("CATEGORY_RECOMMEND_PRODUCT_MAX") && 
        	CATEGORY_RECOMMEND_PRODUCT_MAX != false){
        	$objQuery->delete("dtb_category_recommend", "category_id=?", $category_id);
        }
		/*## カテゴリお勧め商品 ADD END ##*/
        
        $objQuery->commit();        
    }
    
	/**
	 * 編集対象のカテゴリ名を, 入力ボックスへ表示する.
	 *
	 * @param SC_FormParam $objFormParam
	 * @return void
	 */
    function doPreEdit(&$objFormParam, $objUpFile) {
        $category_id = $objFormParam->getValue('category_id');

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        // 編集対象のカテゴリ名をDBより取得する
        $where = 'category_id = ?';
        $arrRes = $objQuery->getRow('*', 'dtb_category', $where, array($category_id));

        $objFormParam->setParam($arrRes);

        $this->arrForm = $objFormParam->getHashArray();
        
		/*## CATEGORY 情報 ## ADD BEGIN*/
		if(constant("USE_CATEGORY_INFO") === true){
			$objUpFile->setDBFileList($arrRet[0]);
		}
		/*## CATEGORY 情報 MDF END ##*/
		
		/*## カテゴリお勧め商品 ADD BEGIN ##*/
		$this->lfPreGetRecommend($category_id);
		/*## カテゴリお勧め商品 ADD END ##*/	
    }


	/**
	 * カテゴリの編集を実行する.
	 *
	 * 下記の場合は, 編集を実行せず、エラーメッセージを表示する
	 *
	 * - カテゴリ名がすでに使用されている場合
	 *
	 * @param SC_FormParam $objFormParam
	 * @return void
	 */
    
    function doEdit(&$objFormParam, $objUpFile) {
    	$category_id = $objFormParam->getValue('category_id');

    	// 追加か
    	$add = strlen($category_id) === 0;

    	// エラーチェック
    	$this->arrErr = $this->checkError($objFormParam, $add);

    	// エラーがない場合、追加・更新処理
    	if (empty($this->arrErr)) {
    		$objQuery =& SC_Query_Ex::getSingletonInstance();
    		
    		$arrCategory = $objFormParam->getDbArray();

    		/*## CATEGORY 情報 ## ADD BEGIN*/
    		if(constant("USE_CATEGORY_INFO") === true){
    			unset($arrCategory["image_key"]);
    			$arrCategory = array_merge($arrCategory, $objUpFile->getDBFileList());

    			// 一時ファイルを本番ディレクトリに移動する
    			$objUpFile->moveTempFile();
    			$objUpFile->temp_file = array();
    		}
    		/*## CATEGORY 情報 ## ADD END*/

    		// 追加
    		if ($add) {
    			$category_id = $this->registerCategory($arrCategory, $objQuery);
    		}
    		// 更新
    		else {
    			unset($arrCategory['category_id']);
    			$this->updateCategory($category_id, $arrCategory, $objQuery);
    		}
    		
    		/*## カテゴリお勧め商品 ADD BEGIN ##*/
    		$this->lfRegistRecommend($category_id, $objQuery);
    		/*## カテゴリお勧め商品 ADD END ##*/
    	}
    	// エラーがある場合、入力値の再表示
    	else {
    		$this->arrForm = $objFormParam->getHashArray();
    	}
    }
 
    /**
     * カテゴリを登録する
     *
     * @param integer 親カテゴリID
     * @param string カテゴリ名
     * @param integer 作成者のID
     * @return void
     */
	function registerCategory($arrCategory, &$objQuery = null) {
	    if(empty($objQuery)){
    		$objQuery =& SC_Query_Ex::getSingletonInstance();
        	$objQuery->begin();
        	$commit_flg = 1;
        }

        $parent_category_id = $arrCategory['parent_category_id'];
        $rank = null;
        if ($parent_category_id == 0) {
            // ROOT階層で最大のランクを取得する。
            $where = 'parent_category_id = ?';
            $rank = $objQuery->max('rank', 'dtb_category', $where, array($parent_category_id)) + 1;
        } else {
            // 親のランクを自分のランクとする。
            $where = 'category_id = ?';
            $rank = $objQuery->get('rank', 'dtb_category', $where, array($parent_category_id));
            // 追加レコードのランク以上のレコードを一つあげる。
            $sqlup = 'UPDATE dtb_category SET rank = (rank + 1) WHERE rank >= ?';
            $objQuery->exec($sqlup, array($rank));
        }

        $where = 'category_id = ?';
        // 自分のレベルを取得する(親のレベル + 1)
        $level = $objQuery->get('level', 'dtb_category', $where, array($parent_category_id)) + 1;

        $arrCategory['create_date'] = 'CURRENT_TIMESTAMP';
        $arrCategory['update_date'] = 'CURRENT_TIMESTAMP';
        $arrCategory['creator_id']  = $_SESSION['member_id'];
        $arrCategory['rank']        = $rank;
        $arrCategory['level']       = $level;
        $arrCategory['category_id'] = $objQuery->nextVal('dtb_category_category_id');

        $objQuery->insert('dtb_category', $arrCategory);

        if($commit_flg)
        	$objQuery->commit();    // トランザクションの終了
        	
        return $arrCategory['category_id'];
    }

    
    /**
     * カテゴリを更新する
     *
     * @param integer $category_id 更新対象のカテゴリID
     * @param array 更新する カラム名 => 値 の連想配列
     * @return void
     */
    function updateCategory($category_id, $arrCategory, &$objQuery = null) {
    	if(empty($objQuery)){
    		$objQuery =& SC_Query_Ex::getSingletonInstance();
    		$objQuery->begin();
    		$commit_flg = 1;
    	}
    	$arrCategory['update_date']   = 'CURRENT_TIMESTAMP';
        $where = "category_id = ?";
        $objQuery->update("dtb_category", $arrCategory, $where, array($category_id));
        if($commit_flg)
        	$objQuery->commit();
    }
        
	/**
	 * パラメーターの初期化を行う
	 *
	 * @param SC_FormParam $objFormParam
	 * @return void
	 */
	function initParam(&$objFormParam) {
		$objFormParam->addParam("親カテゴリーID", "parent_category_id", null, null, array());
		$objFormParam->addParam("カテゴリーID", "category_id", null, null, array());
		$objFormParam->addParam("カテゴリー名", "category_name", STEXT_LEN, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));

		/*## CATEGORY 情報 ## ADD BEGIN*/
		if(constant("USE_CATEGORY_INFO") === true){
			$objFormParam->addParam("カテゴリー説明", "category_info", LLTEXT_LEN, "KVa", array("SPTAB_CHECK","MAX_LENGTH_CHECK"));
			$objFormParam->addParam("カテゴリー画像ALT", "category_main_image_alt", LTEXT_LEN, "KVa", array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
			$objFormParam->addParam("image_key", "image_key", "", "", array());
		}
		/*## CATEGORY 情報 ## ADD END*/
				
		/*## SEO管理 ADD BEGIN ##*/
		if(constant("USE_SEO") === true){
			$objFormParam->addParam('ページタイトル', 'title', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
			$objFormParam->addParam('H1テキスト', 'h1', SMTEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
			$objFormParam->addParam('メタタグ:Description', 'description', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
			$objFormParam->addParam('メタタグ:Keywords', 'keyword', STEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
		}
		/*## SEO管理 ADD END ##*/
		
		/*## カテゴリ一覧でカゴ表示管理 ADD BEGIN ##*/
		$objFormParam->addParam('カゴ非表示 ', 'hide_list_cart', INT_LEN, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
		/*## カテゴリ一覧でカゴ表示管理 ADD END ##*/
	}
	
	/*## CATEGORY 情報 ## ADD BEGIN*/
    /* ファイル情報の初期化 */
    function lfInitFile(&$objUpFile) {
        $objUpFile->addFile("カテゴリー画像", 'category_main_image', array('jpg', 'gif', 'png'),IMAGE_SIZE, true, CAT_MAINIMAGE_WIDTH, CAT_MAINIMAGE_HEIGHT);
    }	

    
    /**
     * 縮小画像生成
     *
     * @param object $objUpFile SC_UploadFileインスタンス
     * @param string $from_key 元画像ファイルキー
     * @param string $to_key 縮小画像ファイルキー
     * @param boolean $forced
     * @return void
     */
    function lfMakeScaleImage(&$objUpFile, $from_key, $to_key, $forced = false){
        $arrImageKey = array_flip($objUpFile->keyname);
        $from_path = "";

        if($objUpFile->temp_file[$arrImageKey[$from_key]]) {
            $from_path = $objUpFile->temp_dir . $objUpFile->temp_file[$arrImageKey[$from_key]];
        } elseif($objUpFile->save_file[$arrImageKey[$from_key]]){
            $from_path = $objUpFile->save_dir . $objUpFile->save_file[$arrImageKey[$from_key]];
        }

        if(file_exists($from_path)) {
            // 生成先の画像サイズを取得
            $to_w = $objUpFile->width[$arrImageKey[$to_key]];
            $to_h = $objUpFile->height[$arrImageKey[$to_key]];

            if($forced) $objUpFile->save_file[$arrImageKey[$to_key]] = "";

            if(empty($objUpFile->temp_file[$arrImageKey[$to_key]])
                    && empty($objUpFile->save_file[$arrImageKey[$to_key]])) {
                // リネームする際は、自動生成される画像名に一意となるように、Suffixを付ける
                $dst_file = $objUpFile->lfGetTmpImageName(IMAGE_RENAME, "", $objUpFile->temp_file[$arrImageKey[$from_key]]) . $this->lfGetAddSuffix($to_key);
                $path = $objUpFile->makeThumb($from_path, $to_w, $to_h, $dst_file);
                $objUpFile->temp_file[$arrImageKey[$to_key]] = basename($path);
            }
        }
    }    
    /*## CATEGORY 情報 ## ADD END*/
        

	/*## カテゴリお勧め商品 ADD BEGIN ##*/
	function initRecommendParam(){
		if(defined("CATEGORY_RECOMMEND_PRODUCT_MAX") &&
		CATEGORY_RECOMMEND_PRODUCT_MAX > 0){
			for($i = 1; $i <= CATEGORY_RECOMMEND_PRODUCT_MAX; $i++){
				$this->objRcmdFormParam->addParam("おすすめ商品{$i}", "recommend_id{$i}", INT_LEN, "n", array("NUM_CHECK"));
				$this->objRcmdFormParam->addParam("おすすめ商品コメント{$i}", "recommend_comment{$i}", LTEXT_LEN, "KVa", array("NUM_CHECK"));
			}
		}
	}


	function lfLoadTempRecommend(){
		if(defined("CATEGORY_RECOMMEND_PRODUCT_MAX")
		&& CATEGORY_RECOMMEND_PRODUCT_MAX > 0){
			$array = $this->objRcmdFormParam->getHashArray();
			
			for($i = 1; $i <= CATEGORY_RECOMMEND_PRODUCT_MAX; $i++){
				if(!empty($array["recommend_id{$i}"])){
					$this->arrRecommend[$i] = $this->lfGetRecommendProduct($array["recommend_id{$i}"]);
				}
			}
		}
		
	}
	
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
			$cols = "T1.comment, T1.rank, T1.product_id";
			$from = "dtb_category_recommend T1";
			$objQuery->setOrder("rank");
			$arrRet = $objQuery->select($cols, $from, "T1.category_id=?", array($category_id));

			//おすすめ情報
			if(is_array($arrRet)){
				foreach($arrRet as $no =>$row){
					$arrDetail = $objProduct->getDetail($row["product_id"]);
					unset($row["product_id"]);
					$arrDetail = array_merge($row, $arrDetail);
					$this->arrRecommend[$row["rank"]] = $arrDetail;
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * おすすめ商品の情報を取得する
	 *
	 * @param $category_id
	 */
	function lfGetRecommendProduct($product_id){
		$arrRet = array();
		if(empty($product_id)) return $arrRet;

		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$cols = "product_id, name, main_list_image, main_list_comment";
		$from = "dtb_products";

		$arrRet = $objQuery->select($cols, $from, "product_id=? AND del_flg<>1", array($product_id));
		$arrRet = $arrRet[0];
		return $arrRet;
	}

	/**
	 * カテゴリのおすすめ情報を登録する
	 *
	 * @param $category_id
	 */
	function lfRegistRecommend($category_id, &$objQuery = null){

		if(defined("CATEGORY_RECOMMEND_PRODUCT_MAX")
		&& CATEGORY_RECOMMEND_PRODUCT_MAX > 0
		&& !empty($category_id)){
				
			$arrParam = $this->objRcmdFormParam->getHashArray();

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

				for($i = 1; $i <= CATEGORY_RECOMMEND_PRODUCT_MAX; $i++){
					if(!empty($arrParam["recommend_id{$i}"])){
						$sqlval["product_id"] = $arrParam["recommend_id{$i}"];
						$sqlval["comment"] = $arrParam["recommend_comment{$i}"];
						$sqlval["rank"] = $i;
						$objQuery->insert("dtb_category_recommend", $sqlval);
					}
				}

				if($commit_flg)
					$objQuery->commit();

			}catch(Eception $e){
				if($commit_flg)
					$objQuery->rollback();
				throw $e;
			}
		}
	}
	/*## カテゴリお勧め商品 ADD END ##*/
}
?>
