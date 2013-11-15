DELETE FROM dtb_csv WHERE csv_id='5';

INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','category_id','カテゴリID','1','3','1','NOW()','NOW()','n','INT_LEN','NUM_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','category_name','カテゴリ名','2','1','1','NOW()','NOW()','KVa','STEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK,EXIST_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','hide_list_cart','カゴ非表示','3','1','1','NOW()','NOW()','n','INT_LEN','NUM_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','category_info','カテゴリ説明1','4','1','1','NOW()','NOW()','KVa','LLTEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','(SELECT product_id FROM dtb_category_recommend WHERE dtb_category_recommend.category_id = dtb_category.category_id ORDER BY dtb_category_recommend.rank LIMIT 1 OFFSET 0) AS category_recommend_product_id1','おすすめ商品ID','5','1','1','NOW()','NOW()','n','STEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','(SELECT name FROM dtb_category_recommend LEFT JOIN dtb_products USING(product_id) WHERE dtb_category_recommend.category_id = dtb_category.category_id ORDER BY dtb_category_recommend.rank LIMIT 1 OFFSET 0) as category_recommend_product_name1','おすすめ商品名','6','2','1','NOW()','NOW()','KVa','LTEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','(SELECT comment FROM dtb_category_recommend WHERE dtb_category_recommend.category_id = dtb_category.category_id ORDER BY dtb_category_recommend.rank LIMIT 1 OFFSET 0) AS category_recommend_product_comment1','おすすめコメント','7','1','1','NOW()','NOW()','KVa','LTEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','title','ページタイトル','8','1','1','NOW()','NOW()','KVa','STEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','h1','H1テキスト','9','1','1','NOW()','NOW()','KVa','STEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','description','メタタグ:Description','10','1','1','NOW()','NOW()','KVa','STEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','keyword','メタタグ:Keywords','11','1','1','NOW()','NOW()','KVa','STEXT_LEN','SPTAB_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','parent_category_id','親カテゴリID','12','1','1','NOW()','NOW()','n','INT_LEN','NUM_CHECK,MAX_LENGTH_CHECK,EXIST_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','level','階層','13','2','2','NOW()','NOW()','n','INT_LEN','NUM_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','rank','表示ランク','14','2','2','NOW()','NOW()','n','INT_LEN','NUM_CHECK,MAX_LENGTH_CHECK');
INSERT INTO dtb_csv VALUES((SELECT MAX(no)+1 FROM dtb_csv),'5','del_flg','削除フラグ','15','1','2','NOW()','NOW()','n','INT_LEN','NUM_CHECK,MAX_LENGTH_CHECK');
