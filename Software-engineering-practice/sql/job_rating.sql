CREATE TABLE IF NOT EXISTS sep_job_rating (
   rating_id INT AUTO_INCREMENT PRIMARY KEY,
   job_id INT NOT NULL,
   user_id INT NOT NULL,
   job_rating INT(1) NOT NULL,
   FOREIGN KEY (job_id) REFERENCES sep_available_jobs(job_id),
   FOREIGN KEY (user_id) REFERENCES sep_users(user_id)
);

# 1(10, 'Android Developer', 'I am looking for an android developer to make the next billion dollar app please', 19.99, TRUE, '2021-04-21'),
# 2(11, 'Apple Developer', 'I am looking for an apple developer to make the next billion dollar app please', 14.99, TRUE, '2021-04-20'),
# 3(12, 'Marketer', 'I am looking for a top quality marketer to get my product all around the world!', 30.00, TRUE, '2021-04-19'),
# 4(13, 'Software Engineer', 'I am looking for a software developer who can do complicated algorithms for me', 10.05, TRUE, '2021-04-10'),
# 5(14, 'Designer', 'I am looking for a designer to design my website interface', 15.00, TRUE, '2021-04-04'),
# 6(15, 'Personal Assistant', 'I am looking for a PA to do the stuff i am too busy to do...', 5.00, TRUE, '2021-03-01');

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
