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

class SC_OrderDetail_Fpdf_Ex extends SC_Fpdf {

	function __construct($download, $title, $tpl_pdf = 'order_detail.pdf') {
		$this->FPDF();
		// デフォルトの設定
		$this->tpl_pdf = PDF_TEMPLATE_REALDIR . $tpl_pdf;  // テンプレートファイル
		$this->pdf_download = $download;      // PDFのダウンロード形式（0:表示、1:ダウンロード）
		$this->tpl_dispmode = 'real';      // 表示モード
		$masterData = new SC_DB_MasterData_Ex();
		$this->arrPref = $masterData->getMasterData('mtb_pref');
        
		// 配送業者の取得
        $this->arrDeliv = SC_Helper_DB_Ex::sfGetIDValueList('dtb_deliv', 'deliv_id', 'name');
        
		// SJISフォント
		$this->AddSJISFont();
		$this->SetFont('SJIS');

		//ページ総数取得
		$this->AliasNbPages();

		// マージン設定
		$this->SetMargins(15, 20);

		// PDFを読み込んでページ数を取得
		$this->pageno = $this->setSourceFile($this->tpl_pdf);
	}

	function setData($arrData) {
		$this->arrData = $arrData;

		// ページ番号よりIDを取得
		$tplidx = $this->ImportPage(1);

		// ページを追加（新規）
		$this->AddPage();

		//表示倍率(100%)
		$this->SetDisplayMode($this->tpl_dispmode);

		if (SC_Utils_Ex::sfIsInt($arrData['order_id'])) {
			$this->disp_mode = true;
		}

		// テンプレート内容の位置、幅を調整 ※useTemplateに引数を与えなければ100%表示がデフォルト
		$this->useTemplate($tplidx);

		// DBから受注情報を読み込む
		$this->lfGetOrderData($this->arrData['order_id']);
		$this->lfGetOrderShipping($this->arrData['order_id']);
		
		//        $this->setShopData();
		//        $this->setMessageData();
		$this->setOrderData();
	}

