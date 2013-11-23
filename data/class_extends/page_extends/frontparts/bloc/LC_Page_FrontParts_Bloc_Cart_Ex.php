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
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc_Cart.php';

/**
 * カート のページクラス(拡張).
 *
 * LC_Page_カート をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_FrontParts_Bloc_Cart_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_FrontParts_Bloc_Cart_Ex extends LC_Page_FrontParts_Bloc_Cart {

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
     * カートの情報を取得する
     *
     * @param SC_CartSession $objCart カートセッション管理クラス
     * @return array カートデータ配列
     */
    function lfGetCartData(&$objCart) {
    	$arrCartList = parent::lfGetCartData($objCart);

    	$cartItems = $objCart->getAllCartList();
    	$arrCartKeys = $objCart->getKeys();
    	foreach ($arrCartKeys as $cart_key) {
    		foreach($cartItems[$cart_key] as $product){
    			$arrCartList["products"][] = $product;
    		}
    	}
        return $arrCartList;
    }
}
