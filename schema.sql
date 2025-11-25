-- Schema and sample data for the "exam_library" database used in the PHP demo.
CREATE DATABASE IF NOT EXISTS exam_library CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE exam_library;

DROP TABLE IF EXISTS books;
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    author VARCHAR(120) NOT NULL,
    genre VARCHAR(80) NOT NULL,
    published_year INT CHECK (published_year >= 0),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO books (title, author, genre, published_year, notes) VALUES
('Clean Code', 'Robert C. Martin', 'Software Engineering', 2008, 'Best practices for writing maintainable code.'),
('The Pragmatic Programmer', 'Andrew Hunt', 'Software Engineering', 1999, 'Classic book on pragmatic software development.'),
('Design Patterns', 'Erich Gamma', 'Architecture', 1994, 'The GoF design patterns collection.'),
('Refactoring', 'Martin Fowler', 'Software Engineering', 1999, 'Improving the design of existing code.'),
('Database System Concepts', 'Abraham Silberschatz', 'Databases', 2010, 'University-level DBMS overview.');
