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
