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
require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order_Pdf.php';
require_once CLASS_EX_REALDIR . 'SC_OrderDetail_Fpdf_Ex.php';

/**
 * 帳票出力 のページクラス(拡張).
 *
 * LC_Page_Admin_Order_Pdf をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_Pdf_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Admin_Order_Pdf_Ex extends LC_Page_Admin_Order_Pdf {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        
        $this->arrType[1]  = '受注明細書';
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
     *
     * PDFの作成
     * @param SC_FormParam $objFormParam
     */
    function createPdf(&$objFormParam) {

        $arrErr = $this->lfCheckError($objFormParam);
        $arrRet = $objFormParam->getHashArray();

        $this->arrForm = $arrRet;
        // エラー入力なし
        if (count($arrErr) == 0) {
        	switch($this->arrForm["type"]){
        		case "0":
        			$objFpdf = new SC_Fpdf_Ex($arrRet['download'], $arrRet['title']);
        			foreach ($arrRet['order_id'] AS $key => $val) {
        				$arrPdfData = $arrRet;
        				$arrPdfData['order_id'] = $val;
        				$objFpdf->setData($arrPdfData);
        			}
        			$objFpdf->createPdf();
        			break;
        		case "1":
        			$objFpdf = new SC_OrderDetail_Fpdf_Ex($arrRet['download'], $arrRet['title']);
        			foreach ($arrRet['order_id'] AS $key => $val) {
        				$arrPdfData = $arrRet;
        				$arrPdfData['order_id'] = $val;
        				$objFpdf->setData($arrPdfData);
        			}
        			$objFpdf->createPdf();
        			break;
        	}
            
            return true;
        } else {
            return $arrErr;
        }
    }
}
