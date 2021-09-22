DROP DATABASE voity_dev_users;
DROP DATABASE voity_dev_companies;
DROP DATABASE voity_dev_connections;
CREATE DATABASE voity_dev_users;
CREATE DATABASE voity_dev_companies;
CREATE DATABASE voity_dev_connections;
GRANT ALL PRIVILEGES ON voity_dev_users.* to voity@localhost;
GRANT ALL PRIVILEGES ON voity_dev_companies.* to voity@localhost;
GRANT ALL PRIVILEGES ON voity_dev_connections.* to voity@localhost;
