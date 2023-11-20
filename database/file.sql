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
    country_id UUID         NOT NULL,
    UNIQUE (initials),
    FOREIGN KEY (country_id) REFERENCES Country (id)
);

CREATE TABLE City
(
    id       UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name     VARCHAR(255) NOT NULL,
    state_id UUID         NOT NULL,
    FOREIGN KEY (state_id) REFERENCES State (id)
);

CREATE TABLE University
(
    id      UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    address VARCHAR(255) NOT NULL,
    name    VARCHAR(255) NOT NULL,
    city_id UUID         NOT NULL,
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
    university_id UUID         NOT NULL,
    FOREIGN KEY (university_id) REFERENCES University (id),
    is_banned     BOOLEAN          DEFAULT FALSE,
    is_deleted    BOOLEAN          DEFAULT FALSE,
    image_url     VARCHAR(255) NOT NULL
);

CREATE TABLE EventOrganizer
(
    id      UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    legal_id CHAR(50) NOT NULL,
    user_id UUID     NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Event
(
    id                    UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name                  VARCHAR(255)   NOT NULL,
    description           TEXT           NOT NULL,
    start_date            timestamp      NOT NULL,
    end_date              timestamp      NOT NULL,
    start_tickets_qty     INT            NOT NULL CHECK (start_tickets_qty >= 0),
    current_tickets_qty   INT            NOT NULL CHECK (current_tickets_qty >= 0),
    current_price        DECIMAL(10, 2) NOT NULL CHECK (current_price >= 0),
    address               VARCHAR(255)   NOT NULL,
    image_url             VARCHAR(255)   NOT NULL,
    category_id           UUID           NOT NULL,
    city_id               UUID           NOT NULL,
    owner_id              UUID           NOT NULL,
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
    user_id          UUID             NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE EventNotifications (
    id                  UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    event_id            UUID              NOT NULL,
    notification_text   TEXT              NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event(id)
);


CREATE TABLE UsersEventNotifications (
    id                   UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id              UUID              NOT NULL,
    read                 BOOLEAN           NOT NULL,
    notification_id      UUID              NOT NULL,
    FOREIGN KEY (notification_id) REFERENCES eventNotifications(id) ON DELETE CASCADE,
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
    price_paid DECIMAL(10, 2) NOT NULL,
    is_used   BOOLEAN        DEFAULT FALSE,
    event_id  UUID           NOT NULL,
    user_id   UUID           NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event (id),
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Comment
(
    id            UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    text          TEXT      NOT NULL,
    commented_at  TIMESTAMP NOT NULL,
    user_id       UUID      NOT NULL,
    discussion_id UUID      NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (discussion_id) REFERENCES Discussion (id)
);


CREATE TABLE Vote
(
    id         UUID PRIMARY KEY            DEFAULT uuid_generate_v4(),
    vote_type  smallint NOT NULL, -- 1 for upvote, -1 for downvote
    voted_at   TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    user_id    UUID     NOT NULL,
    comment_id UUID     NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (comment_id) REFERENCES Comment (id)
);

create or replace function NOTIFY_EVENT_UPDATE()
returns trigger as $$
declare id_notification UUID;
begin 	
		insert into tech_crafted.eventnotifications(event_id, notification_text)
		values(new.id, new.name || ' has been updated.')
		returning id into id_notification;
	
		insert into tech_crafted.userseventnotifications(user_id, notification_id, read)
		select user_id, id_notification, false 
		from ticket
		where event_id = new.id;
	
		return new;
end;

$$ language plpgsql;

create trigger event_update_trigger
after update
on event 
for each row 
execute function notify_event_update();


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

INSERT INTO Users (id, name, phone, email, password, birthDate, university_id, is_banned, is_deleted, image_url)
VALUES (uuid_generate_v4(), 'Tiririca', '+55 77997890', 'tiririca@gmail.com',
        '902fab49244e61e09d9568aedebc84daa1da7b2a',
        '1990-03-15',
        (SELECT id FROM University WHERE name = 'University of California, Los Angeles'), false, false, ''),
       (uuid_generate_v4(), 'Manoel Gomes', '+1 654-3210', 'caneta-azul@azul-caneta.com',
        '3dbd406aad81722b7311188ab5600ea0239f7965',
        '1988-08-20', (SELECT id FROM University WHERE name = 'Columbia University'), false, false, '');

INSERT INTO eventorganizer (id, legal_id, user_id)
VALUES (uuid_generate_v4(), '123456', (SELECT id FROM Users WHERE name = 'Tiririca')),
       (uuid_generate_v4(), '125656', (SELECT id FROM Users WHERE name = 'Manoel Gomes'));

INSERT INTO Users (id, name, phone, email, password, birthDate, university_id, is_banned, is_deleted, image_url)
VALUES (uuid_generate_v4(), 'Tiririca Pior Que Tá Não Fica', '+55 99997890', 'admin@gmail.com',
        '$2y$10$/cAIN8kgiGZR3jDakznSreoEZYQ6NNXnfEAUPEeWmgB9gd3.IdKaG',
        '1990-03-15',
        (SELECT id FROM University WHERE name = 'University of California, Los Angeles'), false, false, '');
INSERT INTO admin (id, user_id)
VALUES (uuid_generate_v4(), (SELECT id FROM Users WHERE name = 'Tiririca Pior Que Tá Não Fica'));








