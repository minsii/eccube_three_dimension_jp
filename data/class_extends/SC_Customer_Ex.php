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

require_once CLASS_REALDIR . 'SC_Customer.php';

class SC_Customer_Ex extends SC_Customer {


	/**
	 * 会員の登録住所を取得する.
	 *
	 * 配列の1番目に会員登録住所, 追加登録住所が存在する場合は2番目以降に
	 * 設定される.
	 *
	 * @param integer $customer_id 顧客ID
	 * @return array 会員登録住所, 追加登録住所の配列
	 */
	function getCustomerAddress($customer_id) {
		$objQuery =& SC_Query_Ex::getSingletonInstance();

		/*## 顧客法人管理 MDF BEGIN ##*/
		$cols = "customer_id, name01, name02, kana01, kana02, zip01, zip02, pref, addr01, addr02, tel01, tel02, tel03";
		if(constant("USE_CUSTOMER_COMPANY") === true){
			$cols .= ", company, company_kana, company_department";
		}
		/*## 会員登録項目カスタマイズ ADD BEGIN ##*/
		else{
			$cols .=", company";
		}
		/*## 会員登録項目カスタマイズ ADD END ##*/
		$from = <<< __EOS__
            (   SELECT NULL AS other_deliv_id,
            {$cols},
                       email, email_mobile,
                       fax01, fax02, fax03
                  FROM dtb_customer
                 WHERE customer_id = ?
             UNION ALL
                SELECT other_deliv_id,
                {$cols},
                       NULL AS email, NULL AS email_mobile,
                       NULL AS fax01, NULL AS fax02, NULL AS fax03
                  FROM dtb_other_deliv
                 WHERE customer_id = ?
            ) AS addrs
__EOS__;

                /*## 顧客法人管理 MDF END ##*/
                $objQuery->setOrder("other_deliv_id IS NULL DESC, other_deliv_id DESC");
                return $objQuery->select("*", $from, "", array($customer_id, $customer_id));
	}


	/*## 事業者番号でログイン ADD BEGIN ##*/
	/**
	 * ログインを実行する.
	 *
	 * ログインを実行し, 成功した場合はユーザー情報をセッションに格納し,
	 * true を返す.
	 * モバイル端末の場合は, 携帯端末IDを保存する.
	 * ログインに失敗した場合は, false を返す.
	 *
	 * @param string $login_email ログインメールアドレス
	 * @param string $login_pass ログインパスワード
	 * @param string $login_company_no 事業者番号
	 * @return boolean ログインに成功した場合 true; 失敗した場合 false
	 */
	function doLogin($login_email, $login_pass, $login_company_no=null) {
		switch (SC_Display_Ex::detectDevice()) {
			case DEVICE_TYPE_MOBILE:
				if (!$this->getCustomerDataFromMobilePhoneIdPass($login_pass) &&
				!$this->getCustomerDataFromEmailPass($login_pass, $login_email, true)
				) {
					return false;
				} else {
					$this->updateMobilePhoneId();
					return true;
				}
				break;

			case DEVICE_TYPE_SMARTPHONE:
			case DEVICE_TYPE_PC:
			default:
				if(!empty($login_email)){
					return $this->getCustomerDataFromEmailPass($login_pass, $login_email);
				}else if(!empty($login_company_no)){
					return $this->getCustomerDataFromCompanyNoPass($login_pass, $login_company_no);
				}
				break;
		}
	}

	function getCustomerDataFromCompanyNoPass($pass, $companyno) {
		// 小文字に変換
		$arrValues = array($companyno);

		// 本登録された会員のみ
		$sql = 'SELECT * FROM dtb_customer WHERE company_no = ? AND del_flg = 0 AND status = 2';
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$result = $objQuery->getAll($sql, $arrValues);
		if (empty($result)) {
			return false;
		} else {
			$data = $result[0];
		}

		// パスワードが合っていれば会員情報をcustomer_dataにセットしてtrueを返す
		if (SC_Utils_Ex::sfIsMatchHashPassword($pass, $data['password'], $data['salt'])) {
			$this->customer_data = $data;
			$this->startSession();
			return true;
		}
		return false;
	}
	/*## 事業者番号でログイン ADD END ##*/

	function getFavoriteProducts($customer_id) {

		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$arrFavorites = $objQuery->getCol("product_id", "dtb_customer_favorite_products", "customer_id = ?", array($customer_id));
		 
		return $arrFavorites;
	}

	/*## 予算実績 ADD BEGIN ##*/
	function getMonthEstimate($customer_id){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$arrEstimate = $objQuery->getRow("*", "dtb_customer_month_estimate", "customer_id = ?", array($customer_id));
		
		if(is_array($arrEstimate)){
			$start = explode(' ', $arrEstimate['month_est_start_date']);
			list($arrEstimate['month_est_start_year'],
			$arrEstimate['month_est_start_month'],
			$arrEstimate['month_est_start_day']) = explode('-',$start[0]);

			$end = explode(' ', $arrEstimate['month_est_end_date']);
			list($arrEstimate['month_est_end_year'],
			$arrEstimate['month_est_end_month'],
			$arrEstimate['month_est_end_day']) = explode('-',$end[0]);
		}
		return $arrEstimate;
	}