	function setOrderDetailData(){
		$arrOrder = array();
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

	function setOrderData() {
		$this->SetFont('SJIS', '', 10);
		
		/* 注文情報 */
		$this->lfText(62, 43, SC_Utils_Ex::sfDispDBDate($this->arrDisp['create_date'])); //ご注文日
		$this->lfText(150, 43, $this->arrDisp['order_id'], 11); //注文番号
		$this->lfText(62, 50, SC_Utils_Ex::sfDispDBDate($this->arrDisp['commit_date'], false)); //発送日
		$this->lfText(145, 50, SC_Utils_Ex::sfDispDBDate($this->arrDisp['payment_date'], false)); //入金日
		$this->lfText(62, 62, $this->arrDisp['note'], 10); // メモ

		/* 購入者情報 */
		$h = 78;
		$text = $this->arrDisp['order_company'].' ['.$this->arrDisp['order_company_no'].']';
		$this->lfText(60, $h, $text); //事業所名、事業所番号

		$h += 7;
		$text = $this->arrDisp['order_name01'].'　'.$this->arrDisp['order_name02'].'　様';
		$this->lfText(60, $h, $text); //購入者氏名

		//購入者郵便番号 都道府県+住所1+住所2
		$h += 7;
		$text = '〒 '.$this->arrDisp['order_zip01'].' - '.$this->arrDisp['order_zip02'];
		$text .= " ". $this->arrPref[$this->arrDisp['order_pref']] . $this->arrDisp['order_addr01'];
		$text .= " ". $this->arrDisp['order_addr02'];
		$this->lfText(60, $h, $text);
		
		//購入者電話番号
		$h += 7;
		$text = $this->arrDisp['order_tel01']. "-". $this->arrDisp['order_tel02']. "-". $this->arrDisp['order_tel03'];
		$this->lfText(60, $h, $text);

		/* 支払情報 */
		$h = 110;
		$this->lfText( 75, $h, number_format($this->arrDisp['payment_total']).' 円'); // 請求金額
		$this->lfText(155, $h, number_format($this->arrDisp['total']).' 円'); // 総合計
		// クーポン利用
		$h += 7;
		if($this->arrDisp['discount'] > 0){
			$this->lfText(75, $h, '利用する');
			$this->lfText(155, $h, number_format($this->arrDisp['discount']).' 円');
		}
		else{
			$this->lfText(75, $h, '利用しない');
		}
		// ポイント利用
		$h += 7;
		if($this->arrDisp['use_point'] > 0){
			$this->lfText(75, $h, '利用する');
			$this->lfText(155, $h, number_format($this->arrDisp['use_point']).' Pt');
		}
		else{
			$this->lfText(75, $h, '利用しない');
		}
		// お支払方法
		$h += 7;
		$this->lfText(75, $h, $this->arrDisp['payment_method']);
		
		/* 配送情報 */
		$h = 142;
		$this->lfText(75, $h, $this->arrDeliv[$this->arrDisp['deliv_id']]); // 配送方法
		$h += 7;
		$this->lfText(75, $h, ""); // 配送区分
		$h += 7;
		$this->lfText(75, $h, SC_Utils_Ex::sfDispDBDate($this->arrShipping['shipping_date'], false)); // お届け日指定
		$this->lfText(155, $h, $this->arrShipping['shipping_time']); // お届け時間帯
		$this->lfText(62, 172, $this->arrDisp['message'], 10); // 備考
		
		/* 明細 */
		$this->SetFont('SJIS', '', 10);
		$h = 211;
		$tax = 0;
		$x = $this->GetX();
		$y = $h - 3;
		for ($i = 0; $i < count($this->arrDisp['quantity']); $i++) {
			// 商品名/商品番号/規格
			$name  = $this->arrDisp['product_name'][$i].' / ';
			$name .= $this->arrDisp['product_code'][$i].' / ';
			if ($this->arrDisp['classcategory_name1'][$i]) {
				$name .= ' [ '.$this->arrDisp['classcategory_name1'][$i];
				if ($this->arrDisp['classcategory_name2'][$i] == '') {
					$name .= ' ]';
				} else {
					$name .= ' * '.$this->arrDisp['classcategory_name2'][$i].' ]';
				}
			}
			if($this->arrDisp['taxfree'][$i]){
				$name .= '【非課税】';
			}
			$this->SetXY(49, $y);
			$this->MultiCell(120, 5, $name, 0, 'L', 0);

			// 購入数量
			$this->lfText(154, $h, number_format($this->arrDisp['quantity'][$i]));

			// 単価
			if($this->arrDisp['taxfree'][$i]){
				$price = $this->arrDisp['price'][$i];
			}else{
				$price = SC_Helper_DB_Ex::sfCalcIncTax($this->arrDisp['price'][$i]);
			}
			$this->lfText(165, $h, number_format($price). " 円");
			
			// 小計（商品毎）
			$subtotal = $price *  $this->arrDisp['quantity'][$i];
			$this->lfText(185, $h, number_format($subtotal). " 円");
			
			$y = $this->GetY();
			$h = $y + 3.5;
		}
		
		$h = 264;
		$this->SetFont('SJIS', '', 10);
		$this->lfText(50, $h, number_format($this->arrDisp['subtotal']). " 円"); // 合計
		$this->lfText(90, $h, number_format($this->arrDisp['tax']). " 円"); // 消費税
		$this->lfText(130, $h, number_format($this->arrDisp['deliv_fee']). " 円"); // 送料
		$this->lfText(170, $h, number_format($this->arrDisp['charge']). " 円"); // 手数料

		$h = 282;
		$this->lfText(50, $h, number_format($this->arrDisp['total']). " 円"); // 手数料
		// クーポン利用額
		if($this->arrDisp['discount'] > 0){
			$this->lfText(90, $h, number_format($this->arrDisp['discount']). " 円");
		}
		else{
			$this->lfText(90, $h, "-");
		}
		// ポイント利用額
		if($this->arrDisp['use_point'] > 0){
			$this->lfText(130, $h, number_format($this->arrDisp['use_point']). " 円");
		}
		else{
			$this->lfText(130, $h, "-");
		}
		$this->lfText(170, $h, number_format($this->arrDisp['payment_total']). " 円"); // 請求金額
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
	
	// 受注配送データの取得
	function lfGetOrderShipping($order_id) {
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$col = '*';
		$where = 'order_id = ?';
		$this->arrShipping = $objQuery->getRow($col, 'dtb_shipping', $where, array($order_id));
	}
}
