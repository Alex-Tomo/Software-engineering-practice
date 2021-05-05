

CREATE TABLE IF NOT EXISTS sep_messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    target_user_id INT NOT NULL,
    message VARCHAR(1000) NOT NULL,
    created_on DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES sep_users(user_id),
    FOREIGN KEY (target_user_id) REFERENCES sep_users(user_id)
);
