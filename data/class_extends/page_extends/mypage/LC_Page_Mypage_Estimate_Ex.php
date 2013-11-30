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
require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';

/**
 * 月額予算 のページクラス(拡張).
 *
 * LC_Page_Mypage_History をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Mypage_History_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_Mypage_Estimate_Ex extends LC_Page_AbstractMypage_Ex {

	// }}}
	// {{{ functions

	/**
	 * Page を初期化する.
	 *
	 * @return void
	 */
	function init() {
		parent::init();
		$this->tpl_mypageno     = 'estimate';
		$this->tpl_subtitle     = '月額予算実績確認';
		$this->httpCacheControl('nocache');

		$objDate            = new SC_Date_Ex(date('Y',strtotime('now - 1 year')), date('Y',strtotime('now + 1 year')));
		$this->arrYear      = $objDate->getYear('', date('Y',strtotime('now')), '');
		$this->arrMonth     = $objDate->getMonth(true);
		$this->arrDay       = $objDate->getDay(true);
	}

	/**
	 * Page のプロセス.
	 *
	 * @return void
	 */
	function process() {
		parent::process();
	}

	function action() {
		$objCustomer = new SC_Customer_Ex();
		$customer_id = $objCustomer->getValue('customer_id');

		// パラメーター管理クラス,パラメーター情報の初期化
		$objMonthFormParam = new SC_FormParam_Ex();
		$objYearFormParam = new SC_FormParam_Ex();
		$this->lfInitMonthEstParam($objMonthFormParam);
		$this->lfInitYearEstParam($objYearFormParam);
		 
		switch ($this->getMode()) {
			case 'edit_month_est':
				$objMonthFormParam->setParam($_POST);

				$this->arrMonthForm = $objMonthFormParam->getHashArray();
				$this->arrErr = $this->lfCheckMonthError($objMonthFormParam);
				
				if (SC_Utils_Ex::isBlank($this->arrErr)) {
					$objCustomer->setMonthEstimate($customer_id, $this->arrMonthForm);
					
					$this->arrMonthDisp = $objCustomer->getMonthEstOrderSummary($customer_id, $this->arrMonthForm);
				}
				
				$this->lfLoadYearEstimate($objCustomer, $objYearFormParam);
				$this->arrYearForm = $objYearFormParam->getHashArray();
				$this->arrYearDisp = $objCustomer->getYearEstOrderSummary($customer_id, $this->arrYearForm);
				
				break;

			case 'edit_year_est':
				$objYearFormParam->setParam($_POST);
				
				$this->arrYearForm = $objYearFormParam->getHashArray();
				$this->arrErr = $this->lfCheckYearError($objYearFormParam);
				
				if (SC_Utils_Ex::isBlank($this->arrErr)) {
					$objCustomer->setYearEstimate($customer_id, $this->arrYearForm);

					$this->arrYearDisp = $objCustomer->getYearEstOrderSummary($customer_id, $this->arrYearForm);
				}
				else{
					$this->arrMonthForm = $objMonthFormParam->getHashArray();
				}

				$this->lfLoadMonthEstimate($objCustomer, $objMonthFormParam);
				$this->arrMonthForm = $objMonthFormParam->getHashArray();
				$this->arrMonthDisp = $objCustomer->getMonthEstOrderSummary($customer_id, $this->arrMonthForm);
				
				break;
				 
			default:
				$this->lfLoadMonthEstimate($objCustomer, $objMonthFormParam);
				$this->lfLoadYearEstimate($objCustomer, $objYearFormParam);
				
				$this->arrMonthForm = $objMonthFormParam->getHashArray();
				$this->arrYearForm = $objYearFormParam->getHashArray();
				$this->arrMonthDisp = $objCustomer->getMonthEstOrderSummary($customer_id, $this->arrMonthForm);
				$this->arrYearDisp = $objCustomer->getYearEstOrderSummary($customer_id, $this->arrYearForm);
				break;
		}
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
	 * DBから予算実績の情報を取得し、画面パラメータに設定する
	 * 会員設定がない場合、デフォルトの期間を設定する
	 *
	 * @param $objCustomer
	 * @param $objFormParam
	 */
	function lfLoadMonthEstimate($objCustomer, &$objMonthFormParam){
		$customer_id = $objCustomer->getValue('customer_id');
		$arrMonth = $objCustomer->getMonthEstimate($customer_id);

		if(is_array($arrMonth)){
			$objMonthFormParam->setParam($arrMonth);
		}
		else{
			$this->lfSetDefaultMonthDate($objMonthFormParam);
		}
	}

	/**
	 * DBから予算実績の情報を取得し、画面パラメータに設定する
	 * 会員設定がない場合、デフォルトの期間を設定する
	 *
	 * @param $objCustomer
	 * @param $objFormParam
	 */
	function lfLoadYearEstimate($objCustomer, &$objYearFormParam){
		$customer_id = $objCustomer->getValue('customer_id');
		$arrYear = $objCustomer->getYearEstimate($customer_id);

		if(is_array($arrYear)){
			$objYearFormParam->setParam($arrYear);
		}else{
			$this->lfSetDefaultYearDate($objYearFormParam);
		}
	}
	
	function lfSetDefaultMonthDate(&$objFormParam){
		$today = getdate();
		$objFormParam->setValue("month_est_start_year", $today["year"]);
		$objFormParam->setValue("month_est_start_month", $today["mon"]);
		$objFormParam->setValue("month_est_start_day", 1);
		 
		$next_mon_date = getdate(strToTime("+1 month", time()));
		$mon_end = getdate(mktime(0, 0, 0, $next_mon_date["mon"], 0, $next_mon_date["year"]));
		$objFormParam->setValue("month_est_end_year", $mon_end["year"]);
		$objFormParam->setValue("month_est_end_month", $mon_end["mon"]);
		$objFormParam->setValue("month_est_end_day", $mon_end["mday"]);
	}

	function lfSetDefaultYearDate(&$objFormParam){
		$today = getdate();

		$objFormParam->setValue("year_est_start_year", $today["year"]);
		$objFormParam->setValue("year_est_start_month", 1);
		$objFormParam->setValue("year_est_start_day", 1);

		$objFormParam->setValue("year_est_end_year", $today["year"]);
		$objFormParam->setValue("year_est_end_month", 12);
		$objFormParam->setValue("year_est_end_day", 31);
	}


	/**
	 * パラメーター情報の初期化を行う.
	 *
	 * @param SC_FormParam $objFormParam SC_FormParam インスタンス
	 * @return void
	 */
	function lfInitMonthEstParam(&$objFormParam) {
		$objFormParam->addParam('月額予算開始年', 'month_est_start_year', 4, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('月額予算開始月', 'month_est_start_month', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('月額予算開始日', 'month_est_start_day', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);

		$objFormParam->addParam('月額予算終了年', 'month_est_end_year', 4, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('月額予算終了月', 'month_est_end_month', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('月額予算終了日', 'month_est_end_day', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);

		$objFormParam->addParam('月額予算金額', 'month_est_total', PRICE_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
	}


	/**
	 * パラメーター情報の初期化を行う.
	 *
	 * @param SC_FormParam $objFormParam SC_FormParam インスタンス
	 * @return void
	 */
	function lfInitYearEstParam(&$objFormParam) {
		$objFormParam->addParam('年額予算開始年', 'year_est_start_year', 4, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('年額予算開始月', 'year_est_start_month', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('年額予算開始日', 'year_est_start_day', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);

		$objFormParam->addParam('年額予算終了年', 'year_est_end_year', 4, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('年額予算終了月', 'year_est_end_month', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);
		$objFormParam->addParam('年額予算終了日', 'year_est_end_day', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '', false);

		$objFormParam->addParam('年額予算金額', 'year_est_total', PRICE_LEN, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
	}

	function lfCheckMonthError(&$objFormParam){
		// 入力データを渡す。
		$arrForm =  $objFormParam->getHashArray();
		$objErr = new SC_CheckError_Ex($arrForm);
		$objErr->arrErr = $objFormParam->checkError();
		
		if (SC_Utils_Ex::isBlank($objErr->arrErr)) {
			$objErr->doFunc(array('月額予算開始', '月額予算終了',
			'month_est_start_year', 'month_est_start_month', 'month_est_start_day',
			'month_est_end_year', 'month_est_end_month', 'month_est_end_day'), 
			array('CHECK_SET_TERM'));
		}
			
		return $objErr->arrErr;
	}
	
	function lfCheckYearError(&$objFormParam){
		// 入力データを渡す。
		$arrForm =  $objFormParam->getHashArray();
		$objErr = new SC_CheckError_Ex($arrForm);
		$objErr->arrErr = $objFormParam->checkError();
		
		if (SC_Utils_Ex::isBlank($objErr->arrErr)) {
			$objErr->doFunc(array('年額予算開始', '年額予算終了',
			'year_est_start_year', 'year_est_start_month', 'year_est_start_day',
			'year_est_end_year', 'year_est_end_month', 'year_est_end_day'), 
			array('CHECK_SET_TERM'));
		}
			
		return $objErr->arrErr;
	}
}
