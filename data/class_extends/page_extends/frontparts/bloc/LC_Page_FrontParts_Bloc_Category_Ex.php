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
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc_Category.php';

/**
 * カテゴリ のページクラス(拡張).
 *
 * LC_Page_FrontParts_Bloc_Category をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_FrontParts_Bloc_Category_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_FrontParts_Bloc_Category_Ex extends LC_Page_FrontParts_Bloc_Category {

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

        // モバイル判定
        switch (SC_Display_Ex::detectDevice()) {
            case DEVICE_TYPE_MOBILE:
                // メインカテゴリの取得
                $this->arrCat = $this->lfGetMainCat(true);
                break;
            default:
                // 選択中のカテゴリID
                $this->tpl_category_id = $this->lfGetSelectedCategoryId($_GET);
                // カテゴリツリーの取得
                $this->arrTree = $this->lfGetCatTree($this->tpl_category_id, false);
                break;
        }
    }
    
    /**
     * カテゴリツリーの取得.
     *
     * @param array $arrParentCategoryId 親カテゴリの配列
     * @param boolean $count_check 登録商品数をチェックする場合はtrue
     * @return array $arrRet カテゴリツリーの配列を返す
     */
    function lfGetCatTree($arrParentCategoryId, $count_check = false) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();
        /*## 全カテゴリ表示 ## MDF BEGIN*/
//		$col = '*';
		$col = 'dtb_category.*, dtb_category_total_count.product_count';
		/*## 全カテゴリ表示 ## MDF END*/
        $from = 'dtb_category left join dtb_category_total_count ON dtb_category.category_id = dtb_category_total_count.category_id';
        // 登録商品数のチェック
        if ($count_check) {
            $where = 'del_flg = 0 AND product_count > 0';
        } else {
            $where = 'del_flg = 0';
        }
        $objQuery->setOption('ORDER BY rank DESC');
        $arrRet = $objQuery->select($col, $from, $where);
        foreach ($arrParentCategoryId as $category_id) {
            $arrParentID = $objDb->sfGetParents(
                'dtb_category',
                'parent_category_id',
                'category_id',
                $category_id
            );
            $arrBrothersID = SC_Utils_Ex::sfGetBrothersArray(
                $arrRet,
                'parent_category_id',
                'category_id',
                $arrParentID
            );
            $arrChildrenID = SC_Utils_Ex::sfGetUnderChildrenArray(
                $arrRet,
                'parent_category_id',
                'category_id',
                $category_id
            );
            $this->root_parent_id[] = $arrParentID[0];
            $arrDispID = array_merge($arrBrothersID, $arrChildrenID);
            foreach ($arrRet as &$arrCategory) {
                if (in_array($arrCategory['category_id'], $arrDispID)) {
                    $arrCategory['display'] = 1;
                }
            }
        }
        return $arrRet;
    }
}
