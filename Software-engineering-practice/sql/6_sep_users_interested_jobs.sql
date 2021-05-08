-- lists the users interested categories

-- user id is the id of the user
-- job code is the categories the user is interested in (minimum of 3)

CREATE TABLE IF NOT EXISTS sep_users_interested_jobs (
    user_id int not null,
    job_code int(9) not null,
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id),
    FOREIGN KEY (job_code) REFERENCES sep_jobs_list(job_code)
);

INSERT INTO sep_users_interested_jobs VALUES
(1, 45), (1 ,46), (1 ,49), (1 ,50), (1 ,51), -- marketing
(2, 1), (2, 3), (2, 5), (2, 6), (2, 7), (2, 8), -- developer
(3, 1), (3, 3), (3, 5), (3, 12), (3, 14), (3, 13), -- developer
(4, 59), (4, 21), (4, 68), (4, 64), (4, 63),  -- misc
(5, 36), (5, 39), (5, 41), -- designer
(6, 1), (6, 14), (6, 20), (6, 21), (6, 13), -- developer
(7, 36), (7, 37), (7, 38), (7, 42), -- designer
(8, 23), (8, 24), (8, 1), (8, 18), (8, 13), (8, 15), -- developer
(9, 45), (9, 47), (9, 49), (9, 50), (9, 51), -- marketing
(10, 46), (10, 45), (10, 49), (10, 50), (10, 51), -- marketing
(11, 26), (11, 1), (11, 12), (11, 11), -- developer
(12, 27), (12, 1), (12, 13), (12, 10), -- developer
(13, 28), (13, 14), (13, 1), (13, 9), -- developer
(14, 1), (14, 3), (14, 5), (14, 7), (14, 8), -- developer
(15, 36), (15, 41), (15, 42), (15, 43), -- designer
(16, 45), (16, 46), (16, 49), -- marketing
(17, 1), (17, 6), (17, 11), (17, 6), -- developer
(18, 1), (18, 4), (18, 12), (18, 11), -- developer
(19, 1), (19, 4), (19, 10), (19, 9), -- developer
(20, 36), (20, 41), (20, 42), (20, 39), (20, 43), -- designer
(21, 59), (21, 21), (21, 68), (21, 65), (21, 63), -- misc
(22, 26), (22, 3), (22, 1), (22, 17), -- developer
(23, 25), (23, 21), (23, 6), (23, 1), -- developer
(24, 24), (24, 68), (24, 9), (24, 1), -- developer
(25, 38), (25, 43), (25, 41), (25, 36), -- designer
(26, 36), (26, 40), (26, 43), (26, 42), -- designer
(27, 1), (27, 9), (27, 19), (27, 21), (27, 5), (27, 68), -- developer
(28, 60), (28, 63), (28, 68), -- misc
(29, 45), (29, 46), (29, 48), (29, 50), -- marketing
(30, 45), (30, 46), (30, 48), (30, 50), -- marketing
(31, 36), (31, 40), (31, 42), (31, 43), -- designer
(32, 1), (32, 16), (32, 19), (32, 11), (32, 68), -- developer
(33, 1), (33, 14), (33, 19), (33, 68); -- developer
