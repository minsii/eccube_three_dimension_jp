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

require_once CLASS_REALDIR . 'SC_PageNavi.php';


class SC_PageNavi_Ex extends SC_PageNavi {
	
	/*## ページ繰り最終レコード ADD BEGIN ##*/
	var $end_row;     // 最終レコード
	
	    // コンストラクタ
    function __construct($now_page, $all_row, $page_row, $func_name, $navi_max = NAVI_PMAX, $urlParam = '', $display_number = true) {
    	parent::__construct($now_page, $all_row, $page_row, $func_name, $navi_max, $urlParam, $display_number);
    	
    	// 最終ページ
    	if($this->now_page == $this->max_page){
    		$this->end_row = $this->all_row;
    	}else{
    		$this->end_row = $this->start_row + $page_row;
    	}
    	$this->arrPagenavi['start_row'] = $this->start_row + 1;
    	$this->arrPagenavi['end_row'] = $this->end_row;
    }
    /*## ページ繰り最終レコード ADD END ##*/
}