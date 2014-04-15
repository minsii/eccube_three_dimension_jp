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
require_once CLASS_REALDIR . 'helper/SC_Helper_CSV.php';

/**
 * CSV関連のヘルパークラス(拡張).
 *
 * LC_Helper_CSV をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author LOCKON CO.,LTD.
 * @version $Id:SC_Helper_DB_Ex.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class SC_Helper_CSV_Ex extends SC_Helper_CSV {

    /**
     * 1次元配列を1行のCSVとして返す
     * 参考: http://jp.php.net/fputcsv
     * 
     * @param array $fields データ1次元配列
     * @param string $delimiter
     * @param string $enclosure
     * @param string $arrayDelimiter
     * @return string 結果行
     */
    function sfArrayToCsv($fields, $delimiter = ',', $enclosure = '"', $arrayDelimiter = '|') {
        if (strlen($delimiter) != 1) {
            trigger_error('delimiter must be a single character', E_USER_WARNING);
            return '';
        }
        
        if (strlen($enclosure) < 1) {
            trigger_error('enclosure must be a single character', E_USER_WARNING);
            return '';
        }
        
        foreach ( $fields as $key => $value ) {
            $field = & $fields[$key];
            
            // 配列を「|」区切りの文字列に変換する
            if (is_array($field)) {
                $field = implode($arrayDelimiter, $field);
            }
            
//             /* enclose a field that contains a delimiter, an enclosure character, or a newline */
//             if (is_string($field) && preg_match('/[' . preg_quote($delimiter) . preg_quote($enclosure) . '\\s]/', $field)) {
//                 $field = $enclosure . preg_replace('/' . preg_quote($enclosure) . '/', $enclosure . $enclosure, $field) . $enclosure;
//             }
            // 顧客様が手動で特殊コードを入れるから、すべての項目にenclosureを追加する
            $field = $enclosure . preg_replace('/' . preg_quote($enclosure) . '/', $enclosure . $enclosure, $field) . $enclosure;
        }
        
        return implode($delimiter, $fields);
    }
    
}
