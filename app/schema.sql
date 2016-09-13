CREATE TABLE users(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  phone CHAR(20),
  email CHAR(255),
  password CHAR(255),
  key TEXT,
  crt TEXT,
  mobikassa_id char(10)
);

CREATE TABLE accounts(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user INTEGER,
  account CHAR(255),
  name CHAR(255)
);

CREATE TABLE payments(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  account INTEGER,
  sum FLOAT,
  date DATE
);

CREATE TABLE addresses(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  account INTEGER,
  name CHAR(255),
  city CHAR(255),
  street CHAR(255),
  number CHAR(5),
  option CHAR(5),
  notify INTEGER
);
