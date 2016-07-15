INSERT INTO %db_prefix%modules (module_name,module_display_name,module_description,module_status) VALUES ('doctor', 'Doctors',"Doctor's Profile, Schedule, Fees and more", '1');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('doctor', '', 300,'doctor', 'fa-user-md', 'Doctor','doctor');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('doctor_detail', 'doctor', 200, 'doctor/index', '', 'Doctor Detail','doctor');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('department', 'doctor', 300, 'doctor/department', '', 'Departments','doctor');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('fees_detail', 'doctor', 500, 'doctor/fees', '', 'Fees','doctor');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('doctor_schdule', 'doctor', 600, 'doctor/doctor_schedule', '', 'Doctor Schedule','doctor');
INSERT INTO %db_prefix%navigation_menu (menu_name,parent_name,menu_order,menu_url,menu_icon,menu_text,required_module) VALUES ('doctor_inavailability', 'doctor', 700, 'doctor/inavailability', '', 'Doctor Inavailability','doctor');
CREATE TABLE IF NOT EXISTS %db_prefix%department ( department_id int(11) NOT NULL AUTO_INCREMENT,  department_name varchar(100) NOT NULL,  PRIMARY KEY (department_id));
CREATE TABLE IF NOT EXISTS %db_prefix%doctor (  doctor_id int(11) NOT NULL AUTO_INCREMENT,  contact_id int(11) NOT NULL, degree varchar(150) NULL, specification varchar(300) NULL,experience varchar(300) NULL, joining_date date NULL, licence_number varchar(50) NULL,  department_id int(11) NULL,  gender varchar(10) NULL,userid VARCHAR(16) NULL , PRIMARY KEY (doctor_id));
CREATE TABLE IF NOT EXISTS %db_prefix%doctor_schedule ( schedule_id int(11) NOT NULL AUTO_INCREMENT, doctor_id int(11) NOT NULL,  schedule_day varchar(500) NOT NULL,  from_time time NOT NULL, to_time time NOT NULL,  PRIMARY KEY (schedule_id));
CREATE TABLE IF NOT EXISTS %db_prefix%fee_master ( id int(11) NOT NULL AUTO_INCREMENT,  `doctor_id` int(11) NOT NULL,  `detail` varchar(100) NOT NULL,  `fees` int(11) NOT NULL,  PRIMARY KEY (`id`));
-- 0.0.2
UPDATE %db_prefix%modules SET module_version = '0.0.2' WHERE module_name = 'doctor';
INSERT INTO %db_prefix%menu_access (menu_name,category_name,allow) VALUES ('doctor', 'Doctor', '1');
INSERT INTO %db_prefix%menu_access (menu_name,category_name,allow) VALUES ('doctor_detail', 'Doctor', '1');
INSERT INTO %db_prefix%menu_access (menu_name,category_name,allow) VALUES ('fees_detail', 'Doctor', '1');
INSERT INTO %db_prefix%menu_access (menu_name,category_name,allow) VALUES ('doctor_schdule', 'Doctor', '1');
INSERT INTO %db_prefix%menu_access (menu_name,category_name,allow) VALUES ('doctor_inavailability', 'Doctor', '1');
UPDATE %db_prefix%navigation_menu SET menu_text = 'Doctor Schedule' WHERE menu_name = 'doctor_schdule';
-- 0.0.3
UPDATE %db_prefix%modules SET module_version = '0.0.3' WHERE module_name = 'doctor';
-- 0.0.4
UPDATE %db_prefix%modules SET module_version = '0.0.4' WHERE module_name = 'doctor';

