CREATE TABLE IF NOT EXISTS sep_users (
    user_id int not null auto_increment unique primary key,
    user_email varchar(128) not null unique,
    user_password varchar(128) not null
);

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

INSERT INTO sep_languages VALUES ("AR", "Arabic"),
                                 ("BE", "Belarusian"),
                                 ("BG", "Bulgarian"),
                                 ("BS", "Bosnian"),
                                 ("CS", "Czech"),
                                 ("CY", "Welsh"),
                                 ("DA", "Danish"),
                                 ("DE", "German"),
                                 ("EL", "Greek"),
                                 ("EN", "English"),
                                 ("ES", "Spanish"),
                                 ("ET", "Estonian"),
                                 ("FA", "Persian"),
                                 ("FI", "Finnish"),
                                 ("FJ", "Fijian"),
                                 ("FR", "French"),
                                 ("GA", "Irish"),
                                 ("HE", "Hebrew"),
                                 ("HI", "Hindi"),
                                 ("HR", "Croatian"),
                                 ("HT", "Haitian"),
                                 ("HU", "Hungarian"),
                                 ("HY", "Armenian"),
                                 ("ID", "Indonesian"),
                                 ("IS", "Icelandic"),
                                 ("IT", "Italian"),
                                 ("JS", "Japanese"),
                                 ("KO", "Korean"),
                                 ("LB", "Luxembourgish"),
                                 ("LT", "Lithuanian"),
                                 ("LV", "Latvian"),
                                 ("MN", "Mongolian"),
                                 ("MT", "Maltese"),
                                 ("MY", "Burmese"),
                                 ("NL", "Dutch"),
                                 ("NO", "Norwegian"),
                                 ("NV", "Navajo"),
                                 ("PL", "Polish"),
                                 ("PT", "Portuguese"),
                                 ("RO", "Romanian"),
                                 ("RU", "Russian"),
                                 ("SK", "Slovak"),
                                 ("SQ", "Albanian"),
                                 ("SR", "Serbian"),
                                 ("SV", "Swedish"),
                                 ("TH", "Thai"),
                                 ("TR", "Turkish"),
                                 ("UK", "Ukrainian"),
                                 ("VI", "Vietnamese"),
                                 ("ZH", "Chinese");

CREATE TABLE IF NOT EXISTS sep_regions (
    region_code varchar(16) not null unique primary key,
    region_name varchar(32) not null unique
);

INSERT INTO sep_regions VALUES ("AF", "Afghanistan"),
                               ("AL", "Albania"),
                               ("DZ", "Algeria"),
                               ("AR", "Argentina"),
                               ("AM", "Armenia"),
                               ("AU", "Australia"),
                               ("AT", "Austria"),
                               ("BS", "Bahamas"),
                               ("BH", "Bahrain"),
                               ("BD", "Bangladesh"),
                               ("BY", "Belarus"),
                               ("BE", "Belgium"),
                               ("BO", "Bolivia"),
                               ("BR", "Brazil"),
                               ("BG", "Bulgaria"),
                               ("KH", "Cambodia"),
                               ("CA", "Canada"),
                               ("CL", "Chile"),
                               ("CN", "China"),
                               ("CO", "Colombia"),
                               ("CG", "Congo"),
                               ("CR", "Costa Rica"),
                               ("HR", "Croatia"),
                               ("CU", "Cuba"),
                               ("CY", "Cyprus"),
                               ("CZ", "Czech Republic"),
                               ("DK", "Denmark"),
                               ("DO", "Dominican Republic"),
                               ("EC", "Ecuador"),
                               ("EG", "Egypt"),
                               ("SV", "El Salvador"),
                               ("EE", "Estonia"),
                               ("FJ", "Fiji"),
                               ("FI", "Finland"),
                               ("FR", "France"),
                               ("DE", "Germany"),
                               ("GI", "Gibraltar"),
                               ("GR", "Greece"),
                               ("GL", "Greenland"),
                               ("HT", "Haiti"),
                               ("HK", "Hong Kong"),
                               ("HU", "Hungary"),
                               ("IS", "Iceland"),
                               ("IN", "India"),
                               ("ID", "Indonesia"),
                               ("IR", "Iran"),
                               ("IQ", "Iraq"),
                               ("IE", "Ireland"),
                               ("IL", "Israel"),
                               ("IT", "Italy"),
                               ("JM", "Jamaica"),
                               ("JP", "Japan"),
                               ("JO", "Jordan"),
                               ("KZ", "Kazakstan"),
                               ("KE", "Kenya"),
                               ("KR", "Korea"),
                               ("KW", "Kuwait"),
                               ("LV", "Latvia"),
                               ("LB", "Lebanon"),
                               ("LT", "Lithuania"),
                               ("LU", "Luxembourg"),
                               ("MY", "Malaysia"),
                               ("MT", "Malta"),
                               ("MX", "Mexico"),
                               ("MC", "Monaco"),
                               ("MN", "Mongolia"),
                               ("MA", "Morocco"),
                               ("NP", "Nepal"),
                               ("NL", "Netherlands"),
                               ("NZ", "New Zealand"),
                               ("NI", "Nicaragua"),
                               ("NO", "Norway"),
                               ("PK", "Pakistan"),
                               ("PH", "Philippines"),
                               ("PL", "Poland"),
                               ("PT", "Portugal"),
                               ("PR", "Puerto Rico"),
                               ("RO", "Romania"),
                               ("RU", "Russia"),
                               ("SA", "Saudi Arabia"),
                               ("RS", "Serbia"),
                               ("SG", "Singapore"),
                               ("SK", "Slovakia"),
                               ("ES", "Spain"),
                               ("LK", "Sri Lanka"),
                               ("SE", "Sweden"),
                               ("CH", "Switzerland"),
                               ("TH", "Thailand"),
                               ("TN", "Tunisia"),
                               ("TR", "Turkey"),
                               ("UA", "Ukraine"),
                               ("AE", "United Arab Emirates"),
                               ("GB", "United Kingdom"),
                               ("US", "United States"),
                               ("UY", "Uruguay"),
                               ("VE", "Venezuela"),
                               ("YE", "Yemen");

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
(10, 'Emma', 'Tall', 'female', 'EN', 'GB'),
(11, 'Jeffy', 'Kake', 'male', 'EN', 'GB'),
(12, 'Isaac', 'Walterson', 'male', 'EN', 'GB'),
(13, 'Bonny', 'Griffin', 'female', 'EN', 'GB'),
(14, 'Bill', 'NoGates', 'male', 'EN', 'GB'),
(15, 'Rebecca', 'Village', 'female', 'EN', 'GB'),
(16, 'Jacky', 'Kingsley', 'female', 'EN', 'GB');

