CREATE TABLE IF NOT EXISTS sep_notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    notification_message VARCHAR(1000) NOT NULL,
    notification_read BOOLEAN NOT NULL DEFAULT FALSE,
    sent_on DATETIME NOT NULL,
    FOREIGN KEY (job_id) REFERENCES sep_available_jobs(job_id),
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id)

);

# user_id is who sent the notification