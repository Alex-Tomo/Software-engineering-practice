CREATE TABLE IF NOT EXISTS sep_read_messages (
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    message_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (job_id) REFERENCES sep_messages(job_id),
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id)
);