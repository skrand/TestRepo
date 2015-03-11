-- Slett database hvis eksisterer
DROP SCHEMA IF EXISTS WOACTGruppeRomBooking;

-- Lag og bruk en ny database
CREATE SCHEMA IF NOT EXISTS WOACTGruppeRomBooking;
USE WOACTGruppeRomBooking;

-- Lag tabellene
CREATE TABLE Rom
(
	RomId int NOT NULL AUTO_INCREMENT,
    Beskrivelse varchar(255),
    Storrelse enum('2', '3', '4'),
    Prosjektor enum('j', 'n'),
    CONSTRAINT pk_RomId PRIMARY KEY (RomId)
);

CREATE TABLE Bruker
(
	BrukerId int NOT NULL AUTO_INCREMENT,
    Brukernavn varchar(255),
    Passord varchar(255),
    Epost varchar(255),
    CONSTRAINT pk_BrukerId PRIMARY KEY (BrukerId)
);

CREATE TABLE LeieAvRom
(
	RomId int NOT NULL,
    BrukerId int NOT NULL,
    Dato date NOT NULL,
    Tidspunkt time NOT NULL,
    AntallTimer int NOT NULL,
    CONSTRAINT pk_LeieId PRIMARY KEY (RomId, Dato, Tidspunkt),
    CONSTRAINT fk_RomId FOREIGN KEY (RomId) REFERENCES Rom(RomId),
    CONSTRAINT fk_BrukerId FOREIGN KEY (BrukerId) REFERENCES Bruker(BrukerId)
);

-- Legg inn innhold
INSERT INTO Rom (Beskrivelse, Storrelse, Prosjektor)
VALUES ('Rom 81', '3', 'j'),
('Rom 82', '4', 'j'),
('Rom 83', '2', 'n'),
('Rom 44', '3', 'j'),
('Rom 45', '4', 'n');

INSERT INTO Bruker (Brukernavn, Passord, Epost)
VALUES ('Per', '123', 'per@westerdals.no'),
('Pål', '117', 'pal@westerdals.no'),
('Ola', 'abc', 'ola@westerdals.no'),
('Kari', 'passord', 'kari@westerdals.no'),
('Anna', 'uahsfwef23', 'anna@westerdals.no');

INSERT INTO LeieAvRom VALUES
(1, 1, '2015-03-11', '10:00:00', 2),
(1, 3, '2015-03-11', '12:00:00', 1),
(2, 2, '2015-03-11', '14:00:00', 1),
(2, 2, '2015-03-13', '14:00:00', 3),
(2, 1, '2015-03-13', '13:00:00', 1),
(3, 2, '2015-03-13', '16:00:00', 3),
(3, 1, '2015-03-14', '10:00:00', 2),
(1, 2, '2015-03-12', '13:00:00', 1),
(3, 3, '2015-03-12', '09:00:00', 3);

-- Spørringer
SELECT * FROM Rom;
SELECT * FROM Bruker;
SELECT * FROM LeieAvRom;

SELECT r.Beskrivelse, r.Storrelse, r.Prosjektor, l.Dato, l.Tidspunkt, l.AntallTimer, b.Brukernavn, b.Epost 
FROM Rom AS r
LEFT JOIN LeieAvRom AS l ON l.RomId = r.RomId
LEFT JOIN Bruker AS b ON l.BrukerId = b.BrukerId;


SELECT r.RomId, r.Beskrivelse, r.Storrelse, r.Prosjektor, l.Dato, l.Tidspunkt, l.AntallTimer, b.Brukernavn, b.Epost FROM Rom AS r
LEFT JOIN LeieAvRom AS l ON l.RomId = r.RomId
LEFT JOIN Bruker AS b ON l.BrukerId = b.BrukerId WHERE r.Storrelse IN (1, 2, 0)
AND r.Prosjektor LIKE '%';

SELECT r.RomId, r.Beskrivelse, r.Storrelse, r.Prosjektor, l.Dato, l.Tidspunkt, l.AntallTimer, b.Brukernavn, b.Epost 
FROM Rom AS r 
LEFT JOIN LeieAvRom AS l ON l.RomId = r.RomId 
LEFT JOIN Bruker AS b ON l.BrukerId = b.BrukerId 
WHERE r.Storrelse IN (2, 3, 4) 
AND r.Prosjektor LIKE '%' 
AND l.Dato LIKE '2015-03-11';