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
DROP TYPE IF EXISTS NotificationType;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS University CASCADE;
DROP TABLE IF EXISTS Event CASCADE;
DROP TABLE IF EXISTS City CASCADE;
DROP TABLE IF EXISTS State CASCADE;
DROP TABLE IF EXISTS Country CASCADE;
DROP TABLE IF EXISTS Category CASCADE;

DROP EXTENSION IF EXISTS "uuid-ossp";
CREATE EXTENSION "uuid-ossp";


-------------------------
-- TABLES ---------------
-------------------------

CREATE TABLE Category
(
    id   UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name VARCHAR(255) NOT NULL
);

CREATE TABLE Country
(
    id       UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name     VARCHAR(255) NOT NULL,
    initials CHAR(3),
    UNIQUE (initials)
);

CREATE TABLE State
(
    id         UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name       VARCHAR(255) NOT NULL,
    initials   CHAR(3),
    country_id UUID          NOT NULL,
    UNIQUE (initials),
    FOREIGN KEY (country_id) REFERENCES Country (id)
);

CREATE TABLE City
(
    id       UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name     VARCHAR(255) NOT NULL,
    state_id UUID          NOT NULL,
    FOREIGN KEY (state_id) REFERENCES State (id)
);

CREATE TABLE University
(
    id      UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    address VARCHAR(255) NOT NULL,
    name    VARCHAR(255) NOT NULL,
    city_id UUID          NOT NULL,
    FOREIGN KEY (city_id) REFERENCES City (id)
);

CREATE TABLE Users
(
    id            UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name          VARCHAR(255) NOT NULL,
    phone         CHAR(15)     NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    birthDate     DATE         NOT NULL,
    university_id UUID          NOT NULL,
    FOREIGN KEY (university_id) REFERENCES University (id),
    is_banned     BOOLEAN          DEFAULT FALSE,
    is_deleted    BOOLEAN          DEFAULT FALSE
);

CREATE TABLE EventOrganizer
(
    id      UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    legalId CHAR(50) NOT NULL,
    user_id UUID      NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);


CREATE TABLE Event
(
    id                UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name              VARCHAR(255)   NOT NULL,
    description       TEXT           NOT NULL,
    startDate         DATE           NOT NULL,
    endDate           DATE           NOT NULL,
    startTicketsQty   INT            NOT NULL,
    currentTicketsQty INT            NOT NULL,
    currentPrice      DECIMAL(10, 2) NOT NULL,
    address           VARCHAR(255)   NOT NULL,
    category_id       UUID            NOT NULL,
    city_id           UUID            NOT NULL,
    owner_id          UUID            NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES EventOrganizer (id),
    FOREIGN KEY (category_id) REFERENCES Category (id),
    FOREIGN KEY (city_id) REFERENCES City (id)
);

CREATE TYPE NotificationType AS ENUM ('INVITE', 'REMINDER', 'REPORT');

