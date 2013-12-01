/*######## SEO管理 ########*/
ALTER TABLE dtb_pagelayout ADD COLUMN title text;
ALTER TABLE dtb_pagelayout ADD COLUMN h1 text;

ALTER TABLE dtb_products ADD COLUMN title text;
ALTER TABLE dtb_products ADD COLUMN h1 text;
ALTER TABLE dtb_products ADD COLUMN keyword text;
ALTER TABLE dtb_products ADD COLUMN description text;

ALTER TABLE dtb_category ADD COLUMN title text;
ALTER TABLE dtb_category ADD COLUMN h1 text;
ALTER TABLE dtb_category ADD COLUMN keyword text;
ALTER TABLE dtb_category ADD COLUMN description text;

INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_SEO',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  'SEO管理使用フラグ|true:使用');

/*######################■ダウンロード商品使用フラグ追加■######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_DOWNLOAD_PRODUCT', 'false', (SELECT MAX(rank)+1 FROM mtb_constants), 'ダウンロード商品使用フラグ|true:使用する');

/*######## 支払方法管理 ########*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PAYMENT_NOTE',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '支払方法説明使用フラグ|true:使用');


/*######## 追加規格 ########*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_EXTRA_CLASS', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '追加規格使用フラグ|true:使用する');
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('MAX_EXTRA_CLASS', '5', (SELECT MAX(rank)+1 FROM mtb_constants), '追加規格登録最大数');
CREATE TABLE dtb_extra_class(
  extra_class_id serial,
  "name" text,
  url text,
  rank integer,
  creator_id integer NOT NULL,
  create_date timestamp without time zone NOT NULL DEFAULT now(),
  update_date timestamp without time zone NOT NULL,
  del_flg smallint NOT NULL DEFAULT 0,
  CONSTRAINT dtb_extra_class_pkey PRIMARY KEY (extra_class_id)
);

CREATE TABLE dtb_extra_classcategory(
  extra_classcategory_id serial,
  "name" text,
  extra_class_id integer NOT NULL,
  rank integer,
  creator_id integer NOT NULL,
  create_date timestamp without time zone NOT NULL DEFAULT now(),
  update_date timestamp without time zone NOT NULL,
  del_flg smallint NOT NULL DEFAULT 0,
  CONSTRAINT dtb_extra_classcategory_pkey PRIMARY KEY (extra_classcategory_id)
);

CREATE TABLE dtb_products_extra_class(
  product_extra_class_id serial,
  product_id integer NOT NULL,
  extra_class_id integer NOT NULL,
  creator_id integer NOT NULL,
  create_date timestamp without time zone NOT NULL DEFAULT now(),
  update_date timestamp without time zone NOT NULL,
  CONSTRAINT dtb_products_extra_class_pkey PRIMARY KEY (product_extra_class_id)
);

INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_MULTIPLE_DELIV', 'false', (SELECT MAX(rank)+1 FROM mtb_constants), '複数配送使用フラグ|true:使用する');
ALTER TABLE dtb_order_detail ADD COLUMN extra_info text;

/*######## 顧客法人管理 ########*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_CUSTOMER_COMPANY',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '顧客法人使用フラグ|true:使用');
ALTER TABLE dtb_customer ADD COLUMN company text;
ALTER TABLE dtb_customer ADD COLUMN company_kana text;
ALTER TABLE dtb_customer ADD COLUMN company_department text;

ALTER TABLE dtb_other_deliv ADD COLUMN company text;
ALTER TABLE dtb_other_deliv ADD COLUMN company_kana text;
ALTER TABLE dtb_other_deliv ADD COLUMN company_department text;

ALTER TABLE dtb_order ADD COLUMN order_company text;
ALTER TABLE dtb_order ADD COLUMN order_company_kana text;
ALTER TABLE dtb_order ADD COLUMN order_company_department text;

ALTER TABLE dtb_shipping ADD COLUMN shipping_company text;
ALTER TABLE dtb_shipping ADD COLUMN shipping_company_kana text;
ALTER TABLE dtb_shipping ADD COLUMN shipping_company_department text;

ALTER TABLE dtb_order_temp ADD COLUMN order_company text;
ALTER TABLE dtb_order_temp ADD COLUMN order_company_kana text;
ALTER TABLE dtb_order_temp ADD COLUMN order_company_department text;

/*######## 顧客管理画面にお届け先一覧表示 ########*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_ADMIN_CUSTOMER_DELIV_LIST',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '顧客管理画面にお届け先一覧を表示するフラグ|true:表示');

/*######################■配送ランク■######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_DELIV_RANK', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '配送ランクを使用するフラグ|false:使用しない');
CREATE TABLE mtb_deliv_rank (
  id serial,
  "name" text,
  rank smallint NOT NULL DEFAULT 0,
  CONSTRAINT mtb_deliv_rank_pkey PRIMARY KEY (id)
);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('配送ランクA', 0);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('配送ランクB', 1);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('配送ランクC', 1);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('配送ランクD', 1);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('配送ランクE', 1);

ALTER TABLE dtb_delivfee ADD COLUMN deliv_rank integer DEFAULT 1;
ALTER TABLE dtb_delivfee DROP CONSTRAINT dtb_delivfee_pkey;
ALTER TABLE dtb_delivfee ADD CONSTRAINT dtb_delivfee_pkey PRIMARY KEY (deliv_id,fee_id,deliv_rank);

ALTER TABLE dtb_products ADD COLUMN deliv_rank integer DEFAULT 1;

/*######################■商品問い合わせ■######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_CONTACT', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '商品問い合わせ使用フラグ');
/*######################■事例問い合わせ■######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_JIREI_CONTACT', 'false', (SELECT MAX(rank)+1 FROM mtb_constants), '事例問い合わせ使用フラグ');

/*######## カテゴリお勧め商品 ########*/
CREATE TABLE dtb_category_recommend (
  category_recommend_id serial,
  category_id INT NOT NULL,
  product_id INT NOT NULL,
  rank INT NOT NULL,
  PRIMARY KEY (category_recommend_id)
);
ALTER TABLE dtb_category_recommend ADD COLUMN comment text;
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('CATEGORY_RECOMMEND_PRODUCT_MAX', '1', (SELECT MAX(rank)+1 FROM mtb_constants), 'カテゴリおすすめ商品最大登録数|数値:最大登録数|false:使用しない');

