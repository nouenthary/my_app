-- 1
ALTER TABLE tec_categories
ADD COLUMN is_active TINYINT DEFAULT 1,
ADD COLUMN parent_id INT,
ADD COLUMN user_id INT,
ADD COLUMN created_at datetime
-- 2
CREATE TABLE brands (
    id INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(255) NOT NULL,
    brand_name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    user_id INT,
    is_active TINYINT DEFAULT 1,
    created_at datetime,
    PRIMARY KEY (ID)
);
-- 3
ALTER TABLE tec_products
ADD COLUMN brand_id INT ,
ADD COLUMN unit VARCHAR(255),
ADD COLUMN is_active TINYINT DEFAULT 1,
ADD COLUMN created_at datetime DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN user_id INT,
ADD COLUMN branch_commission FLOAT DEFAULT 0,
ADD COLUMN staff_commission FLOAT DEFAULT 0,
ADD COLUMN other_commission FLOAT DEFAULT 0
-- 4
CREATE TABLE variants (
    id INT NOT NULL AUTO_INCREMENT,
    variant VARCHAR(255) NOT NULL,
    product_id INT,
    PRIMARY KEY (id)
);

CREATE TABLE `return_imports` (
	id BIGINT NOT NULL AUTO_INCREMENT,
	stock_in_id INT,
	product_id INT,
	store_id INT,
	qty DECIMAL,
	image VARCHAR ( 255 ),
	created_by INT,
	created_date datetime,
	remark VARCHAR ( 255 ),
	warehouse_id INT,
	reference_no VARCHAR ( 255 ),
	return_by INT,
return_date datetime,
PRIMARY KEY ( id ));
