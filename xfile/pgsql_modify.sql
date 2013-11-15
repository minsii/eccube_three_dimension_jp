/*######## SEO�Ǘ� ########*/
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

INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_SEO',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  'SEO�Ǘ��g�p�t���O|true:�g�p');

/*######################���_�E�����[�h���i�g�p�t���O�ǉ���######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_DOWNLOAD_PRODUCT', 'false', (SELECT MAX(rank)+1 FROM mtb_constants), '�_�E�����[�h���i�g�p�t���O|true:�g�p����');

/*######## �x�����@�Ǘ� ########*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PAYMENT_NOTE',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '�x�����@�����g�p�t���O|true:�g�p');


/*######## �ǉ��K�i ########*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_EXTRA_CLASS', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '�ǉ��K�i�g�p�t���O|true:�g�p����');
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('MAX_EXTRA_CLASS', '5', (SELECT MAX(rank)+1 FROM mtb_constants), '�ǉ��K�i�o�^�ő吔');
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

INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_MULTIPLE_DELIV', 'false', (SELECT MAX(rank)+1 FROM mtb_constants), '�����z���g�p�t���O|true:�g�p����');
ALTER TABLE dtb_order_detail ADD COLUMN extra_info text;

/*######## �ڋq�@�l�Ǘ� ########*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_CUSTOMER_COMPANY',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '�ڋq�@�l�g�p�t���O|true:�g�p');
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

/*######## �ڋq�Ǘ���ʂɂ��͂���ꗗ�\�� ########*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_ADMIN_CUSTOMER_DELIV_LIST',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '�ڋq�Ǘ���ʂɂ��͂���ꗗ��\������t���O|true:�\��');

/*######################���z�������N��######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_DELIV_RANK', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '�z�������N���g�p����t���O|false:�g�p���Ȃ�');
CREATE TABLE mtb_deliv_rank (
  id serial,
  "name" text,
  rank smallint NOT NULL DEFAULT 0,
  CONSTRAINT mtb_deliv_rank_pkey PRIMARY KEY (id)
);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('�z�������NA', 0);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('�z�������NB', 1);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('�z�������NC', 1);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('�z�������ND', 1);
INSERT INTO mtb_deliv_rank(name, rank) VALUES('�z�������NE', 1);

ALTER TABLE dtb_delivfee ADD COLUMN deliv_rank integer DEFAULT 1;
ALTER TABLE dtb_delivfee DROP CONSTRAINT dtb_delivfee_pkey;
ALTER TABLE dtb_delivfee ADD CONSTRAINT dtb_delivfee_pkey PRIMARY KEY (deliv_id,fee_id,deliv_rank);

ALTER TABLE dtb_products ADD COLUMN deliv_rank integer DEFAULT 1;

/*######################�����i�₢���킹��######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_CONTACT', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '���i�₢���킹�g�p�t���O');
/*######################������₢���킹��######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_JIREI_CONTACT', 'false', (SELECT MAX(rank)+1 FROM mtb_constants), '����₢���킹�g�p�t���O');

/*######## �J�e�S�������ߏ��i ########*/
CREATE TABLE dtb_category_recommend (
  category_recommend_id serial,
  category_id INT NOT NULL,
  product_id INT NOT NULL,
  rank INT NOT NULL,
  PRIMARY KEY (category_recommend_id)
);
ALTER TABLE dtb_category_recommend ADD COLUMN comment text;
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('CATEGORY_RECOMMEND_PRODUCT_MAX', '1', (SELECT MAX(rank)+1 FROM mtb_constants), '�J�e�S���������ߏ��i�ő�o�^��|���l:�ő�o�^��|false:�g�p���Ȃ�');

