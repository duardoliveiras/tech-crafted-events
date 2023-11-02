CREATE SCHEMA IF NOT EXISTS tech_crafted;
SET
search_path TO tech_crafted;

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
    id   SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE Country
(
    id       SERIAL PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    initials CHAR(3),
    UNIQUE (initials)
);

CREATE TABLE State
(
    id         SERIAL PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    initials   CHAR(3),
    country_id INT          NOT NULL,
    UNIQUE (initials),
    FOREIGN KEY (country_id) REFERENCES Country (id)
);

CREATE TABLE City
(
    id       SERIAL PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    state_id INT          NOT NULL,
    FOREIGN KEY (state_id) REFERENCES State (id)
);

CREATE TABLE University
(
    id      SERIAL PRIMARY KEY,
    address VARCHAR(255) NOT NULL,
    name    VARCHAR(255) NOT NULL,
    city_id INT          NOT NULL,
    FOREIGN KEY (city_id) REFERENCES City (id)
);

CREATE TABLE Users
(
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    phone         CHAR(15)     NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    birthDate     DATE         NOT NULL,
    university_id INT          NOT NULL,
    FOREIGN KEY (university_id) REFERENCES University (id),
    isBanned      BOOLEAN DEFAULT FALSE,
    isDeleted     BOOLEAN DEFAULT FALSE
);

CREATE TABLE EventOrganizer
(
    id      SERIAL PRIMARY KEY,
    legalId CHAR(50) NOT NULL,
    user_id INT      NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);


CREATE TABLE Event
(
    id                SERIAL PRIMARY KEY,
    name              VARCHAR(255)   NOT NULL,
    description       TEXT           NOT NULL,
    startDate         DATE           NOT NULL,
    endDate           DATE           NOT NULL,
    startTicketsQty   INT            NOT NULL,
    currentTicketsQty INT            NOT NULL,
    currentPrice      DECIMAL(10, 2) NOT NULL,
    address           VARCHAR(255)   NOT NULL,
    category_id       INT            NOT NULL,
    city_id           INT            NOT NULL,
    owner_id           INT            NOT NULL,
    FOREIGN KEY (ownerId) REFERENCES EventOrganizer (id),
    FOREIGN KEY (category_id) REFERENCES Category (id),
    FOREIGN KEY (city_id) REFERENCES City (id)
);

CREATE TYPE NotificationType AS ENUM ('INVITE', 'REMINDER', 'REPORT');

CREATE TABLE Notification
(
    id               SERIAL PRIMARY KEY,
    text             TEXT             NOT NULL,
    expiresAt        DATE             NOT NULL,
    notificationType NotificationType NOT NULL,
    user_id          INT              NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Admin
(
    id      SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Discussion
(
    id       SERIAL PRIMARY KEY,
    event_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event (id)
);

CREATE TABLE Ticket
(
    id        SERIAL PRIMARY KEY,
    pricePaid DECIMAL(10, 2) NOT NULL,
    event_id  INT            NOT NULL,
    user_id   INT            NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event (id),
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Comment
(
    id            SERIAL PRIMARY KEY,
    text          TEXT NOT NULL,
    commentedAt   DATE NOT NULL,
    user_id       INT  NOT NULL,
    discussion_id INT  NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (discussion_id) REFERENCES Discussion (id)
);


CREATE TABLE Vote
(
    id         SERIAL PRIMARY KEY,
    voteType   smallint NOT NULL, -- 1 for upvote, -1 for downvote
    votedAt    TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    user_id    INT      NOT NULL,
    comment_id INT      NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (comment_id) REFERENCES Comment (id)
);


INSERT INTO Category (id, name)
VALUES (1, 'Concerts'),
       (2, 'Sports'),
       (3, 'Conferences');

INSERT INTO Country (id, name, initials)
VALUES (1, 'United States', 'US'),
       (2, 'Canada', 'CA'),
       (3, 'United Kingdom', 'UK'),
       (4, 'Brazil', 'BR'),
       (5, 'Spain', 'SP'),
       (6, 'Portugal', 'PT');


INSERT INTO State (id, name, initials, country_id)
VALUES (1, 'California', 'CA', 1),
       (2, 'New York', 'NY', 1),
       (3, 'Ontario', 'ONT', 2),
       (4, 'São Paulo', 'SP', 4),
       (5, 'Porto', 'PO', 6);


INSERT INTO City (id, name, state_id)
VALUES (1, 'Los Angeles', 1),
       (2, 'New York City', 2),
       (3, 'Toronto', 3),
       (4, 'São Paulo', 4),
       (5, 'Porto', 5);


INSERT INTO University (id, name, address, city_id)
VALUES (1, 'University of California, Los Angeles', '405 Hilgard Ave', 1),
       (2, 'Columbia University', '116th St & Broadway', 2),
       (3, 'Universidade de São Paulo', 'R. da Reitoria, R. Cidade Universitária, 374', 4),
       (4, 'Universidade do Porto', 'Praça de Gomes Teixeira', 5);


INSERT INTO Users (id, name, phone, email, password, birthDate, university_id, isBanned, isDeleted)
VALUES (1, 'John Doe', '+1 456-7890', 'john.doe@example.com', '902fab49244e61e09d9568aedebc84daa1da7b2a', '1990-03-15',
        1, false, false),
       (2, 'Jane Smith', '+1 654-3210', 'jane.smith@example.com', '3dbd406aad81722b7311188ab5600ea0239f7965',
        '1988-08-20', 2, false, false);

INSERT INTO eventorganizer (id, legalid, user_id)
VALUES (1, '123456', 1),
       (2, '125656', 2);

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (1, 'Music Festival', 'A three-day music extravaganza', '2023-09-27', '2023-09-30', 1000, 750, 75.00,
        '123 Main St, Cityville', 1, 1, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (2, 'Art Exhibition', 'Featuring local artists', '2023-09-11', '2023-09-16', 500, 400, 28.50,
        '456 Elm St, Townsville', 2, 2, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (3, 'Sports Event', 'Soccer championship', '2023-02-18', '2023-02-22', 1000, 850, 59.99,
        '789 Oak St, Sports City', 2, 3, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (4, 'Food Festival', 'A culinary delight', '2023-04-19', '2023-04-23', 800, 700, 80.50, '567 Pine St, Foodtown',
        1, 4, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (5, 'Tech Conference', 'Innovation and technology', '2023-09-27', '2023-09-30', 500, 450, 60.00,
        '345 Cedar St, Techville', 3, 5, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (6, 'Comedy Show', 'Laughs and entertainment', '2022-11-18', '2022-11-20', 300, 250, 45.00,
        '101 Maple St, Laughsville', 1, 1, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (7, 'Film Festival', 'Celebrating cinema', '2023-05-04', '2023-05-07', 600, 550, 65.00, '222 Film St, Movietown',
        3, 2, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (8, 'Fashion Show', 'Latest fashion trends', '2023-04-22', '2023-04-25', 400, 350, 40.00,
        '777 Runway St, Fashionville', 1, 3, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (9, 'Science Symposium', 'Exploring scientific discoveries', '2022-11-30', '2022-12-02', 200, 150, 70.00,
        '999 Lab St, Sciencetown', 3, 4, 1);
insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (10, 'Dance Performance', 'A mesmerizing dance showcase', '2023-07-26', '2023-07-28', 300, 250, 25.00,
        '444 Rhythm St, Dancetown', 2, 5, 1);