/*######## カテゴリ追加情報 ########*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_CATEGORY_INFO', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), 'カテゴリ追加情報使用フラグ|true:使用する');
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('CAT_MAINIMAGE_WIDTH', '600', (SELECT MAX(rank)+1 FROM mtb_constants), 'カテゴリ画像幅');
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('CAT_MAINIMAGE_HEIGHT', '600', (SELECT MAX(rank)+1 FROM mtb_constants), 'カテゴリ画像縦');
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('PRODUCT_LIST_MAX', '4', (SELECT MAX(rank)+1 FROM mtb_constants), '商品一覧最大表示数');

ALTER TABLE dtb_category ADD COLUMN category_info text;
ALTER TABLE dtb_category ADD COLUMN category_main_image_alt text;
ALTER TABLE dtb_category ADD COLUMN category_main_image text;

/*######################■商品支払方法指定■######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_PAYMENT', 'false', (SELECT MAX(rank)+1 FROM mtb_constants), '商品の支払方法指定を使用するフラグ|false:使用しない');
CREATE TABLE dtb_product_payment
(
  product_id integer NOT NULL,
  payment_id integer NOT NULL,
  CONSTRAINT dtb_product_payment_pkey PRIMARY KEY (product_id, payment_id)
);

/*######################■商品配送方法指定■######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_DELIV', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '商品の配送方法指定を使用するフラグ|false:使用しない');
CREATE TABLE dtb_product_deliv
(
  product_id integer NOT NULL,
  deliv_id integer NOT NULL,
  CONSTRAINT dtb_product_deliv_pkey PRIMARY KEY (product_id, deliv_id)
);

/*######################■商品一括並び替え■######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_BULK_RANK', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '商品一括並び替えを使用するフラグ|false:使用しない');

/*######################■商品並び替えで表示件数指定■######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_RANK_PMAX', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '商品並び替えで表示件数指定を使用するフラグ|false:使用しない');

/*######################■顧客お届け先FAX■######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_OTHER_DELIV_FAX',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '顧客お届け先FAX使用フラグ|true:使用');

/*######################■商品マスタ一覧で公開状態変更■######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PRODUCT_MASTER_DISP_EDIT',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '商品マスタ一覧で公開状態変更機能を使用するフラグ|true:使用');

/*######################■商品マスタ一覧で規格表示■######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PRODUCT_MASTER_SHOW_CLASS',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '商品マスタ一覧で規格表示機能を使用するフラグ|true:使用');

/*######################■商品マスタ一覧で在庫変更■######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PRODUCT_MASTER_STOCK_EDIT',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '商品マスタ一覧で在庫変更機能を使用するフラグ|true:使用');

/*######################■カテゴリ一覧でカゴ表示管理■######################*/
ALTER TABLE dtb_category ADD COLUMN hide_list_cart integer DEFAULT 0;

