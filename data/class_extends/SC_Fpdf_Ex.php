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

require_once CLASS_REALDIR . 'SC_Fpdf.php';

class SC_Fpdf_Ex extends SC_Fpdf {
	

    function setOrderData() {
        $arrOrder = array();
        // DBから受注情報を読み込む
        $this->lfGetOrderData($this->arrData['order_id']);

        // 購入者情報
        $text = '〒 '.$this->arrDisp['order_zip01'].' - '.$this->arrDisp['order_zip02'];
        $this->lfText(23, 43, $text, 10); //購入者郵便番号
        $text = $this->arrPref[$this->arrDisp['order_pref']] . $this->arrDisp['order_addr01'];
        $this->lfText(27, 47, $text, 10); //購入者都道府県+住所1
        $this->lfText(27, 51, $this->arrDisp['order_addr02'], 10); //購入者住所2

        $text = $this->arrDisp['order_company'].' ['.$this->arrDisp['order_company_no'].']';
        $this->lfText(27, 59, $text, 11); //事業所名、事業所番号        
        
        $text = $this->arrDisp['order_name01'].'　'.$this->arrDisp['order_name02'].'　様';
        $this->lfText(27, 64, $text, 11); //購入者氏名

        // お届け先情報
        $this->SetFont('SJIS', '', 10);
        $this->lfText(25, 125, SC_Utils_Ex::sfDispDBDate($this->arrDisp['create_date']), 10); //ご注文日
        $this->lfText(25, 135, $this->arrDisp['order_id'], 10); //注文番号

        $this->SetFont('Gothic', 'B', 15);
        $this->Cell(0, 10, $this->tpl_title, 0, 2, 'C', 0, '');  //文書タイトル（納品書・請求書）
        $this->Cell(0, 66, '', 0, 2, 'R', 0, '');
        $this->Cell(5, 0, '', 0, 0, 'R', 0, '');
        $this->SetFont('SJIS', 'B', 15);
        $this->Cell(67, 8, number_format($this->arrDisp['payment_total']).' 円', 0, 2, 'R', 0, '');
        $this->Cell(0, 45, '', 0, 2, '', 0, '');

        $this->SetFont('SJIS', '', 8);

        $monetary_unit = '円';
        $point_unit = 'Pt';

        // 購入商品情報
        for ($i = 0; $i < count($this->arrDisp['quantity']); $i++) {

            // 購入数量
            $data[0] = $this->arrDisp['quantity'][$i];

            // 税込金額（単価）
            $data[1] = SC_Helper_DB_Ex::sfCalcIncTax($this->arrDisp['price'][$i]);

            // 小計（商品毎）
            $data[2] = $data[0] * $data[1];

            $arrOrder[$i][0]  = $this->arrDisp['product_name'][$i].' / ';
            $arrOrder[$i][0] .= $this->arrDisp['product_code'][$i].' / ';
            if ($this->arrDisp['classcategory_name1'][$i]) {
                $arrOrder[$i][0] .= ' [ '.$this->arrDisp['classcategory_name1'][$i];
                if ($this->arrDisp['classcategory_name2'][$i] == '') {
                    $arrOrder[$i][0] .= ' ]';
                } else {
                    $arrOrder[$i][0] .= ' * '.$this->arrDisp['classcategory_name2'][$i].' ]';
                }
            }
            if($this->arrDisp['taxfree'][$i]){
            	$arrOrder[$i][0] .= '【非課税】';
            }
            $arrOrder[$i][1]  = number_format($data[0]);
            $arrOrder[$i][2]  = number_format($data[1]).$monetary_unit;
            $arrOrder[$i][3]  = number_format($data[2]).$monetary_unit;

        }

        $arrOrder[$i][0] = '';
        $arrOrder[$i][1] = '';
        $arrOrder[$i][2] = '';
        $arrOrder[$i][3] = '';

        $i++;
        $arrOrder[$i][0] = '';
        $arrOrder[$i][1] = '';
        $arrOrder[$i][2] = '商品合計';
        $arrOrder[$i][3] = number_format($this->arrDisp['subtotal']).$monetary_unit;

        $i++;
        $arrOrder[$i][0] = '';
        $arrOrder[$i][1] = '';
        $arrOrder[$i][2] = '送料';
        $arrOrder[$i][3] = number_format($this->arrDisp['deliv_fee']).$monetary_unit;

        $i++;
        $arrOrder[$i][0] = '';
        $arrOrder[$i][1] = '';
        $arrOrder[$i][2] = '手数料';
        $arrOrder[$i][3] = number_format($this->arrDisp['charge']).$monetary_unit;

        $i++;
        $arrOrder[$i][0] = '';
        $arrOrder[$i][1] = '';
        $arrOrder[$i][2] = '値引き';
        $arrOrder[$i][3] = '- '.number_format(($this->arrDisp['use_point'] * POINT_VALUE) + $this->arrDisp['discount']).$monetary_unit;

        $i++;
        $arrOrder[$i][0] = '';
        $arrOrder[$i][1] = '';
        $arrOrder[$i][2] = '請求金額';
        $arrOrder[$i][3] = number_format($this->arrDisp['payment_total']).$monetary_unit;

        // ポイント表記
        if ($this->arrData['disp_point'] && $this->arrDisp['customer_id']) {
            $i++;
            $arrOrder[$i][0] = '';
            $arrOrder[$i][1] = '';
            $arrOrder[$i][2] = '';
            $arrOrder[$i][3] = '';

            $i++;
            $arrOrder[$i][0] = '';
            $arrOrder[$i][1] = '';
            $arrOrder[$i][2] = '利用ポイント';
            $arrOrder[$i][3] = number_format($this->arrDisp['use_point']).$point_unit;

            $i++;
            $arrOrder[$i][0] = '';
            $arrOrder[$i][1] = '';
            $arrOrder[$i][2] = '加算ポイント';
            $arrOrder[$i][3] = number_format($this->arrDisp['add_point']).$point_unit;
        }

        $this->FancyTable($this->label_cell, $arrOrder, $this->width_cell);
    }
    
    // 受注詳細データの取得
    function lfGetOrderDetail($order_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = 'product_id, product_class_id, product_code, product_name, classcategory_name1, classcategory_name2, price, quantity, point_rate, taxfree';
        $where = 'order_id = ?';
        $objQuery->setOrder('order_detail_id');
        $arrRet = $objQuery->select($col, 'dtb_order_detail', $where, array($order_id));
        return $arrRet;
    }
}
