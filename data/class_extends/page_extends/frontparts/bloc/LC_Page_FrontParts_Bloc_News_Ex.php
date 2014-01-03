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
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc_News.php';

/**
 * 新着情報 のページクラス(拡張).
 *
 * LC_Page_FrontParts_Bloc_News をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_FrontParts_Bloc_News_Ex.php 22796 2013-05-02 09:11:36Z h_yoshimoto $
 */
class LC_Page_FrontParts_Bloc_News_Ex extends LC_Page_FrontParts_Bloc_News {

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
    	$this->arrCampaign = $this->lfGetCampaign();
		parent::action();	
    }
    
    /**
     * キャンペン情報を取得する.
     *
     * @return array $arrCampaign キャンペン情報の配列を返す
     */
    function lfGetCampaign() {
    	$objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->setOrder('rank DESC ');
        $arrCampaign = $objQuery->select('*', 'dtb_campaign' ,'del_flg = 0');

        // モバイルサイトのセッション保持 (#797)
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            foreach ($arrCampaign as $key => $value) {
                $arrRow =& $arrCampaign[$key];
                if (SC_Utils_Ex::isAppInnerUrl($arrRow['url'])) {
                    $netUrl = new Net_URL($arrRow['url']);
                    $netUrl->addQueryString(session_name(), session_id());
                    $arrRow['url'] = $netUrl->getURL();
                }
            }
        }

        return $arrCampaign;
    }
}
