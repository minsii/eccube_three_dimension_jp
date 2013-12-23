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
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc_Calendar.php';

/**
 * Calendar のページクラス(拡張).
 *
 * LC_Page_FrontParts_Bloc_Calendar をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $ $
 */
class LC_Page_FrontParts_Bloc_Calendar_Ex extends LC_Page_FrontParts_Bloc_Calendar {

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
     * カレンダー情報取得.
     *
     * @param integer $disp_month 表示する月数
     * @return array カレンダー情報の配列を返す
     */
    function lfGetCalendar($disp_month = 1) {
        $arrCalendar = array();
        $today = date('Y/m/d');

        for ($j = 0; $j <= $disp_month - 1; $j++) {
            $time = mktime(0, 0, 0, date('n') + $j, 1);
            $year = date('Y', $time);
            $month = date('n', $time);

            $objMonth = new Calendar_Month_Weekdays($year, $month, 1);
            $objMonth->build();
            $i = 0;
            while ($objDay = $objMonth->fetch()) {
                $arrCalendar[$j][$i]['in_month']    = $month == $objDay->month;
                $arrCalendar[$j][$i]['first']       = $objDay->first;
                $arrCalendar[$j][$i]['last']        = $objDay->last;
                $arrCalendar[$j][$i]['empty']       = $objDay->empty;
                $arrCalendar[$j][$i]['year']        = $year;
                $arrCalendar[$j][$i]['month']       = $month;
                $arrCalendar[$j][$i]['day']         = $objDay->day;
                $arrCalendar[$j][$i]['holiday']     = $this->lfCheckHoliday($year, $month, $objDay->day);
                $arrCalendar[$j][$i]['today']       = $today === sprintf('%04d/%02d/%02d', $year, $month, $objDay->day);

                $i++;
            }
        }

        return $arrCalendar;
    }
}
