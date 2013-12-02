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
require_once CLASS_REALDIR . 'pages/mypage/LC_Page_MyPage.php';

/**
 * 購入履歴一覧 のページクラス(拡張).
 *
 * LC_Page_Mypage_History_Search_Ex をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Mypage_History_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Mypage_History_Search_Ex extends LC_Page_MyPage {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        
        $this->tpl_mypageno = 'history_search';
        if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
            $this->tpl_subtitle = 'MYページ';
        } else {
            $this->tpl_subtitle = '購入履歴一覧';
        }
        
		$objDate            = new SC_Date_Ex(date('Y',strtotime('now - 2 year')), date('Y',time()));
		$this->arrYear      = $objDate->getYear('', date('Y',strtotime('now')), '');
		$this->arrMonth     = $objDate->getMonth(true);
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
    
    function action(){
        $objCustomer = new SC_Customer_Ex();
        $customer_id = $objCustomer->getvalue('customer_id');

		// 検索引き継ぎ用パラメーター管理クラス
		$objFormSearchParam = new SC_FormParam_Ex();
		$this->lfInitSearchParam(&$objFormSearchParam);
		$objFormSearchParam->setParam($_REQUEST);
		
		$this->arrSearchForm = $objFormSearchParam->getHashArray();
		$this->arrErr = $this->lfCheckErrorSearchParam($objFormSearchParam);

		if (SC_Utils_Ex::isBlank($this->arrErr)){
			
			list($searchWhere, $arrSearchVal) = $this->lfPrepareCondition($objFormSearchParam);
			
			//ページ送り用
			$this->objNavi = new SC_PageNavi_Ex($_REQUEST['pageno'],
				$this->lfGetOrderHistory($customer_id, $searchWhere, $arrSearchVal),
				SEARCH_PMAX, 'fnNaviPage',
				NAVI_PMAX, 'pageno=#page#',
				SC_Display_Ex::detectDevice() !== DEVICE_TYPE_MOBILE);

			$this->arrOrder = $this->lfGetOrderHistory($customer_id, $searchWhere, $arrSearchVal, $this->objNavi->start_row);

			// 支払い方法の取得
			$this->arrPayment = SC_Helper_DB_Ex::sfGetIDValueList('dtb_payment', 'payment_id', 'payment_method');
			// 1ページあたりの件数
			$this->dispNumber = SEARCH_PMAX;
			 
			$this->arrPagenavi = $this->objNavi->arrPagenavi;
		}
    }
    
    function lfPrepareCondition($objFormSearchParam){
    	$arrWhere = array();
    	$arrVal = array();
    	$strWhere = "";
    	
    	$arrData = $objFormSearchParam->getHashArray();
    	if(!empty($arrData["start_year"])){
    		$arrWhere[] = "T1.create_date >= ?";
    		$arrVal[] = SC_Utils_Ex::sfGetTimestamp($arrData["start_year"], $arrData["start_month"], 1);
    	}
    	
    	if(!empty($arrData["end_year"])){
    		$arrWhere[] = "T1.create_date <= ?";
    		$endmonth = SC_Utils_Ex::sfGetTimestamp($arrData["end_year"], $arrData["end_month"], 1);
    		$arr_endmonth = getdate(strtotime("+1 month -1 day", mktime(0, 0, 0, $arrData["end_month"], 1, $arrData["end_year"])));
    		$arrVal[] = SC_Utils_Ex::sfGetTimestamp($arr_endmonth["year"], $arr_endmonth["mon"], $arr_endmonth["mday"], true);
    	}
    	

    	$name = $arrData["search_product_text"];
    	$name = str_replace('　', ' ', $name);
    	$name = trim($name);
    	if(!empty($name)){
    		// スペースでキーワードを分割
    		$names = preg_split("/ +/", $name);
    		$arrTextWhere = array();
    		foreach ($names as $val) {
    			$val = trim($val);
    			if ( strlen($val) > 0 ) {
    				$arrTextWhere[] = "( T2.product_name ILIKE ? OR T2.product_code ILIKE ?)";
    				$arrVal[]  = "%$val%";
    				$arrVal[]  = "%$val%";
    			}
    		}
    		if(count($arrTextWhere) > 0){
    			$arrWhere[] = join($arrTextWhere, " AND ");
    		}
    	}
    	if(count($arrWhere) > 0){
    		$strWhere = join($arrWhere, " AND ");
    	}
    	
    	return array($strWhere, $arrVal);
    }
    
    /**
     * 受注履歴を返す
     *
     * @param mixed $customer_id
     * @param mixed $startno 0以上の場合は受注履歴を返却する -1の場合は件数を返す
     * @access private
     * @return void
     */
    function lfGetOrderHistory($customer_id, $where = null, $arrval = null, $startno = -1) {
        $objQuery   = SC_Query_Ex::getSingletonInstance();

        $col = 'DISTINCT(T1.*)';
        $from = 'dtb_order AS T1 LEFT JOIN dtb_order_detail AS T2 USING(order_id)';
        $arrWhereVal = array();
        if(!empty($where)){
        	$where .= " AND ";
        	$arrWhereVal = $arrval;
        }
        $where .= 'T1.customer_id = ? AND T1.del_flg = 0';
        $arrWhereVal[] = $customer_id;
        $order  = 'T1.order_id DESC';

        if ($startno == -1) {
        	return $objQuery->get('COUNT(DISTINCT(T1.order_id))', $from, $where, $arrWhereVal);
        }

        $objQuery->setLimitOffset(SEARCH_PMAX, $startno);
        // 表示順序
        $objQuery->setOrder($order);

        //購入履歴の取得
        $arrOrders = $objQuery->select($col, $from, $where, $arrWhereVal);
        
		$objQuery   = SC_Query_Ex::getSingletonInstance();
		$objPurchase = new SC_Helper_Purchase_Ex();
		
		if(is_array($arrOrders)){
			foreach($arrOrders as $no => $order){
				$arrShipping = $objPurchase->getShippings($order["order_id"]);
				$arrOrders[$no]["isMultiple"] = count($arrShipping) > 1;
				$arrOrders[$no]["shipping"] = $arrShipping;

				// 受注商品明細の取得
				$arrOrders[$no]["detail"] = $objPurchase->getOrderDetail($order["order_id"]);
				
				// 購入履歴商品に画像をセット
				foreach ($arrOrders[$no]["detail"] as $i => $arrOrderDetail) {
					$objQuery =& SC_Query_Ex::getSingletonInstance();
					$arrProduct = $objQuery->select('main_list_image', 'dtb_products', 'product_id = ?', array($arrOrderDetail['product_id']));
					$arrOrders[$no]["detail"][$i]['main_list_image'] = $arrProduct[0]['main_list_image'];				}
			}
		}
		
		return $arrOrders;
    }
    

	/**
	 * パラメーター情報の初期化を行う.
	 *
	 * @param SC_FormParam $objFormParam SC_FormParam インスタンス
	 * @return void
	 */
	function lfInitSearchParam(&$objFormParam) {
		$objFormParam->addParam('開始年', 'start_year', 4, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('開始月', 'start_month', 2, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('終了年', 'end_year', 4, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('終了月', 'end_month', 2, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('商品名/カタログ番号', 'search_product_text', STEXT_LEN, 'KVa', array('SPTAB_CHECK', 'MAX_LENGTH_CHECK'));
	}
	
    /**
     * 検索パラメーターエラーチェック
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return array エラー配列
     */
	function lfCheckErrorSearchParam(&$objFormParam) {
		$arrErr = $objFormParam->checkError();
		$objErr = new SC_CheckError_Ex($objFormParam->getHashArray());

		$objErr->doFunc(array('開始年月', 'start_year', 'start_month'), array('CHECK_DATE3'));
		$objErr->doFunc(array('終了年月', 'end_year', 'end_month'), array('CHECK_DATE3'));

		$objErr->doFunc(array('開始年月','終了年月', 'start_year', 'start_month', 'end_year', 'end_month'), array('CHECK_SET_TERM3'));
	
		if (!SC_Utils_Ex::isBlank($objErr->arrErr)) {
			$arrErr = array_merge($arrErr, $objErr->arrErr);
		}
		return $arrErr;
	}
}
