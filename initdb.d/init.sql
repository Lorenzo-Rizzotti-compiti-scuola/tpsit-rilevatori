CREATE TABLE Impianto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255),
    latitudine DECIMAL(9,6),
    longitudine DECIMAL(9,6)
);

CREATE TABLE Rilevatore (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('umidita', 'temperatura'),
    unitaDiMisura VARCHAR(10),
    codiceSeriale VARCHAR(255),
    posizione ENUM('terra', 'aria') NULL,
    tipologia ENUM('acqua', 'aria') NULL,
    impianto_id INT,
    FOREIGN KEY (impianto_id) REFERENCES Impianto(id)
);

CREATE TABLE Misurazione (
    id INT PRIMARY KEY AUTO_INCREMENT,
    data DATETIME,
    valore DECIMAL(5,2),
    rilevatore_id INT,
    FOREIGN KEY (rilevatore_id) REFERENCES Rilevatore(id)
);

INSERT INTO Impianto (nome, latitudine, longitudine) VALUES
    ('Impianto 1', 45.123456, 9.123456);
