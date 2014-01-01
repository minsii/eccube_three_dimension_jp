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

require_once CLASS_REALDIR . 'pages/LC_Page.php';

class LC_Page_Ex extends LC_Page {
    function init() {
        // モジュール -> 本体のページへ移動
        if ($_SERVER['PHP_SELF'] != "/shopping/load_payment_module.php" && $_SERVER['PHP_SELF'] != "/resize_image.php" && !$this->page_mdl_smbc && isset($_SESSION['MDL_SMBC']['order_id']) && (empty($_POST['mode']) || $_POST['mode'] == 'return')){
            $temp_order_id = $_SESSION['order_id'];
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objPurchase->rollbackOrder($_SESSION['order_id'], ORDER_CANCEL, true);
            $_SESSION['order_id'] = $temp_order_id;

            unset($_SESSION['MDL_SMBC']);
        }
        parent::init();
    }

	function process(){
		/*## ログイン情報全ページ使用 ADD BEGIN ##*/
		// ログインチェック
		$objCustomer = new SC_Customer_Ex();
		$this->tpl_is_login = $objCustomer->isLoginSuccess(true);
		/*## ログイン情報全ページ使用 ADD END ##*/

		parent::process();
	}
}

?>