/*######################■商品ステータス2、ステータス3を追加■######################*/
CREATE TABLE mtb_status2(
  id serial,
  "name" text,
  rank smallint NOT NULL DEFAULT 0,
  CONSTRAINT mtb_status2_pkey PRIMARY KEY (id)
);
CREATE TABLE mtb_status_image2(
  id serial,
  "name" text,
  rank smallint NOT NULL DEFAULT 0,
  CONSTRAINT mtb_status_image2_pkey PRIMARY KEY (id)
);

CREATE TABLE mtb_status3(
  id serial,
  "name" text,
  rank smallint NOT NULL DEFAULT 0,
  CONSTRAINT mtb_status3_pkey PRIMARY KEY (id)
);
CREATE TABLE mtb_status_image3(
  id serial,
  "name" text,
  rank smallint NOT NULL DEFAULT 0,
  CONSTRAINT mtb_status_image3_pkey PRIMARY KEY (id)
);

INSERT INTO mtb_status2(name, rank) VALUES('反射機能', 1);
INSERT INTO mtb_status2(name, rank) VALUES('杖立て付き', 3);
INSERT INTO mtb_status2(name, rank) VALUES('アイコン', 3);

INSERT INTO mtb_status_image2(name, rank) VALUES('img/page/detail/icon_01.png', 1);
INSERT INTO mtb_status_image2(name, rank) VALUES('img/page/detail/icon_02.png', 3);
INSERT INTO mtb_status_image2(name, rank) VALUES('img/page/detail/icon_03.png', 3);

INSERT INTO mtb_status3(name, rank) VALUES('Good Design', 1);
INSERT INTO mtb_status3(name, rank) VALUES('JIS', 2);
INSERT INTO mtb_status3(name, rank) VALUES('S', 3);

INSERT INTO mtb_status_image3(name, rank) VALUES('img/page/detail/icon_01.png', 1);
INSERT INTO mtb_status_image3(name, rank) VALUES('img/page/detail/icon_02.png', 3);
INSERT INTO mtb_status_image3(name, rank) VALUES('img/page/detail/icon_03.png', 3);

CREATE TABLE dtb_product_status2(
  product_id integer NOT NULL,
  status2_id integer NOT NULL,
  CONSTRAINT dtb_product_status2_pkey PRIMARY KEY (product_id, status2_id)
);

CREATE TABLE dtb_product_status3(
  product_id integer NOT NULL,
  status3_id integer NOT NULL,
  CONSTRAINT dtb_product_status3_pkey PRIMARY KEY (product_id, status3_id)
);

/*######################■商品非課税指定■######################*/
ALTER TABLE dtb_products ADD COLUMN taxfree integer DEFAULT 0;
ALTER TABLE dtb_order_detail ADD COLUMN taxfree integer DEFAULT 0;
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_TAXFREE_PRODUCT',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '商品非課税機能を使用するフラグ|true:使用');


