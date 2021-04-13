-- Create a table for users include 
-- id - primary key, unique, auto_increment, int, not null
-- email - varchar, unique, not null
-- password - varchar, not null,
-- date/time? - current date/time if included

CREATE TABLE IF NOT EXISTS users (
    user_id int not null auto_increment unique primary key,
    user_email varchar(128) not null unique,
    user_password varchar(128) not null 
);
