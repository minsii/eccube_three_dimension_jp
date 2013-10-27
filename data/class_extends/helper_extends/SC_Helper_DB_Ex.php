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
require_once CLASS_REALDIR . 'helper/SC_Helper_DB.php';

/**
 * DB関連のヘルパークラス(拡張).
 *
 * LC_Helper_DB をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author LOCKON CO.,LTD.
 * @version $Id: SC_Helper_DB_Ex.php 21867 2012-05-30 07:37:01Z nakanishi $
 */
class SC_Helper_DB_Ex extends SC_Helper_DB {

	/**
	 * 文字列内に"<!--{$PARAM_NAME}-->"の形で挿入されているシステムパラメータをパラメータの値に変換する
	 *
	 * @param $string
	 */
	function sfChangeConsts(&$string ){

		if(!empty($string)){
			preg_match_all('/<!--{\$([^}]+)}-->/', $string, $matches);
			$TPL_URLPATH = SC_Helper_PageLayout_Ex::getUserDir($device_type_id, SC_Display_Ex::detectDevice());
			
			if(is_array($matches[1])){
				$search = array();
				foreach($matches[1] as $mat){
					$mat = str_replace("smarty.const.", "", $mat);
					if(defined($mat)){
						$search['<!--{$smarty.const.'.$mat.'}-->'] = constant($mat);
					}
					if($mat == "TPL_URLPATH"){
						$search['<!--{$TPL_URLPATH}-->'] = $TPL_URLPATH;
					}
				}
				$keys = array_keys($search);
				$values = array_values($search);
				$string = str_replace($keys, $values, $string);
			}
		}
	}	
	
    /**
     * 追加規格一覧を取得する
     * 
     */
    function lfGetAllExtraClass(){
    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	$objQuery->setOrder("rank DESC");
    	$arrRet = $objQuery->select("extra_class_id, name", "dtb_extra_class");
    	
    	$arrExtraClass = array();
    	if(is_array($arrRet)){
    		foreach($arrRet as $row){
    			$arrExtraClass[$row["extra_class_id"]] = $row["name"]; 
    		}
    	}
    	return $arrExtraClass;
    }
    
    /**
     * 追加規格一覧を取得する
     * 
     */
    function lfGetAllExtraClassCategory(){
    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	$objQuery->setOrder("rank DESC");
    	$arrRet = $objQuery->select("extra_class_id, extra_classcategory_id, name", "dtb_extra_classcategory");
    	
    	$arrExtraClassCat = array();
    	if(is_array($arrRet)){
    		foreach($arrRet as $row){
    			$arrExtraClassCat[$row["extra_class_id"]][$row["extra_classcategory_id"]] = $row["name"]; 
    		}
    	}
    	return $arrExtraClassCat;
    }    
}
?>
