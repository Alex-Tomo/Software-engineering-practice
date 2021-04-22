CREATE TABLE IF NOT EXISTS sep_users (
    user_id int not null auto_increment unique primary key,
    user_email varchar(128) not null unique,
    user_password varchar(128) not null
);

DELETE FROM sep_users WHERE user_email = 'user';
INSERT INTO sep_users (user_id, user_email, user_password) VALUES (10, 'fake1@email.com', 'password');
INSERT INTO sep_users (user_id, user_email, user_password) VALUES (11, 'fake2@email.com', 'password');
INSERT INTO sep_users (user_id, user_email, user_password) VALUES (12, 'fake3@email.com', 'password');
INSERT INTO sep_users (user_id, user_email, user_password) VALUES (13, 'fake4@email.com', 'password');
INSERT INTO sep_users (user_id, user_email, user_password) VALUES (14, 'fake5@email.com', 'password');
INSERT INTO sep_users (user_id, user_email, user_password) VALUES (15, 'fake6@email.com', 'password');
INSERT INTO sep_users (user_id, user_email, user_password) VALUES (16, 'fake7@email.com', 'password');

CREATE TABLE IF NOT EXISTS sep_languages (
    language_code varchar(2) not null unique primary key,
    language_name varchar(32) not null unique
);

CREATE TABLE IF NOT EXISTS sep_regions (
    region_code varchar(16) not null unique primary key,
    region_name varchar(32) not null unique
);

CREATE TABLE IF NOT EXISTS sep_user_info (
     userinfo_id int not null auto_increment unique primary key,
     user_id int not null,
     user_fname varchar(32) not null,
     user_lname varchar(32) not null,
     user_gender varchar(6) not null,
     user_language varchar(2) not null,
     user_region varchar(16) not null,
     FOREIGN KEY (user_id) REFERENCES sep_users(user_id),
     FOREIGN KEY (user_language) REFERENCES sep_languages(language_code),
     FOREIGN KEY (user_region) REFERENCES sep_regions(region_code)
);

INSERT INTO sep_user_info (user_id, user_fname, user_lname, user_gender, user_language, user_region) VALUES
(10, 'Emma', 'Tall', 'female', 'en', 'uk'),
(11, 'Jeffy', 'Kake', 'male', 'en', 'uk'),
(12, 'Isaac', 'Walterson', 'male', 'en', 'uk'),
(13, 'Bonny', 'Griffin', 'female', 'en', 'uk'),
(14, 'Bill', 'NoGates', 'male', 'en', 'uk'),
(15, 'Rebecca', 'Village', 'female', 'en', 'uk');
(16, 'Jacky', 'Kingsley', 'female', 'en', 'uk');


CREATE TABLE IF NOT EXISTS sep_jobs_list (
    job_code int(9) not null unique primary key,
    job_name varchar(64) not null
);

CREATE TABLE IF NOT EXISTS sep_users_interested_jobs (
    user_id int not null,
    job_code int(9) not null,
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id),
    FOREIGN KEY (job_code) REFERENCES sep_jobs_list(job_code)
);

INSERT INTO sep_users_interested_jobs VALUES (10, 1),
                                             (10, 4),
                                             (10, 5),
                                             (11, 1),
                                             (11, 4),
                                             (11, 5),
                                             (12, 2),
                                             (12, 10),
                                             (12, 19),
                                             (14, 1),
                                             (14, 4),
                                             (14, 5),
                                             (15, 8),
                                             (15, 10),
                                             (15, 17),
                                             (16, 1),
                                             (16, 4),
                                             (16, 5);
