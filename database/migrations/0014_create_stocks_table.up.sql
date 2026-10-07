CREATE TABLE stocks (
   id INT AUTO_INCREMENT PRIMARY KEY,
   library_id INT NOT NULL,
   book_id INT NOT NULL,
   quantity INT NOT NULL DEFAULT 0,
   created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

   CONSTRAINT fk_stocks_library
       FOREIGN KEY (library_id) REFERENCES libraries(id)
           ON DELETE CASCADE,

   CONSTRAINT fk_stocks_book
       FOREIGN KEY (book_id) REFERENCES books(id)
           ON DELETE CASCADE,

   CONSTRAINT uq_stocks_library_book
       UNIQUE (library_id, book_id)
);