/*######################■会員登録項目カスタマイズ■######################*/
ALTER TABLE dtb_customer ADD COLUMN company_no text NOT NULL;
ALTER TABLE dtb_customer ADD COLUMN company_certified_date timestamp without time zone NOT NULL;
ALTER TABLE dtb_customer ADD COLUMN company_open_date timestamp without time zone;
ALTER TABLE dtb_customer ADD COLUMN message text;
ALTER TABLE dtb_customer ADD COLUMN need_category_check integer DEFAULT 0;

CREATE TABLE dtb_customer_company_type(
  customer_id integer NOT NULL,
  company_type_id integer NOT NULL,
  CONSTRAINT dtb_customer_company_type_pkey PRIMARY KEY (customer_id, company_type_id)
);

CREATE TABLE mtb_company_type(
  id serial,
  "name" text,
  rank smallint NOT NULL DEFAULT 0,
  CONSTRAINT mtb_company_type_pkey PRIMARY KEY (id)
);

INSERT INTO mtb_company_type(name, rank) VALUES('居宅介護支援', 1);
INSERT INTO mtb_company_type(name, rank) VALUES('訪問介護', 2);
INSERT INTO mtb_company_type(name, rank) VALUES('訪問入浴', 3);
INSERT INTO mtb_company_type(name, rank) VALUES('訪問リハピリ', 4);
INSERT INTO mtb_company_type(name, rank) VALUES('通所介護', 5);
INSERT INTO mtb_company_type(name, rank) VALUES('通所リハピリ', 6);
INSERT INTO mtb_company_type(name, rank) VALUES('療養通所介護', 7);
INSERT INTO mtb_company_type(name, rank) VALUES('認知症対応型通所解除', 8);
INSERT INTO mtb_company_type(name, rank) VALUES('小規模多機能型居宅介護', 9);
INSERT INTO mtb_company_type(name, rank) VALUES('短期入所療養介護', 10);
INSERT INTO mtb_company_type(name, rank) VALUES('介護老人福祉施設（特別養護老人ホーム）', 11);
INSERT INTO mtb_company_type(name, rank) VALUES('介護老人保健施設', 12);

UPDATE mtb_customer_status SET name='法人仮登録' WHERE id=1;
UPDATE mtb_customer_status SET name='法人会員' WHERE id=2;
UPDATE mtb_constants SET name='false' WHERE id='USE_CUSTOMER_COMPANY';

/*######################■本会員承認■######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_ADMIN_CUSTOMER_APPROVAL',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '管理側で本会員承認を使用するフラグ|true:使用');

/*######## サイトHTML化 ########*/
INSERT INTO mtb_constants (id ,name ,rank ,remarks) VALUES ('P_LIST_URLPATH',  'ROOT_URLPATH."products/list.php?category_id=%p"',  (SELECT MAX(rank)+1 FROM mtb_constants),  '商品一覧HTML出力');
UPDATE mtb_constants SET name='ROOT_URLPATH. "products/detail.php?product_id=%p"' WHERE id='P_DETAIL_URLPATH';

/*######## 商品一覧カスタマイズ ########*/
DELETE FROM mtb_product_list_max;
INSERT INTO mtb_product_list_max(id, name, rank) VALUES(12, '12件', 1);
INSERT INTO mtb_product_list_max(id, name, rank) VALUES(24, '24件', 2);
INSERT INTO mtb_product_list_max(id, name, rank) VALUES(48, '48件', 3);
INSERT INTO mtb_product_list_max(id, name, rank) VALUES(60, '60件', 4);
UPDATE mtb_constants SET name='160' WHERE id='SMALL_IMAGE_WIDTH';
UPDATE mtb_constants SET name='160' WHERE id='SMALL_IMAGE_HEIGHT';


