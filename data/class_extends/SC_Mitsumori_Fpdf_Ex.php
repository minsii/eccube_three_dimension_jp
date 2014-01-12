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

class SC_Mitsumori_Fpdf_Ex extends SC_Fpdf {
	function __construct($download, $title, $tpl_pdf = 'mitsumori.pdf') {
		$this->FPDF();
		// デフォルトの設定
		$this->tpl_pdf = PDF_TEMPLATE_REALDIR . $tpl_pdf;  // テンプレートファイル
		$this->pdf_download = $download;      // PDFのダウンロード形式（0:表示、1:ダウンロード）
		$this->tpl_title = $title;
		$this->tpl_dispmode = 'real';      // 表示モード
		$masterData = new SC_DB_MasterData_Ex();
		$this->arrPref = $masterData->getMasterData('mtb_pref');
		$this->width_cell = array(110.3,12,21.7,24.5);

		$this->label_cell[] = '商品名 / 商品コード / [ 規格 ]';
		$this->label_cell[] = '数量';
		$this->label_cell[] = '単価';
		$this->label_cell[] = '金額';

		$this->arrMessage = array(
            'このたびはお買上げいただきありがとうございます。',
            '下記の内容にて納品させていただきます。',
            'ご確認くださいますよう、お願いいたします。'
            );

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
		$this->disp_mode = true;

        // テンプレート内容の位置、幅を調整 ※useTemplateに引数を与えなければ100%表示がデフォルト
        $this->useTemplate($tplidx);

        $this->setShopData();
        $this->setMessageData();
        $this->setOrderData();
    }

    function setShopData() {
        // ショップ情報

        $objDb = new SC_Helper_DB_Ex();
        $arrInfo = $objDb->sfGetBasisData();

        // ショップ名
        $this->lfText(125, 60, $arrInfo['shop_name'], 8, 'B');
        // URL
        $this->lfText(125, 63, $arrInfo['law_url'], 8);
        // 会社名
        $this->lfText(125, 68, $arrInfo['law_company'], 8);
        // 郵便番号
        $text = '〒 ' . $arrInfo['law_zip01'] . ' - ' . $arrInfo['law_zip02'];
        $this->lfText(125, 71, $text, 8);
        // 都道府県+所在地
        $text = $this->arrPref[$arrInfo['law_pref']] . $arrInfo['law_addr01'];
        $this->lfText(125, 74, $text, 8);
        $this->lfText(125, 77, $arrInfo['law_addr02'], 8);

        $text = 'TEL: '.$arrInfo['law_tel01'].'-'.$arrInfo['law_tel02'].'-'.$arrInfo['law_tel03'];
        //FAX番号が存在する場合、表示する
        if (strlen($arrInfo['law_fax01']) > 0) {
            $text .= '　FAX: '.$arrInfo['law_fax01'].'-'.$arrInfo['law_fax02'].'-'.$arrInfo['law_fax03'];
        }
        $this->lfText(125, 80, $text, 8);  //TEL・FAX

        if (strlen($arrInfo['law_email']) > 0) {
            $text = 'Email: '.$arrInfo['law_email'];
            $this->lfText(125, 83, $text, 8);      //Email
        }
    }
    
	function createPdf() {
		// PDFをブラウザに送信
		ob_clean();
		$filename = 'mitsumorisyo.pdf';
		$this->Output($this->lfConvSjis($filename), 'D');

		// 入力してPDFファイルを閉じる
		$this->Close();
	}

