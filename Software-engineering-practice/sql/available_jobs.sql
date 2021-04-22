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

