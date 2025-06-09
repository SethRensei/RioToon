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