/*######## 商品詳細で在庫表示 ########*/
INSERT INTO mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PRODUCT_CLASS_STOCK_TABLE',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '商品詳細で在庫表示を使用するフラグ|true:使用');


/*######## 商品詳細カスタマイズ ########*/
UPDATE mtb_constants SET name='360' WHERE id='NORMAL_IMAGE_WIDTH';
UPDATE mtb_constants SET name='360' WHERE id='NORMAL_IMAGE_HEIGHT';
UPDATE mtb_constants SET name='84' WHERE id='NORMAL_SUBIMAGE_WIDTH';
UPDATE mtb_constants SET name='84' WHERE id='NORMAL_SUBIMAGE_HEIGHT';
UPDATE mtb_constants SET name='500' WHERE id='LARGE_SUBIMAGE_WIDTH';
UPDATE mtb_constants SET name='500' WHERE id='LARGE_SUBIMAGE_HEIGHT';
UPDATE mtb_constants SET name='8' WHERE id='PRODUCTSUB_MAX';

ALTER TABLE dtb_products ADD COLUMN sub_title7 text;
ALTER TABLE dtb_products ADD COLUMN sub_comment7 text;
ALTER TABLE dtb_products ADD COLUMN sub_image7 text;
ALTER TABLE dtb_products ADD COLUMN sub_large_image7 text;
ALTER TABLE dtb_products ADD COLUMN sub_title8 text;
ALTER TABLE dtb_products ADD COLUMN sub_comment8 text;
ALTER TABLE dtb_products ADD COLUMN sub_image8 text;
ALTER TABLE dtb_products ADD COLUMN sub_large_image8 text;


/*######## マイページ予算実績 ########*/
INSERT INTO dtb_pagelayout VALUES (10, (SELECT MAX(page_id)+1 FROM dtb_pagelayout WHERE device_type_id=10), 'MYページ/予算実績確認', 'mypage/estimate.php', 'mypage/estimate', 1, 1, 2, NULL, NULL, NULL, NULL, 'now()', 'now()', NULL, NULL);

CREATE TABLE dtb_customer_month_estimate(
  customer_id integer,
  month_est_start_date timestamp without time zone,
  month_est_end_date timestamp without time zone,
  month_est_total numeric NOT NULL default 0,
  CONSTRAINT dtb_customer_month_estimate_pkey PRIMARY KEY (customer_id)
);

CREATE TABLE dtb_customer_year_estimate(
  customer_id integer,
  year_est_start_date timestamp without time zone,
  year_est_end_date timestamp without time zone,
  year_est_total numeric NOT NULL default 0,
  CONSTRAINT dtb_customer_year_estimate_pkey PRIMARY KEY (customer_id)
);

/*######## 最近の受注履歴 ########*/
INSERT INTO mtb_constants (id ,name ,rank ,remarks) VALUES ('LATEST_ORDER_MAX',  '１',  (SELECT MAX(rank)+1 FROM mtb_constants),  '最近の受注履歴表示数|数値:最大登録数|false:使用しない');


/*######## 最近のお気に入り ########*/
INSERT INTO mtb_constants (id ,name ,rank ,remarks) VALUES ('LATEST_FAVORITE_PRODUCT_MAX',  '3',  (SELECT MAX(rank)+1 FROM mtb_constants),  '最近のお気に入り商品表示数|数値:最大登録数|false:使用しない');

/*######## 会員新着情報 ########*/
CREATE TABLE dtb_customer_news (
  news_id integer NOT NULL,
  news_date timestamp without time zone,
  rank integer,
  news_title text NOT NULL,
  news_comment text,
  news_url text,
  news_select smallint NOT NULL DEFAULT 0,
  link_method text,
  creator_id integer NOT NULL,
  create_date timestamp without time zone NOT NULL DEFAULT now(),
  update_date timestamp without time zone NOT NULL,
  del_flg smallint NOT NULL DEFAULT 0,
  CONSTRAINT dtb_customer_news_pkey PRIMARY KEY (news_id)
);

CREATE SEQUENCE dtb_customer_news_news_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 4
  CACHE 1;