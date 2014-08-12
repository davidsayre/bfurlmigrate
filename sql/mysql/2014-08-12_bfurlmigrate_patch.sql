ALTER table bfurlmigrate ADD bfurl_id INT PRIMARY KEY AUTO_INCREMENT;
ALTER TABLE bfurlmigrate ADD UNIQUE INDEX `uq_url` (`url` ASC);
ALTER TABLE bfurlmigrate ADD UNIQUE INDEX `uq_remote_id` (`remote_id` ASC);