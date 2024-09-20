CREATE DATABASE motos_pw;

USE motos_pw;

CREATE TABLE motos_dados (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    marca VARCHAR(255) NOT NULL,
    modelo VARCHAR(255) NOT NULL,
    motor SMALLINT(4) NOT NULL,
    ano INT NOT NULL,
    cor VARCHAR(255) NOT NULL,
    quilometragem INT NOT NULL, 
    imagem BLOB,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL
);

CREATE TABLE clientes (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefone VARCHAR(15),
    endereco VARCHAR(255),
    cidade VARCHAR(100),
    estado VARCHAR(50),
    cpf VARCHAR(14) NOT NULL UNIQUE,
    imagem BLOB,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL
);