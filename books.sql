USE isp;

DROP TABLE IF EXISTS Books;

CREATE TABLE Books (
  Book_id INT(11) NOT NULL AUTO_INCREMENT,
  Title CHAR(50) NOT NULL,
  Price FLOAT NOT NULL,
  Quantity INT(3) NOT NULL,
  Flag BOOL,
  PRIMARY KEY (Book_id)
);

insert into Books values
(1, 'Harry Potter', 19.99, 10, 1),
(2, 'Data Structures', 129.99, 20, 0),
(3, 'Time Traveling', 99.99, 5, 1),
(4, 'String Theory', 59.99, 10, 0),
(5, 'Breaking Bad', 29.99, 40, 1),
(6, 'NFL Record and Fact Book', 15.99, 5, 0),
(7, 'Coding for Beginners', 39.99, 10, 0);