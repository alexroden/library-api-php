CREATE TABLE user_roles (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   role_id INT NOT NULL,
   created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

   CONSTRAINT fk_user_roles_user
       FOREIGN KEY (user_id) REFERENCES users(id)
           ON DELETE CASCADE,

   CONSTRAINT fk_user_roles_role
       FOREIGN KEY (role_id) REFERENCES roles(id)
           ON DELETE CASCADE,

   CONSTRAINT uq_user_role
       UNIQUE (user_id, role_id)
);
