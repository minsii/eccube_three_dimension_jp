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

require_once CLASS_REALDIR . 'SC_CustomerList.php';

class SC_CustomerList_Ex extends SC_CustomerList {
	
    function __construct($array, $mode = '') {
        parent::__construct($array, $mode);

        /*## 会員登録項目カスタマイズ ADD BEGIN ##*/
        // 事業者区分
        if (!isset($this->arrSql['search_company_type'])) $this->arrSql['search_company_type'] = '';
        if (is_array($this->arrSql['search_company_type']) 
        		&& count($this->arrSql['search_company_type']) > 0) {
        	$where = join(array_fill(0, count($this->arrSql['search_company_type']), "?"), ", ");
        	$this->setWhere("customer_id IN (SELECT customer_id FROM dtb_customer_company_type WHERE company_type_id IN ($where))");
        	foreach ($this->arrSql['search_company_type'] as $data) {
        		$this->arrVal[] = $data;
        	}
        }
        /*## 会員登録項目カスタマイズ ADD END ##*/
    }
	
}
