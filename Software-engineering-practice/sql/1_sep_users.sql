# Auto generates a user id, and stores the user email and
# a hashed corresponding password

CREATE TABLE IF NOT EXISTS sep_users (
    user_id int not null auto_increment unique primary key,
    user_email varchar(128) not null unique,
    user_password varchar(128) not null,
    user_online boolean not null default false
);

-- $2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG == password
INSERT INTO sep_users (user_id, user_email, user_password) VALUES 
(1, 'emma@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(2, 'jeffy@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(3, 'isaac@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(4, 'bonny@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(5, 'bill@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(6, 'rebecca@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(7, 'jacky@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(8, 'jonathan@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(9, 'josephine@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(10, 'chan@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(11, 'angel@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(12, 'arnold@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(13, 'kate@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(14, 'wayne@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(15, 'toleen@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(16, 'kong@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(17, 'diana@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(18, 'ben@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(19, 'sophie@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(20, 'brad@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(21, 'mila@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(22, 'peter@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(23, 'mary@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(24, 'harry@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(25, 'maggie@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(26, 'harold@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(27, 'margot@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(28, 'robert@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(29, 'amy@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(30, 'john@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(31, 'jane@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(32, 'rick_p@fakeemail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG'),
(33, 'handsomeJack@hereMail.com', '$2y$10$4XsPbKCgqTNKMg0EIRlt4efWmOjx9zLLw8G6jRgJBcjlRHzLVP3TG');
