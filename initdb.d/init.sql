CREATE TABLE Impianto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255),
    latitudine DECIMAL(9,6),
    longitudine DECIMAL(9,6)
);

CREATE TABLE Rilevatore (
    id VARCHAR(255) PRIMARY KEY,
    tipo ENUM('umidita', 'temperatura'),
    unitaDiMisura VARCHAR(10),
    posizione ENUM('terra', 'acqua', 'aria') NULL,
    impianto_id INT,
    FOREIGN KEY (impianto_id) REFERENCES Impianto(id)
);

CREATE TABLE Misurazione (
    id INT PRIMARY KEY AUTO_INCREMENT,
    data DATETIME,
    valore DECIMAL(5,2),
    rilevatore_id VARCHAR(255),
    FOREIGN KEY (rilevatore_id) REFERENCES Rilevatore(id)
);

INSERT INTO Impianto (nome, latitudine, longitudine) VALUES
    ('Impianto 1', 45.123456, 9.123456);
