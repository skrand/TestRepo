-- Slett database hvis eksisterer
DROP SCHEMA IF EXISTS GruppeRomBooking;

-- Lag og bruk en ny database
CREATE SCHEMA IF NOT EXISTS GruppeRomBooking;
USE GruppeRomBooking;

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
('Rom 45', '4', 'n'),
('Rom 31', '3', 'j'),
('Rom 32', '2', 'j'),
('Rom 33', '4', 'n');

INSERT INTO Bruker (Brukernavn, Passord)
VALUES ('admin', '$2y$10$k4fENyEARYL4qB.EvuK32eFXCKjxRNAAFsX1wgDXsXaxrPZA2oK82');