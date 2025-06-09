-- Active: 1747300423576@@127.0.0.1@5432@riotoon
CREATE DATABASE riotoon;

CREATE TABLE category(
    c_id SERIAL PRIMARY KEY,
    label VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE webtoon
(
    w_id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(150) NOT NULL,
    synopsis TEXT NOT NULL,
    cover VARCHAR(255) NOT NULL,
    release_year INT NOT NULL,
    status  BOOLEAN NOT NULL,
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    update_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE web_cat
(
    wc_id SERIAL PRIMARY KEY,
    c_id INT NOT NULL,
    w_id INT NOT NULL,
    CONSTRAINT fk_cat FOREIGN KEY (c_id) REFERENCES category(c_id)
    ON DELETE CASCADE,
    CONSTRAINT fk_web FOREIGN KEY (w_id) REFERENCES webtoon(w_id)
    ON DELETE CASCADE
);

CREATE TABLE chapter(
    ch_id SERIAL PRIMARY KEY,
    ch_num INT NOT NULL,
    ch_path VARCHAR(255) NOT NULL,
    webtoon INT NOT NULL,
    update_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    CONSTRAINT fk_ch_web FOREIGN KEY(webtoon) REFERENCES webtoon
);

-- INSERT DATA
INSERT INTO category VALUES (1,'Action'),(2,'Adulte'),(3,'Arts Martiaux'),(4,'Aventure'),(5,'Biographie'),(6,'Combat'),(7,'Comédie'),(8,'Cyberpunk'),(9,'Drame'),(10,'Famille'),(11,'Fantaisie'),(12,'Guerre'),(13,'Historique'),(14,'Horreur'),(15,'Isekai'),(16,'Josei'),(17,'Magie'),(18,'Musique'),(19,'Mystère'),(20,'Politique'),(21,'Post-apocalyptique'),(22,'Psycho'),(23,'Romance'),(24,'Sc-Fi'),(25,'School life'),(26,'Seinen'),(27,'Shojo'),(28,'Shonen'),(29,'Slice of Life'),(30,'Sport'),(31,'Steampunk'),(32,'Surnaturel'),(33,'Thriller'),(34,'Tragédie'),(35,'Webcomic');