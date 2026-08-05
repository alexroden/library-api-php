CREATE TABLE book_categories (
   id INT AUTO_INCREMENT PRIMARY KEY,
   book_id INT NOT NULL,
   category_id INT NOT NULL,
   created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

   CONSTRAINT fk_book_categories_book
       FOREIGN KEY (book_id) REFERENCES books(id)
           ON DELETE CASCADE,

   CONSTRAINT fk_book_categories_category
       FOREIGN KEY (category_id) REFERENCES categories(id)
           ON DELETE CASCADE,

   CONSTRAINT uq_book_categories
       UNIQUE (book_id, category_id)
);