CREATE TABLE Notification
(
    id               UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    text             TEXT             NOT NULL,
    expiresAt        DATE             NOT NULL,
    notificationType NotificationType NOT NULL,
    user_id          UUID              NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Admin
(
    id      UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Discussion
(
    id       UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    event_id UUID NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event (id)
);

CREATE TABLE Ticket
(
    id        UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    pricePaid DECIMAL(10, 2) NOT NULL,
    event_id  UUID            NOT NULL,
    user_id   UUID            NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event (id),
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Comment
(
    id            UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    text          TEXT NOT NULL,
    commentedAt   DATE NOT NULL,
    user_id       UUID  NOT NULL,
    discussion_id UUID  NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (discussion_id) REFERENCES Discussion (id)
);


CREATE TABLE Vote
(
    id         UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    voteType   smallint NOT NULL, -- 1 for upvote, -1 for downvote
    votedAt    TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    user_id    UUID      NOT NULL,
    comment_id UUID      NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (comment_id) REFERENCES Comment (id)
);


INSERT INTO Category (id, name)
VALUES (uuid_generate_v4(), 'Concerts'),
       (uuid_generate_v4(), 'Sports'),
       (uuid_generate_v4(), 'Conferences');

INSERT INTO Country (id, name, initials)
VALUES (uuid_generate_v4(), 'United States', 'US'),
       (uuid_generate_v4(), 'Canada', 'CA'),
       (uuid_generate_v4(), 'United Kingdom', 'UK'),
       (uuid_generate_v4(), 'Brazil', 'BR'),
       (uuid_generate_v4(), 'Spain', 'SP'),
       (uuid_generate_v4(), 'Portugal', 'PT');

INSERT INTO State (id, name, initials, country_id)
VALUES (uuid_generate_v4(), 'California', 'CA', (SELECT id FROM Country WHERE name = 'United States')),
       (uuid_generate_v4(), 'New York', 'NY', (SELECT id FROM Country WHERE name = 'United States')),
       (uuid_generate_v4(), 'Ontario', 'ONT', (SELECT id FROM Country WHERE name = 'Canada')),
       (uuid_generate_v4(), 'São Paulo', 'SP', (SELECT id FROM Country WHERE name = 'Brazil')),
       (uuid_generate_v4(), 'Porto', 'PO', (SELECT id FROM Country WHERE name = 'Portugal'));

INSERT INTO City (id, name, state_id)
VALUES (uuid_generate_v4(), 'Los Angeles', (SELECT id FROM State WHERE name = 'California')),
       (uuid_generate_v4(), 'New York City', (SELECT id FROM State WHERE name = 'New York')),
       (uuid_generate_v4(), 'Toronto', (SELECT id FROM State WHERE name = 'Ontario')),
       (uuid_generate_v4(), 'São Paulo', (SELECT id FROM State WHERE name = 'São Paulo')),
       (uuid_generate_v4(), 'Porto', (SELECT id FROM State WHERE name = 'Porto'));

INSERT INTO University (id, name, address, city_id)
VALUES (uuid_generate_v4(), 'University of California, Los Angeles', '405 Hilgard Ave',
        (SELECT id FROM City WHERE name = 'Los Angeles')),
       (uuid_generate_v4(), 'Columbia University', '116th St & Broadway',
        (SELECT id FROM City WHERE name = 'New York City')),
       (uuid_generate_v4(), 'Universidade de São Paulo', 'R. da Reitoria, R. Cidade Universitária, 374',
        (SELECT id FROM City WHERE name = 'São Paulo')),
       (uuid_generate_v4(), 'Universidade do Porto', 'Praça de Gomes Teixeira',
        (SELECT id FROM City WHERE name = 'Porto'));

INSERT INTO Users (id, name, phone, email, password, birthDate, university_id, is_banned, is_deleted)
VALUES (uuid_generate_v4(), 'Tiririca', '+55 77997890', 'tiririca@gmail.com', '902fab49244e61e09d9568aedebc84daa1da7b2a',
        '1990-03-15',
        (SELECT id FROM University WHERE name = 'University of California, Los Angeles'), false, false),
       (uuid_generate_v4(), 'Manoel Gomes', '+1 654-3210', 'caneta-azul@azul-caneta.com',
        '3dbd406aad81722b7311188ab5600ea0239f7965',
        '1988-08-20', (SELECT id FROM University WHERE name = 'Columbia University'), false, false);

INSERT INTO eventorganizer (id, legalid, user_id)
VALUES (uuid_generate_v4(), '123456', (SELECT id FROM Users WHERE name = 'Tiririca')),
       (uuid_generate_v4(), '125656', (SELECT id FROM Users WHERE name = 'Manoel Gomes'));

INSERT INTO Users (id, name, phone, email, password, birthDate, university_id, is_banned, is_deleted)
VALUES (uuid_generate_v4(), 'tiririca pior que ta nao fica', '+55 99997890', 'admin@gmail.com', '$2y$10$/cAIN8kgiGZR3jDakznSreoEZYQ6NNXnfEAUPEeWmgB9gd3.IdKaG',
        '1990-03-15',
        (SELECT id FROM University WHERE name = 'University of California, Los Angeles'), false, false);
INSERT INTO admin (id, user_id)
VALUES (uuid_generate_v4(), (SELECT id FROM Users WHERE name = 'tiririca pior que ta nao fica'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Music Festival', 'A three-day music extravaganza', '2023-09-27', '2023-09-30', 1000, 750,
        75.00, '123 Main St, Cityville', (SELECT id FROM Category WHERE name = 'Concerts'),
        (SELECT id FROM City WHERE name = 'Los Angeles'), (SELECT id FROM eventorganizer WHERE legalid = '123456'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Art Exhibition', 'Featuring local artists', '2023-09-11', '2023-09-16', 500, 400, 28.50,
        '456 Elm St, Townsville', (SELECT id FROM Category WHERE name = 'Sports'),
        (SELECT id FROM City WHERE name = 'New York City'), (SELECT id FROM eventorganizer WHERE legalid = '123456'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Sports Event', 'Soccer championship', '2023-02-18', '2023-02-22', 1000, 850, 59.99,
        '789 Oak St, Sports City', (SELECT id FROM Category WHERE name = 'Sports'),
        (SELECT id FROM City WHERE name = 'Toronto'), (SELECT id FROM eventorganizer WHERE legalid = '125656'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Food Festival', 'A culinary delight', '2023-04-19', '2023-04-23', 800, 700, 80.50,
        '567 Pine St, Foodtown', (SELECT id FROM Category WHERE name = 'Concerts'),
        (SELECT id FROM City WHERE name = 'São Paulo'), (SELECT id FROM eventorganizer WHERE legalid = '123456'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Tech Conference', 'Innovation and technology', '2023-09-27', '2023-09-30', 500, 450, 60.00,
        '345 Cedar St, Techville', (SELECT id FROM Category WHERE name = 'Conferences'),
        (SELECT id FROM City WHERE name = 'Porto'), (SELECT id FROM eventorganizer WHERE legalid = '125656'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Comedy Show', 'Laughs and entertainment', '2022-11-18', '2022-11-20', 300, 250, 45.00,
        '101 Maple St, Laughsville', (SELECT id FROM Category WHERE name = 'Concerts'),
        (SELECT id FROM City WHERE name = 'Los Angeles'), (SELECT id FROM eventorganizer WHERE legalid = '123456'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Film Festival', 'Celebrating cinema', '2023-05-04', '2023-05-07', 600, 550, 65.00,
        '222 Film St, Movietown', (SELECT id FROM Category WHERE name = 'Conferences'),
        (SELECT id FROM City WHERE name = 'New York City'), (SELECT id FROM eventorganizer WHERE legalid = '125656'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Fashion Show', 'Latest fashion trends', '2023-04-22', '2023-04-25', 400, 350, 40.00,
        '777 Runway St, Fashionville', (SELECT id FROM Category WHERE name = 'Concerts'),
        (SELECT id FROM City WHERE name = 'Toronto'), (SELECT id FROM eventorganizer WHERE legalid = '125656'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Science Symposium', 'Exploring scientific discoveries', '2022-11-30', '2022-12-02', 200,
        150, 70.00, '999 Lab St, Sciencetown', (SELECT id FROM Category WHERE name = 'Conferences'),
        (SELECT id FROM City WHERE name = 'São Paulo'), (SELECT id FROM eventorganizer WHERE legalid = '123456'));

insert into event (id, name, description, startdate, enddate, startticketsqty, currentticketsqty, currentprice, address,
                   category_id, city_id, owner_id)
values (uuid_generate_v4(), 'Dance Performance', 'A mesmerizing dance showcase', '2023-07-26', '2023-07-28', 300, 250,
        25.00, '444 Rhythm St, Dancetown', (SELECT id FROM Category WHERE name = 'Sports'),
        (SELECT id FROM City WHERE name = 'Porto'), (SELECT id FROM eventorganizer WHERE legalid = '125656'));






