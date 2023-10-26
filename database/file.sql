CREATE SCHEMA IF NOT EXISTS tech_crafted;
SET search_path TO tech_crafted;

DROP TABLE IF EXISTS Vote CASCADE;
DROP TABLE IF EXISTS Comment CASCADE;
DROP TABLE IF EXISTS Ticket CASCADE;
DROP TABLE IF EXISTS Discussion CASCADE;
DROP TABLE IF EXISTS EventOrganizer CASCADE;
DROP TABLE IF EXISTS Admin CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS University CASCADE;
DROP TABLE IF EXISTS Event CASCADE;
DROP TABLE IF EXISTS City CASCADE;
DROP TABLE IF EXISTS State CASCADE;
DROP TABLE IF EXISTS Country CASCADE;
DROP TABLE IF EXISTS Category CASCADE;


-------------------------
-- TABLES ---------------
-------------------------

CREATE TABLE Category
(
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL
);

CREATE TABLE Country
(
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  initials CHAR(3),
  UNIQUE (initials)
);

CREATE TABLE State
(
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  initials CHAR(3),
  country_id INT NOT NULL,
  UNIQUE (initials),
  FOREIGN KEY (country_id) REFERENCES Country(id)
);

CREATE TABLE City
(
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  state_id INT NOT NULL,
  FOREIGN KEY (state_id) REFERENCES State(id)
);

CREATE TABLE University
(
  id SERIAL PRIMARY KEY,
  address VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  city_id INT NOT NULL,
  FOREIGN KEY (city_id) REFERENCES City(id)
);

CREATE TABLE Users
(
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone CHAR(15) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  birthDate DATE NOT NULL,
  university_id INT NOT NULL,
  FOREIGN KEY (university_id) REFERENCES University(id),
  isBanned BOOLEAN DEFAULT FALSE,
  isDeleted BOOLEAN DEFAULT FALSE
);

CREATE TABLE EventOrganizer
(
  id SERIAL PRIMARY KEY,
  legalId CHAR(50) NOT NULL,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id)
);


CREATE TABLE Event
(
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  startDate DATE NOT NULL,
  endDate DATE NOT NULL,
  startTicketsQty INT NOT NULL,
  currentTicketsQty INT NOT NULL,
  currentPrice DECIMAL(10, 2) NOT NULL,
  address VARCHAR(255) NOT NULL,
  category_id INT NOT NULL,
  city_id INT NOT NULL,
  ownerId INT NOT NULL,
  FOREIGN KEY (ownerId) REFERENCES EventOrganizer(id),
  FOREIGN KEY (category_id) REFERENCES Category(id),
  FOREIGN KEY (city_id) REFERENCES City(id)
);

CREATE TYPE NotificationType AS ENUM ('INVITE', 'REMINDER', 'REPORT');

CREATE TABLE Notification
(
  id SERIAL PRIMARY KEY,
  text TEXT NOT NULL,
  expiresAt DATE NOT NULL,
  notificationType NotificationType NOT NULL,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE Admin
(
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE Discussion
(
  id SERIAL PRIMARY KEY,
  event_id INT NOT NULL,
  FOREIGN KEY (event_id) REFERENCES Event(id)
);

CREATE TABLE Ticket
(
  id SERIAL PRIMARY KEY,
  pricePaid DECIMAL(10,2) NOT NULL,
  event_id INT NOT NULL,
  user_id INT NOT NULL,
  FOREIGN KEY (event_id) REFERENCES Event(id),
  FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE Comment
(
  id SERIAL PRIMARY KEY,
  text TEXT NOT NULL,
  commentedAt DATE NOT NULL,
  user_id INT NOT NULL,
  discussion_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id),
  FOREIGN KEY (discussion_id) REFERENCES Discussion(id)
);


CREATE TABLE Vote
(
  id SERIAL PRIMARY KEY,
  voteType smallint NOT NULL, -- 1 for upvote, -1 for downvote
  votedAt TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
  user_id INT NOT NULL,
  comment_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id),
  FOREIGN KEY (comment_id) REFERENCES Comment(id)
);