CREATE TABLE IF NOT EXISTS sep_jobs_list (
    job_code int(9) not null unique primary key,
    job_name varchar(64) not null
);

INSERT INTO sep_jobs_list VALUES (1, 'Software Engineer'),
                                 (2, 'Marketing'),
                                 (3, 'Designer'),
                                 (4, 'Artificial Intelligence'),
                                 (5, 'Web Development'),
                                 (6, 'Games Development'),
                                 (7, 'Tutor'),
                                 (8, 'Freelance Writer'),
                                 (9, 'Transcriptionist'),
                                 (10, 'Social Media Manager'),
                                 (11, 'Bookkeeper'),
                                 (12, 'Advisor'),
                                 (13, 'Virtual Assistant'),
                                 (14, 'Wordpress'),
                                 (15, 'Data Entry'),
                                 (16, 'Proof Reader'),
                                 (17, 'Reviewer'),
                                 (18, 'Influencer'),
                                 (19, 'Ad Specialist');

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

CREATE TABLE IF NOT EXISTS sep_available_jobs (
    job_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    job_title TEXT NOT NULL,
    job_desc TEXT NOT NULL,
    job_price FLOAT NOT NULL,
    job_availability BOOL NOT NULL,
    job_date DATE,
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id)
);

INSERT INTO sep_available_jobs (user_id, job_title, job_desc, job_price, job_availability, job_date) VALUES
(10, 'Android Developer', 'I am looking for an android developer to make the next billion dollar app please', 19.99, TRUE, '2021-04-21'),
(11, 'Apple Developer', 'I am looking for an apple developer to make the next billion dollar app please', 14.99, TRUE, '2021-04-20'),
(12, 'Marketer', 'I am looking for a top quality marketer to get my product all around the world!', 30.00, TRUE, '2021-04-19'),
(13, 'Software Engineer', 'I am looking for a software developer who can do complicated algorithms for me', 10.05, TRUE, '2021-04-10'),
(14, 'Designer', 'I am looking for a designer to design my website interface', 15.00, TRUE, '2021-04-04'),
(15, 'Personal Assistant', 'I am looking for a PA to do the stuff i am too busy to do...', 5.00, TRUE, '2021-03-01'),
(16, 'Web Developer', 'I am looking for a World Class Web Developer!', 25.49, TRUE, '2021-04-020');


CREATE TABLE IF NOT EXISTS sep_job_rating (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    job_rating INT(1) NOT NULL,
    FOREIGN KEY (job_id) REFERENCES sep_available_jobs(job_id),
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id)
);

INSERT INTO sep_job_rating (job_id, user_id, job_rating) VALUES (1, 11, 5),
                                                                (1, 12, 5),
                                                                (1, 13, 5),
                                                                (1, 14, 5);

INSERT INTO sep_job_rating (job_id, user_id, job_rating) VALUES (2, 10, 4),
                                                                (2, 12, 3),
                                                                (2, 13, 5),
                                                                (2, 14, 2);

INSERT INTO sep_job_rating (job_id, user_id, job_rating) VALUES (3, 11, 4),
                                                                (3, 12, 4),
                                                                (3, 13, 2),
                                                                (3, 14, 5);

INSERT INTO sep_job_rating (job_id, user_id, job_rating) VALUES (4, 11, 5),
                                                                (4, 14, 4);

INSERT INTO sep_job_rating (job_id, user_id, job_rating) VALUES (5, 11, 5),
                                                                (5, 13, 3),
                                                                (5, 15, 3);

INSERT INTO sep_job_rating (job_id, user_id, job_rating) VALUES (6, 11, 5);

INSERT INTO sep_job_rating (job_id, user_id, job_rating) VALUES (7, 11, 5),
                                                                (7, 12, 5),
                                                                (7, 13, 5),
                                                                (7, 14, 5);

