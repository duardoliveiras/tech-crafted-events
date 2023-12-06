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
DROP TYPE IF EXISTS NotificationType CASCADE;
DROP TYPE IF EXISTS ticket_status CASCADE;
DROP TYPE IF EXISTS event_status CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS University CASCADE;
DROP TABLE IF EXISTS Event CASCADE;
DROP TABLE IF EXISTS City CASCADE;
DROP TABLE IF EXISTS State CASCADE;
DROP TABLE IF EXISTS Country CASCADE;
DROP TABLE IF EXISTS Category CASCADE;
DROP TABLE IF EXISTS EventNotifications CASCADE;
DROP TABLE IF EXISTS UsersEventNotifications CASCADE;

-------------------------
-- TABLES ---------------
-------------------------

CREATE TABLE Category
(
    id   UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE Country
(
    id       UUID PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    initials CHAR(3),
    UNIQUE (initials)
);

CREATE TABLE State
(
    id         UUID PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    initials   CHAR(3),
    country_id UUID         NOT NULL,
    UNIQUE (initials),
    FOREIGN KEY (country_id) REFERENCES Country (id)
);

CREATE TABLE City
(
    id       UUID PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    state_id UUID         NOT NULL,
    FOREIGN KEY (state_id) REFERENCES State (id)
);

CREATE TABLE University
(
    id      UUID PRIMARY KEY,
    address VARCHAR(255) NOT NULL,
    name    VARCHAR(255) NOT NULL,
    city_id UUID         NOT NULL,
    FOREIGN KEY (city_id) REFERENCES City (id)
);

CREATE TABLE Users
(
    id            UUID PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    phone         CHAR(15)     NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    birthDate     DATE         NOT NULL,
    university_id UUID         NOT NULL,
    FOREIGN KEY (university_id) REFERENCES University (id),
    is_banned     BOOLEAN DEFAULT FALSE,
    is_deleted    BOOLEAN DEFAULT FALSE,
    image_url     VARCHAR(255) NOT NULL
);

CREATE TABLE EventOrganizer
(
    id       UUID PRIMARY KEY,
    legal_id CHAR(50) NOT NULL,
    user_id  UUID     NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TYPE event_status AS ENUM ('UPCOMING', 'ONGOING', 'FINISHED', 'CANCELLED', 'BANNED', 'DELETED');

CREATE TABLE Event
(
    id                  UUID PRIMARY KEY,
    name                VARCHAR(255)   NOT NULL,
    description         TEXT           NOT NULL,
    start_date          timestamp      NOT NULL,
    end_date            timestamp      NOT NULL,
    start_tickets_qty   INT            NOT NULL CHECK (start_tickets_qty >= 0),
    current_tickets_qty INT            NOT NULL CHECK (current_tickets_qty >= 0),
    current_price       DECIMAL(10, 2) NOT NULL CHECK (current_price >= 0),
    address             VARCHAR(255)   NOT NULL,
    image_url           VARCHAR(255)   NOT NULL,
    category_id         UUID           NOT NULL,
    city_id             UUID           NOT NULL,
    owner_id            UUID           NOT NULL,
    status event_status DEFAULT 'UPCOMING' NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES EventOrganizer (id),
    FOREIGN KEY (category_id) REFERENCES Category (id),
    FOREIGN KEY (city_id) REFERENCES City (id)
);

CREATE TYPE NotificationType AS ENUM ('INVITE', 'REMINDER', 'REPORT');

CREATE TABLE Notification
(
    id               UUID PRIMARY KEY,
    text             TEXT             NOT NULL,
    expiresAt        DATE             NOT NULL,
    notificationType NotificationType NOT NULL,
    user_id          UUID             NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE EventNotifications
(
    id                SERIAL PRIMARY KEY,
    event_id          UUID NOT NULL,
    notification_text TEXT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event (id)
);

CREATE TABLE UsersEventNotifications
(
    id              SERIAL PRIMARY KEY,
    user_id         UUID    NOT NULL,
    read            BOOLEAN NOT NULL,
    notification_id SERIAL  NOT NULL,
    FOREIGN KEY (notification_id) REFERENCES eventNotifications (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Admin
(
    id      UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Discussion
(
    id       UUID PRIMARY KEY,
    event_id UUID NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event (id)
);

CREATE TYPE ticket_status AS ENUM ('PENDING', 'PAID', 'READ', 'CANCELED', 'ERROR');

CREATE TABLE Ticket
(
    id         UUID PRIMARY KEY,
    price_paid DECIMAL(10, 2) NOT NULL,
    status     ticket_status DEFAULT 'PENDING',
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    event_id   UUID           NOT NULL,
    user_id    UUID           NOT NULL,
    FOREIGN KEY (event_id) REFERENCES Event (id),
    FOREIGN KEY (user_id) REFERENCES Users (id)
);

CREATE TABLE Comment
(
    id            UUID PRIMARY KEY,
    text          TEXT      NOT NULL,
    commented_at  TIMESTAMP NOT NULL,
    user_id       UUID      NOT NULL,
    discussion_id UUID      NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (discussion_id) REFERENCES Discussion (id)
);


CREATE TABLE Vote
(
    id         UUID PRIMARY KEY,
    vote_type  smallint NOT NULL, -- 1 for upvote, -1 for downvote
    voted_at   TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    user_id    UUID     NOT NULL,
    comment_id UUID     NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (comment_id) REFERENCES Comment (id)
);

create or replace function NOTIFY_EVENT_UPDATE()
    returns trigger as
$$
declare
    id_notification INTEGER;
begin
    insert into tech_crafted.eventnotifications(id, event_id, notification_text)
    values (DEFAULT, new.id, new.name || ' has been updated.')
    returning id into id_notification;

    insert into tech_crafted.userseventnotifications(user_id, notification_id, read)
    select user_id, id_notification, false
    from ticket
    where event_id = new.id;

    return new;
end;
CREATE OR REPLACE FUNCTION notify_event_update()
    RETURNS trigger AS 
$$
declare
    id_notification INTEGER;
   	v_notification_text VARCHAR;
   	v_update bool;
begin
	
	if new is distinct from old then
		v_notification_text := new.name || ' the event underwent several changes.';
		v_update = true;
	end if;
	
	if new.name <> old.name and new.description = old.description and new.start_date = old.start_date and new.end_date = old.end_date
		and new.address = old.address then
		v_notification_text := old.name || ' name has been updated.';
		v_update = true;
	
	elsif new.name = old.name and new.description <> old.description and new.start_date = old.start_date and new.end_date = old.end_date
		and new.address = old.address then
		v_notification_text := old.name || ' description has been updated.';
		v_update = true;
	
	elsif new.name = old.name and new.description = old.description and new.start_date <> old.start_date and new.end_date = old.end_date
		and new.address = old.address then
		v_notification_text := old.name || ' start date has been updated to ' || to_char(new.start_date, 'mm/dd/yyyy hh:mi');
		v_update = true;
	
	elsif new.name = old.name and new.description = old.description and new.start_date = old.start_date and new.end_date <> old.end_date
		and new.address = old.address then1
		v_notification_text := old.name || ' end date has been updated to ' || to_char(new.end_date, 'mm/dd/yyyy hh:mi');
		v_update = true;
	
	elsif new.name = old.name and new.description = old.description and new.start_date = old.start_date and new.end_date = old.end_date
		and new.address <> old.address then
		v_notification_text := old.name || ' address has been updated.';
		v_update = true;
	else
		v_update = false;
	end if;

	if v_update = true then
	
	    insert into tech_crafted.eventnotifications(id, event_id, notification_text)
	    values (DEFAULT, new.id, v_notification_text)
	    returning id into id_notification;
	
	    insert into tech_crafted.userseventnotifications(user_id, notification_id, read)
	    select user_id, id_notification, false
	    from ticket
	    where event_id = new.id;
	 end if;
	
	return new;
end;
$$ language plpgsql;

create trigger event_update_trigger
    after update
    on event
    for each row
execute function notify_event_update();


INSERT INTO Category (id, name)
VALUES ('836080d2-63d0-4917-97ac-c404614f44be', 'Concerts'),
       ('88a5890b-5d41-440e-a330-1e0c049ffb86', 'Sports'),
       ('4447616f-c7a9-48a4-9f0f-0c12c6b988da', 'Conferences');

INSERT INTO Country (id, name, initials)
VALUES ('11111111-1111-1111-1111-111111111111', 'United States', 'US'),
       ('22222222-2222-2222-2222-222222222222', 'Canada', 'CA'),
       ('33333333-3333-3333-3333-333333333333', 'United Kingdom', 'UK'),
       ('44444444-4444-4444-4444-444444444444', 'Brazil', 'BR'),
       ('55555555-5555-5555-5555-555555555555', 'Spain', 'SP'),
       ('66666666-6666-6666-6666-666666666666', 'Portugal', 'PT');

INSERT INTO State (id, name, initials, country_id)
VALUES ('77777777-7777-7777-7777-777777777777', 'California', 'CA', '11111111-1111-1111-1111-111111111111'),
       ('88888888-8888-8888-8888-888888888888', 'New York', 'NY', '11111111-1111-1111-1111-111111111111'),
       ('99999999-9999-9999-9999-999999999999', 'Ontario', 'ONT', '22222222-2222-2222-2222-222222222222'),
       ('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'São Paulo', 'SP', '44444444-4444-4444-4444-444444444444'),
       ('bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'Porto', 'PO', '66666666-6666-6666-6666-666666666666');

INSERT INTO City (id, name, state_id)
VALUES ('cccccccc-cccc-cccc-cccc-cccccccccccc', 'Los Angeles', '77777777-7777-7777-7777-777777777777'),
       ('dddddddd-dddd-dddd-dddd-dddddddddddd', 'New York City', '88888888-8888-8888-8888-888888888888'),
       ('eeeeeeee-eeee-eeee-eeee-eeeeeeeeeeee', 'Toronto', '99999999-9999-9999-9999-999999999999'),
       ('ffffffff-ffff-ffff-ffff-ffffffffffff', 'São Paulo', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa'),
       ('11111111-1111-1111-1111-111111111111', 'Porto', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb');

INSERT INTO University (id, name, address, city_id)
VALUES ('22222222-2222-2222-2222-222222222222', 'University of California, Los Angeles', '405 Hilgard Ave',
        'cccccccc-cccc-cccc-cccc-cccccccccccc'),
       ('33333333-3333-3333-3333-333333333333', 'Columbia University', '116th St & Broadway',
        'dddddddd-dddd-dddd-dddd-dddddddddddd'),
       ('44444444-4444-4444-4444-444444444444', 'Universidade de São Paulo',
        'R. da Reitoria, R. Cidade Universitária, 374', 'ffffffff-ffff-ffff-ffff-ffffffffffff'),
       ('55555555-5555-5555-5555-555555555555', 'Universidade do Porto', 'Praça de Gomes Teixeira',
        '11111111-1111-1111-1111-111111111111');

INSERT INTO Users (id, name, phone, email, password, birthDate, university_id, is_banned, is_deleted, image_url)
VALUES ('66666666-6666-6666-6666-666666666666', 'Tiririca', '+55 77997890', 'tiririca@gmail.com',
        '902fab49244e61e09d9568aedebc84daa1da7b2a', '1990-03-15', '22222222-2222-2222-2222-222222222222', false, false,
        ''),
       ('77777777-7777-7777-7777-777777777777', 'Manoel Gomes', '+1 654-3210', 'caneta-azul@azul-caneta.com',
        '3dbd406aad81722b7311188ab5600ea0239f7965', '1988-08-20', '33333333-3333-3333-3333-333333333333', false, false,
        '');

INSERT INTO eventorganizer (id, legal_id, user_id)
VALUES ('88888888-8888-8888-8888-888888888888', '123456', '66666666-6666-6666-6666-666666666666'),
       ('99999999-9999-9999-9999-999999999999', '125656', '77777777-7777-7777-7777-777777777777');

INSERT INTO Users (id, name, phone, email, password, birthDate, university_id, is_banned, is_deleted, image_url)
VALUES ('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Tiririca Pior Que Tá Não Fica', '+55 99997890', 'admin@gmail.com',
        '$2y$10$/cAIN8kgiGZR3jDakznSreoEZYQ6NNXnfEAUPEeWmgB9gd3.IdKaG', '1990-03-15',
        '22222222-2222-2222-2222-222222222222', false, false, '');

INSERT INTO admin (id, user_id)
VALUES ('bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa');

