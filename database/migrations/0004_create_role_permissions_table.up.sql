CREATE TABLE role_permissions (
   id INT AUTO_INCREMENT PRIMARY KEY,
   role_id INT NOT NULL,
   permission_id INT NOT NULL,
   created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

   CONSTRAINT fk_role_permissions_role
       FOREIGN KEY (role_id) REFERENCES roles(id)
           ON DELETE CASCADE,

   CONSTRAINT fk_role_permissions_permission
       FOREIGN KEY (permission_id) REFERENCES permissions(id)
           ON DELETE CASCADE,

   CONSTRAINT uq_role_permission
       UNIQUE (role_id, permission_id)
);