	function getYearEstimate($customer_id){
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$arrEstimate = $objQuery->getRow("*", "dtb_customer_year_estimate", "customer_id = ?", array($customer_id));
		
		if(is_array($arrEstimate)){
			$start = explode(' ', $arrEstimate['year_est_start_date']);
			list($arrEstimate['year_est_start_year'],
			$arrEstimate['year_est_start_month'],
			$arrEstimate['year_est_start_day']) = explode('-',$start[0]);

			$end = explode(' ', $arrEstimate['year_est_end_date']);
			list($arrEstimate['year_est_end_year'],
			$arrEstimate['year_est_end_month'],
			$arrEstimate['year_est_end_day']) = explode('-',$end[0]);
		}
		return $arrEstimate;
	}

	function setMonthEstimate($customer_id, $arrData, &$objQuery = null){
		$cmt = false;
		if($objQuery == null){
			$objQuery =& SC_Query_Ex::getSingletonInstance();
			$objQuery->begin();
			$cmt = true;
		}

		$objQuery->delete("dtb_customer_month_estimate", "customer_id = ?", array($customer_id));

		$arrval = array();
		$arrval["customer_id"] = $customer_id;
		$arrval["month_est_start_date"] = SC_Utils_Ex::sfGetTimestamp($arrData['month_est_start_year'],
											$arrData['month_est_start_month'],
											$arrData['month_est_start_day']);
		$arrval["month_est_end_date"] = SC_Utils_Ex::sfGetTimestamp($arrData['month_est_end_year'],
											$arrData['month_est_end_month'],
											$arrData['month_est_end_day']);
		$arrval["month_est_total"] = $arrData["month_est_total"];
		$objQuery->insert("dtb_customer_month_estimate", $arrval);

		if($cmt){
			$objQuery->commit();
		}
	}
	
	function setYearEstimate($customer_id, $arrData, &$objQuery = null){
		$cmt = false;
		if($objQuery == null){
			$objQuery =& SC_Query_Ex::getSingletonInstance();
			$objQuery->begin();
			$cmt = true;
		}

		$objQuery->delete("dtb_customer_year_estimate", "customer_id = ?", array($customer_id));

		$arrval = array();
		$arrval["customer_id"] = $customer_id;
		$arrval["year_est_start_date"] = SC_Utils_Ex::sfGetTimestamp($arrData['year_est_start_year'],
											$arrData['year_est_start_month'],
											$arrData['year_est_start_day']);
		$arrval["year_est_end_date"] = SC_Utils_Ex::sfGetTimestamp($arrData['year_est_end_year'],
											$arrData['year_est_end_month'],
											$arrData['year_est_end_day']);
		$arrval["year_est_total"] = $arrData["year_est_total"];
		$objQuery->insert("dtb_customer_year_estimate", $arrval);

		if($cmt){
			$objQuery->commit();
		}
	}
	
	/**
	 * 会員設定の予算期間に従って、受注実績を集計する
	 *
	 * @param $customer_id
	 * @param $arrData
	 */
	function getMonthEstOrderSummary($customer_id, $arrData){
		$arrRet = array();
		 
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$where = "customer_id=? AND create_date >= ? AND create_date <= ? AND del_flg = 0";

		$arrval = array($customer_id,
		SC_Utils_Ex::sfGetTimestamp($arrData['month_est_start_year'],
		$arrData['month_est_start_month'],
		$arrData['month_est_start_day']),
		SC_Utils_Ex::sfGetTimestamp($arrData['month_est_end_year'],
		$arrData['month_est_end_month'],
		$arrData['month_est_end_day']),
		);
		$month_order_total = $objQuery->getCol("SUM(total) AS month_est_balance", "dtb_order", $where, $arrval);
		
		$arrRet["month_order_total"] = empty($month_order_total[0]) ? 0 : $month_order_total[0];
		$arrRet["month_est_balance"] = $arrData["month_est_total"] - $arrRet["month_order_total"];

		return $arrRet;
	}
	
	/**
	 * 会員設定の予算期間に従って、受注実績を集計する
	 *
	 * @param $customer_id
	 * @param $arrData
	 */
	function getYearEstOrderSummary($customer_id, $arrData){
		$arrRet = array();
		 
		$objQuery =& SC_Query_Ex::getSingletonInstance();
		$where = "customer_id=? AND create_date >= ? AND create_date <= ? AND del_flg = 0";

		$arrval = array($customer_id,
		SC_Utils_Ex::sfGetTimestamp($arrData['year_est_start_year'],
		$arrData['year_est_start_month'],
		$arrData['year_est_start_day']),
		SC_Utils_Ex::sfGetTimestamp($arrData['year_est_end_year'],
		$arrData['year_est_end_month'],
		$arrData['year_est_end_day']),
		);
		$year_order_total = $objQuery->getCol("SUM(total) AS year_est_total", "dtb_order", $where, $arrval);
			
		$arrRet["year_order_total"] = empty($year_order_total[0]) ? 0 : $year_order_total[0];
		$arrRet["year_est_balance"] = $arrData["year_est_total"] - $arrRet["year_order_total"];

		return $arrRet;
	}
	/*## 予算実績 ADD END ##*/
	
	function getLoginCustomerData(){
		return isset($_SESSION['customer']) ? $_SESSION['customer'] : array();
	}
}

?>
