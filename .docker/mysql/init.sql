-- Load environment variables from the .env file
source /var/lib/mysql/.env;

-- Create the database using the DB_DATABASE variable
--CREATE DATABASE IF NOT EXISTS '${DB_DATABASE}';

-- Create a user and grant privileges using the DB_USER and DB_PASS variables
CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASS}';
--GRANT ALL PRIVILEGES ON '${DB_DATABASE}'.* TO '${DB_USER}'@'%';

-- Flush privileges to apply changes
FLUSH PRIVILEGES;
