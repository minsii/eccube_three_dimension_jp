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
require_once CLASS_REALDIR . 'util/SC_Utils.php';

/**
 * 各種ユーティリティクラス(拡張).
 *
 * SC_Utils をカスタマイズする場合はこのクラスを使用する.
 *
 * @package Util
 * @author LOCKON CO.,LTD.
 * @version $Id: SC_Utils_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class SC_Utils_Ex extends SC_Utils {
    
    /*## マルチページ繰り ADD BEGIN ##*/
    /**
     * アンカーハッシュ文字列を取得する
     * アンカーキーをサニタイジングする
     * 
     * @param string $anchor_key フォーム入力パラメーターで受け取ったアンカーキー
     * @return <type> 
     */
    function getAnchorHash($anchor_key) {
        if($anchor_key != "") {
            return "location.hash='#" . htmlspecialchars($anchor_key) . "'";
        } else {
            return "";
        }
    }
    /*## マルチページ繰り ADD END ##*/

    /*## 追加規格 ADD BEGIN ##*/
    
    /* 追加規格分類の件数取得 */
    function sfGetExtraClassCatCount() {
        $sql = "SELECT count(dtb_extra_class.extra_class_id) AS count, dtb_extra_class.extra_class_id ";
        $sql.= "FROM dtb_extra_class INNER JOIN dtb_extra_classcategory ON dtb_extra_class.extra_class_id = dtb_extra_classcategory.extra_class_id ";
        $sql.= "WHERE dtb_extra_class.del_flg = 0 AND dtb_extra_classcategory.del_flg = 0 ";
        $sql.= "GROUP BY dtb_extra_class.extra_class_id, dtb_extra_class.name";
        $objQuery = new SC_Query_Ex();
        $arrList = $objQuery->getAll($sql);
        // キーと値をセットした配列を取得
        $arrRet = SC_Utils_Ex::sfArrKeyValue($arrList, 'extra_class_id', 'count');

        return $arrRet;
    }
    /*## 追加規格 ADD END ##*/     
    
    /*## サイトHTML化 ADD BEGIN ##*/
    /**
     * フォーマットURLにパラメータを入れ替えた文字列を取得
     * パラメータは1個から複数個指定できる
     * 
     * @param $format
     * @param $param1
     * @param $paramn
     */
    function sfGetFormattedUrl(){
    	$numargs = func_num_args();
    	if($numargs < 1){
    		return "";
    	}

    	$format = func_get_arg(0);
    	if($numargs == 1){
    		$param = "";
    		$search = "%p";
    	}
    	else{
    		$param = array();
    		$search = array();
    		for($i=1; $i<$numargs; $i++){
    			$param[] = func_get_arg($i);
    			$search[] = "%p";
    		}
    	}
    	$url = str_replace($search, $param, $format);
    	return $url;
    }
    /*## サイトHTML化 ADD END ##*/
}
?>
