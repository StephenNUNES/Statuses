CREATE TABLE IF NOT EXISTS Users
(
    login VARCHAR(50) NOT NULL PRIMARY KEY,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS Statuses 
(
    id INTEGER PRIMARY KEY,
    message VARCHAR(140) NOT NULL,
    dateStatus DATE NOT NULL,
    nameCreator VARCHAR(50) NOT NULL,
    senderClient VARCHAR(50) NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY (nameCreator) REFERENCES Users(login)
);


# mysql uframework -uuframework -ppassw0rd < app/config/schema.sql