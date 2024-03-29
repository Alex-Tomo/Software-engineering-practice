# this table lists all the jobs which have been posted

# user id is the person whom posted the job
# job title is the name of the job
# job description is the description of the job
# job price is the hourly rate given to the job
# job availability is a TRUE or FALSE value stating whether the job is available or not
# job date is the date the job was posted
# job image is the name of the corresponding image

CREATE TABLE IF NOT EXISTS sep_available_jobs (
    job_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    job_title TEXT NOT NULL,
    job_desc TEXT NOT NULL,
    job_price FLOAT NOT NULL,
    job_availability BOOL NOT NULL,
    job_date DATE,
    job_image TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id)
);

INSERT INTO sep_available_jobs (user_id, job_title, job_desc, job_price, job_availability, job_date, job_image) VALUES
(1, 'Android Developer', 'I am looking for an android developer to make the next billion dollar app please', 19.99, TRUE, '2021-04-21', 'image_1.jpg'),
(1, 'Apple Developer', 'I am looking for an apple developer to make the next billion dollar app please', 14.99, TRUE, '2021-04-20', 'image_2.jpg'),
(2,'Marketer', 'I am looking for a top quality marketer to get my product all around the world!', 30.00, TRUE, '2021-04-19', 'image_3.jpg'),
(3, 'Software Developer', 'I am looking for a software developer who can do complicated algorithms for me', 10.05, TRUE, '2021-04-10', 'image_4.jpg'),
(4, 'Website Interface Designer', 'I am looking for a designer to design my website interface', 15.00, TRUE, '2021-04-04', 'image_5.jpg'),
(5, 'Personal Assistant', 'I am looking for a PA to do the stuff i am too busy to do...', 5.00, TRUE, '2021-03-01', 'image_6.jpg'),
(6, 'C Developer', 'I am looking for a C developer', 99.99, TRUE, '2021-03-02', 'image_7.jpg'),
(6, 'Website Designer', 'I am looking for a website designer', 99.99, TRUE, '2021-03-02', 'image_8.jpg'),
(7, 'PHP Developer', 'I am looking for a PHP developer', 14.95, TRUE, '2021-03-02', 'image_9.jpg'),
(8, 'Cloud Engineer', 'I am looking for a cloud engineer', 19.99, TRUE, '2021-03-02', 'image_10.jpg'),
(8, 'Machine Learning Expert', 'I am looking for a machine learning expert', 4.99, TRUE, '2021-03-02', 'image_11.jpg'),
(8, 'Hacker Wanted!', 'I am looking for a hacker.. for a friend..', 14.95, TRUE, '2021-03-02', 'image_12.jpg'),
(9, 'Games Developer', 'I am looking for a games developer', 30.00, TRUE, '2021-03-02', 'image_13.jpg'),
(10, 'User Experience', 'I am looking for a UX developer', 30.00, TRUE, '2021-03-02', 'image_14.jpg'),
(11, 'Digital Marketing', 'I am looking for a digital marketer', 19.99, TRUE, '2021-01-14', 'image_15.jpg'),
(12, 'Campaign Marketer', 'I am looking for a campaign marketer', 15.00, TRUE, '2021-03-02', 'image_16.jpg'),
(13, 'Accountant Wanted', 'I am looking for an accountant to help with ny finances!', 25.00, TRUE, '2021-02-04', 'image_17.jpg'),
(14, 'Product Designer', 'I am looking for a product designer', 14.95, TRUE, '2021-02-10', 'image_18.jpg'),
(15, 'Virtual Assistant', 'I am looking for a virtual assistant', 15.00, TRUE, '2021-03-02', 'image_19.jpg'),
(15, 'Wordpress Expert', 'I am looking for a wordpress expert', 25.00, TRUE, '2021-04-019', 'image_20.jpg'),
(16, 'Cybersecurity Needed', 'I am looking for a cybersecurity', 19.99, TRUE, '2021-03-17', 'image_21.jpg'),
(17, 'DevOps Engineer', 'I am looking for a DevOps engineer', 20.00, TRUE, '2021-03-02', 'image_22.jpg'),
(18, 'Cloud Engineer', 'I am looking for a cloud engineer', 30.00, TRUE, '2021-03-11', 'image_23.jpg'),
(19, 'Data Scientist', 'I am looking for a data scientist', 14.95, TRUE, '2021-04-12', 'image_24.jpg'),
(19, 'Marketer Wanted', 'I am looking for a marketer', 14.95, TRUE, '2021-03-02', 'image_25.jpg'),
(20, 'Designer', 'I am looking for a visual designer', 13.95, TRUE, '2021-04-13', 'image_26.jpg'),
(21, 'Database Engineer', 'I am looking for a database engineer', 30.00, TRUE, '2021-03-02', 'image_27.jpg'),
(21, 'Java Developer', 'I am looking for a java developer', 19.99, TRUE, '2021-02-02', 'image_28.jpg'),
(21, 'Data Scientist', 'I am looking for a data scientist', 25.00, TRUE, '2021-01-02', 'image_29.jpg'),
(22, 'C Developer', 'I am looking for a c developer', 14.95, TRUE, '2021-03-02', 'image_30.jpg'),
(23, 'C++ Developer', 'I am looking for a c++ developer', 20.00, TRUE, '2021-03-02', 'image_31.jpg'),
(24, 'Book Keeper', 'I am looking for a bookkeeper', 15.00, TRUE, '2021-03-02', 'image_32.jpg'),
(24, 'Accountant', 'I am looking for an accountant', 14.95, TRUE, '2021-03-02', 'image_33.jpg'),
(24, 'Finance Analyst', 'I am looking for a finance analyst', 20.00, TRUE, '2021-04-21', 'image_34.jpg'),
(25, 'Web Developer', 'I am looking for a web developer', 19.99, TRUE, '2021-04-03', 'image_35.jpg'),
(25, 'Games Developer', 'I am looking for a games developer', 25.00, TRUE, '2021-04-02', 'image_36.jpg'),
(26, 'Java Developer', 'I am looking for a java developer', 19.99, TRUE, '2021-04-02', 'image_37.jpg'),
(27, 'Developer Wanted', 'I am looking for a developer', 15.00, TRUE, '2021-03-12', 'image_38.jpg'),
(28, 'Designer Wanted', 'I am looking for a designer', 19.99, TRUE, '2021-03-23', 'image_39.jpg'),
(29, 'Web Designer Wanted', 'I am looking for a web designer', 25.00, TRUE, '2021-03-12', 'image_40.jpg'),
(30, 'Games Designer', 'I am looking for a games designer', 20.00, TRUE, '2021-03-22', 'image_41.jpg'),
(31, 'Cloud Developer', 'I am looking for a cloud developer!', 41.50, TRUE, '2021-03-21', 'image_42.jpg'),
(32, 'Web Designer', 'Looking for a designer to help with my website', 18.73, TRUE, '2021-03-02', 'image_43.jpg'),
(33, 'World Domination, Help Wanted!', 'Looking for some vault hunters to help me take over the world...', 199.99, TRUE, '2021-04-25', 'image_44.jpg');

