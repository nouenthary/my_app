-- 1
ALTER TABLE tec_categories
ADD COLUMN is_active TINYINT,
ADD COLUMN parent_id INT,
ADD COLUMN user_id INT,
ADD COLUMN created_at datetime
