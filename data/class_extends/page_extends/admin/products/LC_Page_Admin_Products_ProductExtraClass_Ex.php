<?php

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 商品登録(商品追加規格)のページクラス.
 *
 * @package Page
 * @author simin
 */
class LC_Page_Admin_Products_ProductExtraClass_Ex extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'products/product_extra_class.tpl';
        $this->tpl_mainno = 'products';
        $this->tpl_subno = 'product';
        $this->tpl_maintitle = '商品管理';
        $this->tpl_subtitle = '商品登録(商品追加規格)';
        $masterData = new SC_DB_MasterData_Ex();
        // 追加規格プルダウンのリスト
        $this->arrExtraClass = $this->getAllExtraClass();
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
		    	
        // 商品マスターの検索条件パラメーターを初期化
        $objFormParam = new SC_FormParam_Ex();
        $this->initParam($objFormParam);

        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $this->arrSearchHidden = $objFormParam->getSearchArray();

        switch ($this->getMode()) {

        // 初期表示
        case 'pre_edit':
            $this->doPreEdit($objFormParam);
            break;
            
        case 'edit':
        	$this->arrErr = $objFormParam->checkError();
        	if(!count($this->arrErr)){
        		$this->registerData($objFormParam);
        		
        		$this->tpl_complete = 1;
        	}
            break;

        default:
        }

        // 登録対象の商品名を取得
        $this->tpl_product_name =  $this->getProductName($objFormParam->getValue('product_id'));
        $this->arrForm = $objFormParam->getHashArray();
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
     * パラメーター初期化
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function initParam(&$objFormParam) {
    	$objFormParam->addParam('product_id', 'product_id', INT_LEN, 'n', array("NUM_CHECK"));
         // 規格プルダウン
        for($i=1; $i <= MAX_EXTRA_CLASS; $i++){
        	$objFormParam->addParam("追加規格$i", "extra_class_id$i", INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));}
    }

    /**
     * 追加規格の更新を行う.
     *
     * @param $objFormParam
     */
    function registerData($objFormParam) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrForm = $objFormParam->getHashArray();
        
        if(empty($arrForm["product_id"])){
        	return false;
        }
        
        $sqlval = array();
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['product_id'] = $arrForm["product_id"];

       	// 全部の追加規格を削除してから、画面の入力を全部新規挿入する
        $objQuery->begin();
        $objQuery->delete("dtb_products_extra_class", "product_id=?", array($arrForm["product_id"]));
		
        for($i=1; $i <= MAX_EXTRA_CLASS; $i++){
        	if(!empty($arrForm["extra_class_id$i"])){
        		$sqlval["extra_class_id"] = $arrForm["extra_class_id$i"];
        		$objQuery->insert("dtb_products_extra_class", $sqlval);
        	}
        }
        $objQuery->commit();
    }

 
    /**
     * 追加規格編集画面を表示する
     *
     */
    function doPreEdit(&$objFormParam) {
    	$product_id = $objFormParam->getValue("product_id");
    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	
    	if(empty($product_id)){
    		return false;
    	}

    	$objQuery->setOrder("product_extra_class_id");
    	$arrRet = $objQuery->getCol("extra_class_id", "dtb_products_extra_class", "product_id=?", array($product_id));

    	$i = 1;
    	if(is_array($arrRet)){
    		foreach($arrRet as $col){
    			$objFormParam->setValue("extra_class_id$i", $col);
    			$i++;
    		}
    	}
    }
    
    /**
     * 商品名を取得する.
     *
     * @access private
     * @param integer $product_id 商品ID
     * @return string 商品名の文字列
     */
    function getProductName($product_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        return $objQuery->get('name', 'dtb_products', 'product_id = ?', array($product_id));
    }  

    /**
     * 追加規格一覧を取得する
     * 
     */
    function getAllExtraClass(){
    	$objQuery =& SC_Query_Ex::getSingletonInstance();
    	$objQuery->setOrder("rank DESC");
    	$arrRet = $objQuery->select("extra_class_id, name", "dtb_extra_class");
    	
    	$arrExtraClass = array();
    	if(is_array($arrRet)){
    		foreach($arrRet as $row){
    			$arrExtraClass[$row["extra_class_id"]] = $row["name"]; 
    		}
    	}
    	return $arrExtraClass;
    }
}