/*######## �J�e�S���ǉ���� ########*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_CATEGORY_INFO', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '�J�e�S���ǉ����g�p�t���O|true:�g�p����');
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('CAT_MAINIMAGE_WIDTH', '600', (SELECT MAX(rank)+1 FROM mtb_constants), '�J�e�S���摜��');
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('CAT_MAINIMAGE_HEIGHT', '600', (SELECT MAX(rank)+1 FROM mtb_constants), '�J�e�S���摜�c');
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('PRODUCT_LIST_MAX', '4', (SELECT MAX(rank)+1 FROM mtb_constants), '���i�ꗗ�ő�\����');

ALTER TABLE dtb_category ADD COLUMN category_info text;
ALTER TABLE dtb_category ADD COLUMN category_main_image_alt text;
ALTER TABLE dtb_category ADD COLUMN category_main_image text;

/*######################�����i�x�����@�w�聡######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_PAYMENT', 'false', (SELECT MAX(rank)+1 FROM mtb_constants), '���i�̎x�����@�w����g�p����t���O|false:�g�p���Ȃ�');
CREATE TABLE dtb_product_payment
(
  product_id integer NOT NULL,
  payment_id integer NOT NULL,
  CONSTRAINT dtb_product_payment_pkey PRIMARY KEY (product_id, payment_id)
);

/*######################�����i�z�����@�w�聡######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_DELIV', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '���i�̔z�����@�w����g�p����t���O|false:�g�p���Ȃ�');
CREATE TABLE dtb_product_deliv
(
  product_id integer NOT NULL,
  deliv_id integer NOT NULL,
  CONSTRAINT dtb_product_deliv_pkey PRIMARY KEY (product_id, deliv_id)
);

/*######################�����i�ꊇ���ёւ���######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_BULK_RANK', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '���i�ꊇ���ёւ����g�p����t���O|false:�g�p���Ȃ�');

/*######################�����i���ёւ��ŕ\�������w�聡######################*/
INSERT INTO mtb_constants(id, name, rank, remarks) VALUES('USE_PRODUCT_RANK_PMAX', 'true', (SELECT MAX(rank)+1 FROM mtb_constants), '���i���ёւ��ŕ\�������w����g�p����t���O|false:�g�p���Ȃ�');

/*######################���ڋq���͂���FAX��######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_OTHER_DELIV_FAX',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '�ڋq���͂���FAX�g�p�t���O|true:�g�p');

/*######################�����i�}�X�^�ꗗ�Ō��J��ԕύX��######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PRODUCT_MASTER_DISP_EDIT',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '���i�}�X�^�ꗗ�Ō��J��ԕύX�@�\���g�p����t���O|true:�g�p');

/*######################�����i�}�X�^�ꗗ�ŋK�i�\����######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PRODUCT_MASTER_SHOW_CLASS',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '���i�}�X�^�ꗗ�ŋK�i�\���@�\���g�p����t���O|true:�g�p');

/*######################�����i�}�X�^�ꗗ�ō݌ɕύX��######################*/
INSERT INTO  mtb_constants (id ,name ,rank ,remarks) VALUES ('USE_PRODUCT_MASTER_STOCK_EDIT',  'true',  (SELECT MAX(rank)+1 FROM mtb_constants),  '���i�}�X�^�ꗗ�ō݌ɕύX�@�\���g�p����t���O|true:�g�p');

/*######################���J�e�S���ꗗ�ŃJ�S�\���Ǘ���######################*/
ALTER TABLE dtb_category ADD COLUMN hide_list_cart integer DEFAULT 0;

/*######################�����i�X�e�[�^�X2�A�X�e�[�^�X3��ǉ���######################*/
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

INSERT INTO mtb_status2(name, rank) VALUES('���ˋ@�\', 1);
INSERT INTO mtb_status2(name, rank) VALUES('�񗧂ĕt��', 3);
INSERT INTO mtb_status2(name, rank) VALUES('�A�C�R��', 3);

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

/*######################�����i��ېŎw�聡######################*/
ALTER TABLE dtb_products ADD COLUMN taxfree integer DEFAULT 0;

