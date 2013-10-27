<?php

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 追加規格管理 のページクラス(拡張).
 *
 *
 * @package Page
 * @author simin
 */
class LC_Page_Admin_Products_Extra_Class_Ex extends LC_Page_Admin_Ex {


    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'products/extra_class.tpl';
        $this->tpl_subno = 'extra_class';
        $this->tpl_subtitle = '追加規格管理';
        $this->tpl_maintitle = '商品管理';
        $this->tpl_mainno = 'products';
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
    	if(USE_EXTRA_CLASS !== true){
			// エラーページの表示
			SC_Utils::sfDispError(AUTH_ERROR);
		}	
    	
        $objFormParam = new SC_FormParam_Ex();

        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $extra_class_id = $objFormParam->getValue('extra_class_id');

        // 要求判定
        switch($this->getMode()) {
            // 編集処理
        case 'edit':
            //パラメーターの取得
            $this->arrForm  = $objFormParam->getHashArray();
            // 入力パラメーターチェック
            $this->arrErr = $this->lfCheckError($objFormParam);
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                //新規追加規格追加かどうかを判定する
                $is_insert = $this->lfCheckInsert($this->arrForm);
                if($is_insert) {
                    $this->lfInsertClass($objFormParam); // 新規作成
                } else {
                    $this->lfUpdateClass($objFormParam); // 既存編集
                }
                // 再表示
                SC_Response_Ex::reload();
            }
            break;
            // 削除
        case 'delete':
            //追加規格データの削除処理
            $this->lfDeleteClass($extra_class_id);
            // 再表示
            SC_Response_Ex::reload();
            break;
            // 編集前処理
        case 'pre_edit':
            // 追加規格情報を取得する。
            $arrData = $this->lfGetExtraClass($extra_class_id);
            if(is_array($arrData)){
            	foreach($arrData as $nm => $val){
            		$this->arrForm[$nm] = $val;
            	}
            }
            break;
        case 'down':
            $this->lfDownRank($extra_class_id);
            // 再表示
            SC_Response_Ex::reload();
            break;
        case 'up':
            $this->lfUpRank($extra_class_id);
            // 再表示
            SC_Response_Ex::reload();
            break;
        default:
            break;
        }
        // 追加規格一覧の読込
        $this->arrExtraClass = $this->lfListExtraClass();
        $this->arrExtraClassCatCount = SC_Utils_Ex::sfGetExtraClassCatCount();
        // POSTデータを引き継ぐ
        $this->tpl_extra_class_id = $extra_class_id;
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
     * パラメーターの初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("追加規格名", 'name', STEXT_LEN, 'KVa', array("EXIST_CHECK" ,"SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objFormParam->addParam("URL", 'url', STEXT_LEN, 'KVa', array("SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objFormParam->addParam("規格ID", "extra_class_id", INT_LEN, 'n', array("NUM_CHECK"));
    }

   /**
     * 有効な追加規格情報を一覧する
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return array 追加規格情報
     */
    function lfListExtraClass($arrData) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $where = "del_flg <> 1";
        $objQuery->setOrder("rank DESC");
        $arrExtraClass = $objQuery->select("name, extra_class_id, url", "dtb_extra_class", $where);
        return $arrExtraClass;
    }

   /**
     * 追加規格情報を取得する
     *
     * @param integer $extra_class_id 追加規格ID
     * @return array
     */
    function lfGetExtraClass($extra_class_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = "extra_class_id = ?";
        $arrRet = $objQuery->select('name, extra_class_id, url', "dtb_extra_class", $where, array($extra_class_id));
        return $arrRet[0];
    }

   /**
     * 追加規格情報を新規登録
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return integer 更新件数
     */
    function lfInsertClass($objFormParam) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // INSERTする値を作成する。
        $sqlval = $objFormParam->getHashArray();
        unset($sqlval["extra_class_id"]);
        
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['rank'] = $objQuery->max('rank', "dtb_extra_class") + 1;
        $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        // INSERTの実行
        $ret = $objQuery->insert("dtb_extra_class", $sqlval);
        return $ret;
    }

   /**
     * 追加規格情報を更新
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return integer 更新件数
     */
    function lfUpdateClass($objFormParam) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // UPDATEする値を作成する。
        $sqlval = $objFormParam->getHashArray();
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "extra_class_id = ?";
        // UPDATEの実行
        $ret = $objQuery->update("dtb_extra_class", $sqlval, $where, array($sqlval['extra_class_id']));
        return $ret;
    }

    /**
     * 規格情報を削除する.
     *
     * @param integer $extra_class_id 規格ID
     * @param SC_Helper_DB $objDb SC_Helper_DBのインスタンス
     * @return integer 更新件数
     */
    function lfDeleteClass($extra_class_id) {
        $objDb = new SC_Helper_DB_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $objDb->sfDeleteRankRecord("dtb_extra_class", "extra_class_id", $extra_class_id, "", true);
        $where= "extra_class_id = ?";
        $ret = $objQuery->delete("dtb_extra_classcategory", $where, array($extra_class_id));
        return $ret;
    }

   /**
     * エラーチェック
     *
     * @param array $objFormParam フォームパラメータークラス
     * @return array エラー配列
     */
    function lfCheckError(&$objFormParam) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrForm = $objFormParam->getHashArray();
        // パラメーターの基本チェック
        $arrErr = $objFormParam->checkError();
        if (!SC_Utils_Ex::isBlank($arrErr)) {
            return $arrErr;
        }else{
            $arrForm = $objFormParam->getHashArray();
        }

        // 編集中のレコード以外に同じ名称が存在する場合
        if(empty($arrForm['extra_class_id'])) $arrForm['extra_class_id'] = '0';
        if ($objQuery->count("dtb_extra_class", "name = ? AND extra_class_id <> ? AND del_flg = 0 ", 
        		array($arrForm['name'], $arrForm['extra_class_id']))) {
            $arrErr['name'] = "※ 既に同じ内容の登録が存在します。<br>";
        }
        return $arrErr;
    }

    /**
     * 新規追加規格追加かどうかを判定する.
     *
     * @param string $arrForm フォームの入力値
     * @return boolean 新規商品追加の場合 true
     */
    function lfCheckInsert($arrForm) {
        //extra_class_id のあるなしで新規商品かどうかを判定
        if (empty($arrForm['extra_class_id'])){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 並び順を上げる 
     *
     * @param integer $extra_class_id 規格ID
     * @return void
     */
    function lfUpRank($extra_class_id) {
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRankUp("dtb_extra_class", "extra_class_id", $extra_class_id);
    }
    /**
     * 並び順を下げる
     *
     * @param integer $extra_class_id 規格ID
     * @return void
     */
    function lfDownRank($extra_class_id) {
        $objDb = new SC_Helper_DB_Ex();
        $objDb->sfRankDown("dtb_extra_class", "extra_class_id", $extra_class_id);
    }
}
?>
