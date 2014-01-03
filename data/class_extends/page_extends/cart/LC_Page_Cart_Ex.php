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
require_once CLASS_REALDIR . 'pages/cart/LC_Page_Cart.php';

/**
 * カート のページクラス(拡張).
 *
 * LC_Page_Cart をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Cart_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Cart_Ex extends LC_Page_Cart {

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
    	parent::action();
    	
    	/*# 見積表作成 ADD BEGIN #*/
        $objCartSess = new SC_CartSession_Ex();

        switch ($this->mode) {
            case 'pdf':
                // カート商品が1件以上存在する場合
                if (count($this->cartItems) > 0) {
                    $this->createPdf();
                }
                break;
        }
        /*# 見積表作成 ADD END #*/
    }
    
    /*# 見積表作成 ADD BEGIN #*/
    /**
     *
     * PDFの作成
     */
    function createPdf() {
    	$objFpdf = new SC_Fpdf_Ex(1, "見積表");
    	
    	$arrPdfData = array();
    	$arrPdfData["detail"] = $this->cartItems;
    	$arrPdfData["calculate"]["data"] = $this->arrData;
    	$arrPdfData["calculate"]["total_inctax"] = $this->tpl_total_inctax;
    	$arrPdfData["calculate"]["total_tax"] = $this->tpl_total_tax;
    	$arrPdfData["calculate"]["total_point"] = $this->tpl_total_point;
    	$arrPdfData["calculate"]["deliv_free"] = $this->tpl_deliv_free;  
    	$arrPdfData["calculate"]["all_total_inctax"] = $this->tpl_all_total_inctax;
    	
    	$objFpdf->setData($arrPdfData);
    	$objFpdf->createPdf();
    	return true;
    }
    /*# 見積表作成 ADD END #*/
    
}
