ALTER TABLE ho_hematology ADD officer_is_approved INT(11) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER esign_is_approved;
ALTER TABLE ho_serology ADD officer_is_approved INT(11) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER esign_is_approved;
ALTER TABLE ho_urinalysis ADD officer_is_approved INT(11) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER esign_is_approved;
ALTER TABLE ho_fecalysis ADD officer_is_approved INT(11) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER esign_is_approved;
ALTER TABLE ho_pregnancy ADD officer_is_approved INT(11) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER esign_is_approved;
ALTER TABLE ho_blood_sugar_tests ADD officer_is_approved INT(11) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER esign_is_approved;
ALTER TABLE ho_pregnancy ADD officer_is_approved INT(11) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER esign_is_approved;
ALTER TABLE ho_gram_stainings ADD officer_is_approved INT(11) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER esign_is_approved;