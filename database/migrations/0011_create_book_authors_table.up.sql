CREATE TABLE book_authors (
   id INT AUTO_INCREMENT PRIMARY KEY,
   book_id INT NOT NULL,
   author_id INT NOT NULL,
   created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

   CONSTRAINT fk_book_authors_book
       FOREIGN KEY (book_id) REFERENCES books(id)
           ON DELETE CASCADE,

   CONSTRAINT book_authors_author
       FOREIGN KEY (author_id) REFERENCES authors(id)
           ON DELETE CASCADE,

   CONSTRAINT uq_book_authors
       UNIQUE (book_id, author_id)
);