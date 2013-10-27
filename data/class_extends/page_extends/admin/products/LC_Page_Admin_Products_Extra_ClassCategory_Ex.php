<?php

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 追加規格分類 のページクラス.
 *
 * @package Page
 * @author simin
 */
class LC_Page_Admin_Products_Extra_ClassCategory_Ex extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'products/extra_classcategory.tpl';
        $this->tpl_subno = 'class';
        $this->tpl_maintitle = '商品管理';
        $this->tpl_subtitle = '追加規格管理＞分類登録';
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
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();
        $extra_class_id = $objFormParam->getValue('extra_class_id');
        $extra_classcategory_id = $objFormParam->getValue('extra_classcategory_id');

        switch($this->getMode()) {
        // 登録ボタン押下
        // 新規作成 or 編集
        case 'edit':
            // パラメーター値の取得
            $this->arrForm = $objFormParam->getHashArray();
                 
            // 入力パラメーターチェック
            $this->arrErr = $this->lfCheckError($objFormParam);
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                //新規規格追加かどうかを判定する
                $is_insert = $this->lfCheckInsert($extra_classcategory_id);
                if($is_insert) {
                    //新規追加
                    $this->lfInsertExtraClass($this->arrForm);
                } else {
                    //更新
                    $this->lfUpdateExtraClass($this->arrForm);
                }
                // 再表示
                SC_Response_Ex::reload();
            }
            break;
            // 削除
        case 'delete':
            // ランク付きレコードの削除
            $this->lfDeleteExtraClassCat($extra_class_id, $extra_classcategory_id);
            SC_Response_Ex::reload();
            break;
            // 編集前処理
        case 'pre_edit':
            // 規格名を取得する。
            $arrData = $this->lfGetExtraClassCat($extra_classcategory_id);
            if(is_array($arrData)){
            	foreach($arrData as $nm => $val){
            		$this->arrForm[$nm] = $val;
            	}
            }
            break;
        case 'down':
            //並び順を下げる
            $this->lfDownRank($extra_class_id, $extra_classcategory_id);
            SC_Response_Ex::reload();
            break;
        case 'up':
            //並び順を上げる
            $this->lfUpRank($extra_class_id, $extra_classcategory_id);
            SC_Response_Ex::reload();
            break;
        default:
            break;
        }
        //規格分類名の取得
        $this->tpl_extra_class_name = $this->lfGetExtraClassName($extra_class_id);
        //規格分類情報の取得
        $this->arrExtraClassCat = $this->lfListExtraClassCat($extra_class_id);
        // POSTデータを引き継ぐ
        $this->tpl_extra_classcategory_id = $extra_classcategory_id;
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
        $objFormParam->addParam("追加規格ID", "extra_class_id", INT_LEN, 'n', array("NUM_CHECK"));
        $objFormParam->addParam("追加規格分類名", 'name', STEXT_LEN, 'KVa', array("EXIST_CHECK" ,"SPTAB_CHECK" ,"MAX_LENGTH_CHECK"));
        $objFormParam->addParam("追加規格分類ID", "extra_classcategory_id", INT_LEN, 'n', array("NUM_CHECK"));
    }

   /**
     * 有効な規格分類情報の取得
     *
     * @param integer $extra_class_id 規格ID
     * @return array 規格分類情報
     */
    function lfListExtraClassCat($extra_class_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $where = "del_flg <> 1 AND extra_class_id = ?";
        $objQuery->setOrder("rank DESC"); // XXX 降順
        $arrExtraClassCat = $objQuery->select("name, extra_classcategory_id", "dtb_extra_classcategory", $where, array($extra_class_id));
        return $arrExtraClassCat;
    }

   /**
     * 規格名の取得
     *
     * @param integer $extra_class_id 規格ID
     * @return string 規格名
     */
    function lfGetExtraClassName($extra_class_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $where = "extra_class_id = ?";
        $name = $objQuery->get('name', "dtb_extra_class", $where, array($extra_class_id));
        return $name;
    }
   /**
     * 規格分類名を取得する
     *
     * @param integer $extra_classcategory_id 規格分類ID
     * @return string 規格分類名
     */
    function lfGetExtraClassCat($extra_classcategory_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = "extra_classcategory_id = ?";
        $arrRet = $objQuery->select('name', "dtb_extra_classcategory", $where, array($extra_classcategory_id));
        return $arrRet[0];
    }

   /**
     * 追加規格分類情報を新規登録
     *
     * @param array $arrForm フォームパラメータークラス
     * @return integer 更新件数
     */
    function lfInsertExtraClass($arrForm) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // 親規格IDの存在チェック
        $where = "del_flg <> 1 AND extra_class_id = ?";
        $extra_class_id = $objQuery->get("extra_class_id", "dtb_extra_class", $where, array($arrForm['extra_class_id']));
        if(!SC_Utils_Ex::isBlank($extra_class_id)) {
            // INSERTする値を作成する。
            $sqlval['name'] = $arrForm['name'];
            $sqlval['extra_class_id'] = $arrForm['extra_class_id'];
            $sqlval['creator_id'] = $_SESSION['member_id'];
            $sqlval['rank'] = $objQuery->max('rank', "dtb_extra_classcategory", $where, array($arrForm['extra_class_id'])) + 1;
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
            // INSERTの実行
            $ret = $objQuery->insert("dtb_extra_classcategory", $sqlval);
        }
        $objQuery->commit();
        return $ret;
    }

   /**
     * 追加規格分類情報を更新
     *
     * @param array $arrForm フォームパラメータークラス
     * @return integer 更新件数
     */
    function lfUpdateExtraClass($arrForm) {
        $objQuery = new SC_Query_Ex();
        // UPDATEする値を作成する。
        $sqlval['name'] = $arrForm['name'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "extra_classcategory_id = ?";
        // UPDATEの実行
        $ret = $objQuery->update("dtb_extra_classcategory", $sqlval, $where, array($arrForm['extra_classcategory_id']));
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
        
        if(empty($arrForm['extra_classcategory_id'])) $arrForm['extra_classcategory_id'] = '0';
       	if ($objQuery->count("dtb_extra_classcategory", "name = ? AND extra_class_id = ? AND extra_classcategory_id <> ? AND del_flg = 0 ", 
        		array($arrForm['name'], $arrForm['extra_class_id'], $arrForm['extra_classcategory_id']))) {
            $arrErr['name'] = "※ 既に同じ内容の登録が存在します。<br>";
        }        
        return $arrErr;
    }

    /**
     * 新規規格分類追加かどうかを判定する.
     *
     * @param integer $extra_classcategory_id 規格分類ID
     * @return boolean 新規商品追加の場合 true
     */
    function lfCheckInsert($extra_classcategory_id) {
        //extra_classcategory_id のあるなしで新規規格分類化かどうかを判定
        if (empty($extra_classcategory_id)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 規格分類情報を削除する
     *
     * @param integer $extra_class_id 規格ID
     * @param integer $extra_classcategory_id 規格分類ID
     * @return void
     */
    function lfDeleteExtraClassCat($extra_class_id, $extra_classcategory_id) {
        $objDb = new SC_Helper_DB_Ex();
        $where = "extra_class_id = " . SC_Utils_Ex::sfQuoteSmart($extra_class_id);
        $objDb->sfDeleteRankRecord("dtb_extra_classcategory", "extra_classcategory_id", $extra_classcategory_id, $where, true);
    }
    /**
     * 並び順を上げる
     *
     * @param integer $extra_class_id 規格ID
     * @param integer $extra_classcategory_id 規格分類ID
     * @return void
     */
    function lfUpRank($extra_class_id, $extra_classcategory_id) {
        $objDb = new SC_Helper_DB_Ex();
        $where = "extra_class_id = " . SC_Utils_Ex::sfQuoteSmart($extra_class_id);
        $objDb->sfRankUp("dtb_extra_classcategory", "extra_classcategory_id", $extra_classcategory_id, $where);
    }
    /**
     * 並び順を下げる
     *
     * @param integer $extra_class_id 規格ID
     * @param integer $extra_classcategory_id 規格分類ID
     * @return void
     */
    function lfDownRank($extra_class_id, $extra_classcategory_id) {
        $objDb = new SC_Helper_DB_Ex();
        $where = "extra_class_id = " . SC_Utils_Ex::sfQuoteSmart($extra_class_id);
        $objDb->sfRankDown("dtb_extra_classcategory", "extra_classcategory_id", $extra_classcategory_id, $where);
    }
}
?>
