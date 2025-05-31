-- Reset root password and create database
ALTER USER 'root'@'localhost' IDENTIFIED BY 'sozial_root';
CREATE DATABASE IF NOT EXISTS sozial;
CREATE USER IF NOT EXISTS 'sozial_user'@'localhost' IDENTIFIED BY 'sozial_pass';
GRANT ALL PRIVILEGES ON sozial.* TO 'sozial_user'@'localhost';
FLUSH PRIVILEGES;
