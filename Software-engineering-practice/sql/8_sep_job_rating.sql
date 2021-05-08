# creates a rating between 0 and 5 for each job

# job id is the job being rated
# user id is the person rating the job
# job rating is the rating the user gave to the job

CREATE TABLE IF NOT EXISTS sep_job_rating (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    job_rating INT(1) NOT NULL,
    FOREIGN KEY (job_id) REFERENCES sep_available_jobs(job_id),
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id)
);

INSERT INTO sep_job_rating (job_id, user_id, job_rating) VALUES
(1, 2, 5), (1, 3, 5), (1, 4, 5), (1, 5, 5),
(2, 1, 4), (2, 3, 3), (2, 4, 5), (2, 5, 2),
(3, 2, 4), (3, 4, 4), (3, 5, 2), (3, 6, 5),
(4, 1, 5), (4, 3, 4),
(5, 1, 5), (5, 2, 3), (5, 3, 3),
(6, 4, 5),
(7, 4, 5), (7, 2, 5), (7, 1, 5), (7, 6, 5),
(8, 7, 1), (8, 12, 1), (8, 31, 3), (8, 17, 5), (8, 1, 4), (8, 18, 5), (8, 19, 3), (8, 24, 2), (8, 23, 1),
(9, 8, 1), (9, 21, 4), (9, 18, 4), (9, 1, 5), (9, 2, 2), (9, 14, 3),
(10, 9, 2), (10, 12, 3), (10, 9, 3), (10, 17, 1), (10, 12, 1),
(11, 10, 3), (11, 21, 4), (11, 31, 4), (11, 4, 5), (11, 13, 5), (11, 19, 4),
(12, 21, 4), (12, 12, 5), (12, 9, 5), (12, 11, 5), (12, 18, 5), (12, 20, 5), (12, 8, 5), (12, 32, 5), (12, 30, 4), (12, 17, 4), (12, 28, 5), (12, 8, 5),
(13, 30, 5), (13, 21, 3), (13, 12, 4), (13, 17, 3), (13, 27, 4), (13, 27, 4), (13, 29, 5), (13, 32, 4),
(14, 31, 2), (14, 12, 3), (14, 31, 2),
(15, 21, 3), (15, 2, 3), (15, 6, 3), (15, 8, 4), (15, 11, 1),
(16, 1, 4), (16, 2, 5),
(17, 2, 3),
(18, 3, 3), (18, 19, 4), (18, 23, 5), (18, 29, 5), (18, 1, 4), (18, 28, 4), (18, 14, 5),
(19, 4, 4), (19, 10, 4),
(20, 5, 3), (20, 18, 3), (20, 11, 4),
(21, 6, 2),
(22, 7, 1),
(23, 8, 2),
(24, 9, 3),
(25, 10, 4), (25, 21, 4), (25, 30, 5),
(26, 1, 5), (26, 20, 4),
(27, 2, 3), (27, 1, 4), (27, 9, 3), (27, 19, 4),
(28, 3, 3),
(29, 4, 2),
(30, 5, 4), (30, 1, 2),
(31, 6, 5),
(32, 7, 4), (32, 10, 4), (32, 4, 5), (32, 9, 5), (32, 14, 3),
(33, 8, 3),
(34, 9, 3), (34, 16, 4), (34, 19, 2),
(35, 1, 4), (35, 14, 3),
(36, 2, 4), (36, 12, 4), (36, 20, 5), (36, 5, 4),
(37, 3, 3),
(38, 4, 2), (38, 23, 2), (38, 31, 3),
(39, 5,1 ), (39, 21, 1),
(40, 6, 2),
(41, 7, 3), (41, 12, 3), (41, 15, 4), (41, 20, 2), (41, 18, 4),
(42, 8, 1), (42, 11, 4),
(43, 9, 4),
(44, 1, 5), (44, 2, 3), (44, 3, 3);
