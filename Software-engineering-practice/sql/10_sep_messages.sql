# The messages between each user in the chat webpage

# user id is the currently logged in user
# other user id is the person you are talking to

CREATE TABLE IF NOT EXISTS sep_messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    other_user_id INT NOT NULL,
    job_id INT NOT NULL,
    message VARCHAR(1000) NOT NULL,
    created_on DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id),
    FOREIGN KEY (other_user_id) REFERENCES sep_users(user_id),
    FOREIGN KEY (job_id) REFERENCES sep_available_jobs(job_id)
);