	function setOrderData() {
		$arrOrder = array();

		$arrCustomer = $this->arrData["customer"];
		$arrItems = $this->arrData["detail"];
		$calculate = $this->arrData["calculate"];

		// 購入者情報
		$text = '〒 '.$arrCustomer['zip01'].' - '.$arrCustomer['zip02'];
		$this->lfText(23, 43, $text, 10); //購入者郵便番号
		$text = $this->arrPref[$arrCustomer['pref']] . $arrCustomer['addr01'];
		$this->lfText(27, 47, $text, 10); //購入者都道府県+住所1
		$this->lfText(27, 51, $arrCustomer['addr02'], 10); //購入者住所2
		$text = $arrCustomer['name01'].'　'.$arrCustomer['name02'].'　様';
		$this->lfText(27, 59, $text, 11); //購入者氏名

		// お届け先情報
		$this->SetFont('SJIS', '', 10);
		$this->lfText(25, 125, date("Y-m-d"), 10); //ご注文日

		$this->SetFont('Gothic', 'B', 15);
		$this->Cell(0, 10, $this->tpl_title, 0, 2, 'C', 0, '');  //文書タイトル（納品書・請求書）
		$this->Cell(0, 66, '', 0, 2, 'R', 0, '');
		$this->Cell(5, 0, '', 0, 0, 'R', 0, '');
		$this->SetFont('SJIS', 'B', 15);
		$this->Cell(67, 8, number_format($calculate['all_total_inctax']).' 円', 0, 2, 'R', 0, '');
		$this->Cell(0, 45, '', 0, 2, '', 0, '');

		$this->SetFont('SJIS', '', 8);

		$monetary_unit = '円';
		$point_unit = 'Pt';

		// 購入商品情報
		foreach($arrItems as $i => $item){

			// 購入数量
			$data[0] = $item['quantity'];

			// 単価
			$data[1] = $item["productsClass"]["price02"];
			
			// 小計（商品毎、税込）
			if($item["productsClass"]["taxfree"]){
				$data[2] = $data[0] * $data[1];
			} else{
				$data[2] = $data[0] * SC_Helper_DB_Ex::sfCalcIncTax($data[1]);
			}
			
			$arrOrder[$i][0]  = $item["productsClass"]['name'].' / ';
			$arrOrder[$i][0] .= $item["productsClass"]['product_code'].' / ';
			if ($item["productsClass"]['classcategory_name1']) {
				$arrOrder[$i][0] .= ' [ '.$item["productsClass"]['classcategory_name1'];
				if ($item["productsClass"]['classcategory_name2'] == '') {
					$arrOrder[$i][0] .= ' ]';
				} else {
					$arrOrder[$i][0] .= ' * '.$item["productsClass"]['classcategory_name2'].' ]';
				}
			}
			$arrOrder[$i][1]  = number_format($data[0]);
			$arrOrder[$i][2]  = number_format($data[1]).$monetary_unit;
			$arrOrder[$i][3]  = number_format($data[2]).$monetary_unit;
			
			if($item["productsClass"]["taxfree"]){
				$arrOrder[$i][3] .= "(税抜)";
			}
			else{
				$arrOrder[$i][3] .= "(税込)";
			}
		}

		$i++;
		$arrOrder[$i][0] = '';
		$arrOrder[$i][1] = '';
		$arrOrder[$i][2] = '';
		$arrOrder[$i][3] = '';

		$i++;
		$arrOrder[$i][0] = '';
		$arrOrder[$i][1] = '';
		$arrOrder[$i][2] = '商品合計';
		$arrOrder[$i][3] = number_format($calculate['total_inctax']).$monetary_unit;
		 
		$i++;
		$arrOrder[$i][0] = '';
		$arrOrder[$i][1] = '';
		$arrOrder[$i][2] = '請求金額';
		$arrOrder[$i][3] = number_format($calculate['all_total_inctax']).$monetary_unit;
		 
		 
		// ポイント表記
		if ($calculate['total_point'] && $arrCustomer['customer_id']) {
			$i++;
			$arrOrder[$i][0] = '';
			$arrOrder[$i][1] = '';
			$arrOrder[$i][2] = '';
			$arrOrder[$i][3] = '';

			$i++;
			$arrOrder[$i][0] = '';
			$arrOrder[$i][1] = '';
			$arrOrder[$i][2] = '加算ポイント';
			$arrOrder[$i][3] = number_format($calculate['total_point']).$point_unit;
		}

		$this->FancyTable($this->label_cell, $arrOrder, $this->width_cell);
	}
	
    function setMessageData() {
        $text = '作成日: '.date("Y").'年'.date("m").'月'.date("d").'日';
        $this->lfText(158, 288, $text, 8);  //作成日
    }
}
