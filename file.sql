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


------------------
-- TRIGGERS ------
------------------

CREATE OR REPLACE FUNCTION CreateDiscussionOnEventInsert() RETURNS TRIGGER AS $$
BEGIN
  -- insert new discussion vinculated to event
  INSERT INTO Discussion (event_id) VALUES (NEW.id);
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER CreateDiscussionOnEventInsert
AFTER INSERT ON Event
FOR EACH ROW EXECUTE PROCEDURE CreateDiscussionOnEventInsert();


------------------
-- INDEXES -------
------------------
-- Index on 'name' column in 'Category' table
CREATE INDEX idx_category_name ON Category USING btree (name);

-- Index on 'name' column in 'Country' table
CREATE INDEX idx_country_name ON Country USING btree (name);

-- Index on 'name' column in 'State' table
CREATE INDEX idx_state_name ON State USING btree (name);

-- Index on 'country_id' column in 'State' table
CREATE INDEX idx_state_country_id ON State USING btree (country_id);

-- Index on 'name' column in 'City' table
CREATE INDEX idx_city_name ON City USING btree (name);

-- Index on 'state_id' column in 'City' table
CREATE INDEX idx_city_state_id ON City USING btree (state_id);

-- Index on 'startDate', 'endDate', 'category_id', and 'city_id' columns in 'Event' table
CREATE INDEX idx_event_dates ON Event USING btree (startDate, endDate);
CREATE INDEX idx_event_category_id ON Event USING btree (category_id);
CREATE INDEX idx_event_city_id ON Event USING btree (city_id);

-- Index on 'city_id' column in 'University' table
CREATE INDEX idx_university_city_id ON University USING btree (city_id);

-- Index on 'university_id' column in 'Users' table
CREATE INDEX idx_users_university_id ON Users USING btree (university_id);

-- Index on 'user_id' column in 'Notification', 'Admin', and 'EventOrganizer' tables
CREATE INDEX idx_notification_user_id ON Notification USING btree (user_id);
CREATE INDEX idx_admin_user_id ON Admin USING btree (user_id);
CREATE INDEX idx_event_organizer_user_id ON EventOrganizer USING btree (user_id);

-- Index on 'event_id' column in 'Discussion', and 'Ticket' tables
CREATE INDEX idx_discussion_event_id ON Discussion USING btree (event_id);
CREATE INDEX idx_ticket_event_id ON Ticket USING btree (event_id);

-- Index on 'user_id', and 'discussion_id' columns in 'Comment' table
CREATE INDEX idx_comment_user_id ON Comment USING btree (user_id);
CREATE INDEX idx_comment_discussion_id ON Comment USING btree (discussion_id);

-- Index on 'user_id', and 'comment_id' columns in 'Vote' table
CREATE INDEX idx_vote_user_id ON Vote USING btree (user_id);
CREATE INDEX idx_vote_comment_id ON Vote USING btree (comment_id);


-----------------
-- TRANSACTIONS -
-----------------


BEGIN;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

DO
$$
DECLARE 
    availableTickets INT;
BEGIN
    -- verify available quantity
    SELECT ticketsAvailable INTO availableTickets
    FROM Event
    WHERE id = eventID;

    -- verify if there is needed quantity
    IF availableTickets >= ticketsDesired THEN

        INSERT INTO Ticket (pricePaid, event_id, user_id)
        VALUES (ticketPrice, eventID, userID); 

        UPDATE Event
        SET ticketsAvailable = ticketsAvailable - ticketsDesired
        WHERE id = eventID;
    ELSE
        ROLLBACK;
    END IF;
END; 
$$;

COMMIT;

------------------
--- POPULATION
------------------

INSERT INTO Category (name) VALUES
  ('Concerts'),
  ('Sports'),
  ('Conferences');

INSERT INTO Country (name, initials) VALUES
  ('United States', 'US'),
  ('Canada', 'CA'),
  ('United Kingdom', 'UK'),
  ('Brazil', 'BR'),
  ('Spain', 'SP'),
  ('Portugal', 'PT');

INSERT INTO State (name, initials, country_id) VALUES
  ('California', 'CA', 1),
  ('New York', 'NY', 1),
  ('Ontario', 'ONT', 2),
  ('São Paulo', 'SP', 4),
  ('Porto', 'PO', 6);

INSERT INTO City (name, state_id) VALUES
  ('Los Angeles', 1),
  ('New York City', 2),
  ('Toronto', 3),
  ('São Paulo', 4),
  ('Porto', 5);

INSERT INTO University (name, address, city_id) VALUES
  ('University of California, Los Angeles', '405 Hilgard Ave', 1),
  ('Columbia University', '116th St & Broadway', 2),
  ('Universidade de São Paulo', 'R. da Reitoria, R. Cidade Universitária, 374', 4),
  ('Universidade do Porto', 'Praça de Gomes Teixeira', 5);

INSERT INTO Users (name, phone, email, password, birthDate, university_id, isBanned, isDeleted) VALUES
  ('John Doe', '+1 (123) 456-7890', 'john.doe@example.com', '902fab49244e61e09d9568aedebc84daa1da7b2a', '1990-03-15', 1, false, false),
  ('Jane Smith', '+1 (987) 654-3210', 'jane.smith@example.com', '3dbd406aad81722b7311188ab5600ea0239f7965', '1988-08-20', 2, false, false);


