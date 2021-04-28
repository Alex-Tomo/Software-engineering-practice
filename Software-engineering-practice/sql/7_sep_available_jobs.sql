CREATE TABLE IF NOT EXISTS sep_available_jobs (
    job_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    job_code INT NOT NULL,
    job_title TEXT NOT NULL,
    job_desc TEXT NOT NULL,
    job_price FLOAT NOT NULL,
    job_availability BOOL NOT NULL,
    job_date DATE,
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id),
    FOREIGN KEY (job_code) REFERENCES sep_jobs_list(job_code)
);

INSERT INTO sep_available_jobs (user_id, job_code, job_title,job_desc, job_price, job_availability, job_date) VALUES
(1, 6, 'Android Developer', 'I am looking for an android developer to make the next billion dollar app please', 19.99, TRUE, '2021-04-21'),
(1, 15, 'Apple Developer', 'I am looking for an apple developer to make the next billion dollar app please', 14.99, TRUE, '2021-04-20'),
(2, 45, 'Marketer', 'I am looking for a top quality marketer to get my product all around the world!', 30.00, TRUE, '2021-04-19'),
(3, 4, 'Software Developer', 'I am looking for a software developer who can do complicated algorithms for me', 10.05, TRUE, '2021-04-10'),
(4, 36, 'Website Interface Designer', 'I am looking for a designer to design my website interface', 15.00, TRUE, '2021-04-04'),
(5, 63, 'Personal Assistant', 'I am looking for a PA to do the stuff i am too busy to do...', 5.00, TRUE, '2021-03-01'),
(6, 9, 'C Developer', 'I am looking for a C developer', 99.99, TRUE, '2021-03-02'),
(6, 5, 'Website Designer', 'I am looking for a website designer', 99.99, TRUE, '2021-03-02'),
(7, 12, 'PHP Developer', 'I am looking for a PHP developer', 14.95, TRUE, '2021-03-02'),
(8, 14, 'Cloud Engineer', 'I am looking for a cloud engineer', 19.99, TRUE, '2021-03-02'),
(8, 21, 'Machine Learning Expert', 'I am looking for a machine learning expert', 4.99, TRUE, '2021-03-02'),
(8, 28, 'Hacker Wanted!', 'I am looking for a hacker.. for a friend..', 14.95, TRUE, '2021-03-02'),
(9, 23, 'Games Developer', 'I am looking for a games developer', 30.00, TRUE, '2021-03-02'),
(10, 29, 'User Experience', 'I am looking for a UX developer', 30.00, TRUE, '2021-03-02'),
(11, 46, 'Digital Marketing', 'I am looking for a digital marketer', 19.99, TRUE, '2021-01-14'),
(12, 51, 'Campaign Marketer', 'I am looking for a campaign marketer', 15.00, TRUE, '2021-03-02'),
(13, 60, 'Accountant Wanted', 'I am looking for an accountant to help with ny finances!', 25.00, TRUE, '2021-02-04'),
(14, 38, 'Product Designer', 'I am looking for a product designer', 14.95, TRUE, '2021-02-10'),
(15, 63, 'Virtual Assistant', 'I am looking for a virtual assistant', 15.00, TRUE, '2021-03-02'),
(15, 35, 'Wordpress Expert', 'I am looking for a wordpress expert', 25.00, TRUE, '2021-04-019'),
(16, 34, 'Cybersecurity Needed', 'I am looking for a cybersecurity', 19.99, TRUE, '2021-03-17'),
(17, 33, 'DevOps Engineer', 'I am looking for a DevOps engineer', 20.00, TRUE, '2021-03-02'),
(18, 14, 'Cloud Engineer', 'I am looking for a cloud engineer', 30.00, TRUE, '2021-03-11'),
(19, 30, 'Data Scientist', 'I am looking for a data scientist', 14.95, TRUE, '2021-04-12'),
(19, 45, 'Marketer Wanted', 'I am looking for a marketer', 14.95, TRUE, '2021-03-02'),
(20, 41, 'Designer', 'I am looking for a visual designer', 13.95, TRUE, '2021-04-13'),
(21, 13, 'Database Engineer', 'I am looking for a database engineer', 30.00, TRUE, '2021-03-02'),
(21, 3, 'Java Developer', 'I am looking for a java developer', 19.99, TRUE, '2021-02-02'),
(21, 30, 'Data Scientist', 'I am looking for a data scientist', 25.00, TRUE, '2021-01-02'),
(22, 9, 'C Developer', 'I am looking for a c developer', 14.95, TRUE, '2021-03-02'),
(23, 10, 'C++ Developer', 'I am looking for a c++ developer', 20.00, TRUE, '2021-03-02'),
(24, 58, 'Book Keeper', 'I am looking for a bookkeeper', 15.00, TRUE, '2021-03-02'),
(24, 60, 'Accountant', 'I am looking for an accountant', 14.95, TRUE, '2021-03-02'),
(24, 61, 'Finance Analyst', 'I am looking for a finance analyst', 20.00, TRUE, '2021-04-21'),
(25, 5, 'Web Developer', 'I am looking for a web developer', 19.99, TRUE, '2021-04-03'),
(25, 23, 'Games Developer', 'I am looking for a games developer', 25.00, TRUE, '2021-04-02'),
(26, 3, 'Java Developer', 'I am looking for a java developer', 19.99, TRUE, '2021-04-02'),
(27, 1, 'Developer Wanted', 'I am looking for a developer', 15.00, TRUE, '2021-03-12'),
(28, 36, 'Designer Wanted', 'I am looking for a designer', 19.99, TRUE, '2021-03-23'),
(29, 42, 'Web Designer Wanted', 'I am looking for a web designer', 25.00, TRUE, '2021-03-12'),
(30, 43, 'Games Designer', 'I am looking for a games designer', 20.00, TRUE, '2021-03-22'),
(31, 31, 'Cloud Developer', 'I am looking for a cloud developer!', 41.50, TRUE, '2021-03-21'),
(32, 36, 'Web Designer', 'Looking for a designer to help with my website', 18.73, TRUE, '2021-03-02'),
(33, 68, 'World Domination, Help Wanted!', 'Looking for some vault hunters to help me take over the world...', 199.99, TRUE, '2021-04-25');

