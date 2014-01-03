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
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * キャンペン管理 のページクラス(拡張).
 *
 * LC_Page_Admin_Contents_Campaign_Ex をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author m.si
 */
class LC_Page_Admin_Contents_Campaign_Ex extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        
        $this->tpl_mainpage = 'contents/campaign.tpl';
        $this->tpl_subno = 'campaign';
        $this->tpl_mainno = 'campaign';
        $this->tpl_maintitle = 'コンテンツ管理';
        $this->tpl_subtitle = 'キャンペン管理';
        
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrCAMPAIGN = $masterData->getMasterData('mtb_campaign');
        $this->arrCAMPAIGN_IMAGE = $masterData->getMasterData('mtb_campaign_image');
    }


    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {

        $objDb = new SC_Helper_DB_Ex();
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $campaign_id = $objFormParam->getValue('campaign_id');

        //---- 新規登録/編集登録
        switch ($this->getMode()) {
            case 'regist':
                $arrPost = $objFormParam->getHashArray();
                $this->arrErr = $this->lfCheckError($objFormParam);
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    $arrPost['link_method'] = $this->checkLinkMethod($arrPost['link_method']);
                    $member_id = $_SESSION['member_id'];
                    if (strlen($campaign_id) > 0 && is_numeric($campaign_id)) {
                        $this->lfCampaignUpdate($arrPost,$member_id);
                    } else {
                        $this->lfCampaignInsert($arrPost,$member_id);
                    }
                    $campaign_id = '';
                    $this->tpl_onload = "window.alert('編集が完了しました');";
                } else {
                    $this->arrForm = $arrPost;
                }
                break;
            case 'search':
                if (is_numeric($campaign_id)) {
                    list($this->arrForm) = $this->getCampaign($campaign_id);
                    $this->edit_mode = 'on';
                }
                break;
            case 'delete':
            //----　データ削除
                if (is_numeric($campaign_id)) {
                    $pre_rank = $this->getRankByCampaignId($campaign_id);
                    $this->computeRankForDelete($campaign_id,$pre_rank);

                    SC_Response_Ex::reload();             //自分にリダイレクト（再読込による誤動作防止）
                }
                break;
            case 'move':
            //----　表示順位移動
                if (strlen($campaign_id) > 0 && is_numeric($campaign_id) == true) {
                    $term = $objFormParam->getValue('term');
                    if ($term == 'up') {
                        $objDb->sfRankUp('dtb_campaign', 'campaign_id', $campaign_id);
                    } else if ($term == 'down') {
                        $objDb->sfRankDown('dtb_campaign', 'campaign_id', $campaign_id);
                    }

                    $this->objDisplay->reload();
                }
                break;
            case 'moveRankSet':
            //----　指定表示順位移動
                $input_pos = $this->getPostRank($campaign_id);
                if (SC_Utils_Ex::sfIsInt($input_pos)) {
                    $objDb->sfMoveRank('dtb_campaign', 'campaign_id', $campaign_id, $input_pos);

                    $this->objDisplay->reload();
                }
                break;
            default:
                break;
        }

        $this->arrCampaign = $this->getCampaign();
        $this->tpl_campaign_id = $campaign_id;
        $this->line_max = count($this->arrCampaign);
        $this->max_rank = $this->getRankMax();

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
     * 入力されたパラメーターのエラーチェックを行う。
     * @param Object $objFormParam
     * @return Array エラー内容
     */
    function lfCheckError(&$objFormParam) {
        $objErr = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();
        return $objErr->arrErr;
    }

    /**
     * パラメーターの初期化を行う
     * @param Object $objFormParam
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('campaign_id', 'campaign_id');
        $objFormParam->addParam('種類', 'type', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('内容', 'content', MTEXT_LEN, 'KVa', array('EXIST_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
        $objFormParam->addParam('URL', 'url', URL_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('別ウィンドウで開く', 'link_method', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ランク移動', 'term', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
    }

    /**
     * 新着記事のデータの登録を行う
     * @param Array $arrPost POSTデータの配列
     * @param Integer $member_id 登録した管理者のID
     */
    function lfCampaignInsert($arrPost,$member_id) {
        $objQuery = $objQuery =& SC_Query_Ex::getSingletonInstance();

        // rankの最大+1を取得する
        $rank_max = $this->getRankMax();
        $rank_max = $rank_max + 1;

        $table = 'dtb_campaign';
        $sqlval = array();
        $campaign_id = $objQuery->nextVal('dtb_campaign_campaign_id');
        $sqlval['campaign_id'] = $campaign_id;
        $sqlval['type'] = $arrPost['type'];
        $sqlval['content'] = $arrPost['content'];
        $sqlval['url'] = $arrPost['url'];
        $sqlval['link_method'] = $arrPost['link_method'];
        $sqlval['rank'] = $rank_max;
		$sqlval['creator_id'] = $member_id;
        $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->insert($table, $sqlval);
    }

    function lfCampaignUpdate($arrPost,$member_id) {
        $objQuery = $objQuery =& SC_Query_Ex::getSingletonInstance();

        $table = 'dtb_campaign';
        $sqlval = array();
        $sqlval['type'] = $arrPost['type'];
        $sqlval['content'] = $arrPost['content'];
        $sqlval['url'] = $arrPost['url'];
        $sqlval['link_method'] = $arrPost['link_method'];
        $sqlval['creator_id'] = $member_id;
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = 'campaign_id = ?';
        $arrValIn = array($arrPost['campaign_id']);
        $objQuery->update($table, $sqlval, $where, $arrValIn);
    }

    /**
     * チェックボックスの値が空の時は無効な値として1を格納する
     * @param int $link_method
     * @return int
     */
    function checkLinkMethod($link_method) {
        if (strlen($link_method) == 0) {
            $link_method = 1;
        }
        return $link_method;
    }

    /**
     * ニュース記事を取得する。
     * @param Integer campaign_id ニュースID
     */
    function getCampaign($campaign_id = '') {
        $objQuery = $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $table = 'dtb_campaign';
        $order = 'rank DESC';
        if (strlen($campaign_id) == 0) {
            $where = 'del_flg = 0';
            $arrWhereVal = array();
        } else {
            $where = 'del_flg = 0 AND campaign_id = ?';
            $arrWhereVal = array($campaign_id);
        }
        $objQuery->setOrder($order);
        return $objQuery->select($col, $table, $where, $arrWhereVal);
    }

    /**
     * 指定されたニュースのランクの値を取得する。
     * @param Integer $campaign_id
     */
    function getRankByCampaignId($campaign_id) {
        $objQuery = $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = 'rank';
        $table = 'dtb_campaign';
        $where = 'del_flg = 0 AND campaign_id = ?';
        $arrWhereVal = array($campaign_id);
        list($rank) = $objQuery->select($col, $table, $where, $arrWhereVal);
        return $rank['rank'];
    }

    /**
     * 削除する新着情報以降のrankを1つ繰り上げる。
     * @param Integer $campaign_id
     * @param Integer $rank
     */
    function computeRankForDelete($campaign_id,$rank) {
        SC_Helper_DB_Ex::sfDeleteRankRecord('dtb_campaign', 'campaign_id', $campaign_id);
    }


    /**
     * ランクの最大値の値を返す。
     * @return Intger $max ランクの最大値の値
     */
    function getRankMax() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = 'MAX(rank) as max';
        $table = 'dtb_campaign';
        $where = 'del_flg = 0';
        list($result) = $objQuery->select($col, $table, $where);
        return $result['max'];
    }

    /**
     * POSTされたランクの値を取得する
     * @param Object $objFormParam
     * @param Integer $campaign_id
     */
    function getPostRank($campaign_id) {
        if (strlen($campaign_id) > 0 && is_numeric($campaign_id) == true) {
            $key = 'pos-' . $campaign_id;
            $input_pos = $_POST[$key];
            return $input_pos;
        }
    }
}
