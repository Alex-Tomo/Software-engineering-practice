CREATE TABLE IF NOT EXISTS sep_users (
    user_id int not null auto_increment unique primary key,
    user_email varchar(128) not null unique,
    user_password varchar(128) not null 
);

DELETE FROM sep_users WHERE user_email ='user';
INSERT INTO sep_users (user_email, user_password) VALUES ('user', 'password');

CREATE TABLE IF NOT EXISTS sep_languages (
    language_code varchar(2) not null unique primary key,
    language_name varchar(32) not null unique
);

INSERT INTO sep_languages VALUES ('en', 'English'),
                             ('fr', 'French'),
                             ('de', 'German'),
                             ('da', 'Danish'),
                             ('es', 'Spanish'),
                             ('it', 'Italian'),
                             ('fi', 'Finnish'),
                             ('no', 'Norwegian'),
                             ('sv', 'Swedish'),
                             ('zh', 'Chinese'),
                             ('ja', 'Japanese'),
                             ('ar', 'Arabic'),
                             ('el', 'Greek'),
                             ('lt', 'Lithuanian'),
                             ('pl', 'Polish'),
                             ('ru', 'Russian');

CREATE TABLE IF NOT EXISTS sep_regions (
    region_code varchar(16) not null unique primary key,
    region_name varchar(32) not null unique
);

INSERT INTO sep_regions VALUES ('uk', 'United Kingdom'),
                           ('france', 'France'),
                           ('germany', 'Germany'),
                           ('denmark', 'Denmark'),
                           ('spain', 'Spain'),
                           ('italy', 'Italy'),
                           ('finland', 'Finland'),
                           ('norway', 'Norway'),
                           ('sweden', 'Sweden'),
                           ('china', 'China'),
                           ('japan', 'Japan'),
                           ('uae', 'United Arab Emirates'),
                           ('usa', 'United States'),
                           ('greece', 'Greece'),
                           ('lithuania', 'Lithuania'),
                           ('poland', 'Poland'),
                           ('russia', 'Russia');

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

