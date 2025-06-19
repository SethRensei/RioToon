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
    category INT NOT NULL,
    webtoon INT NOT NULL,
    CONSTRAINT fk_cat FOREIGN KEY (category) REFERENCES category(c_id)
    ON DELETE CASCADE,
    CONSTRAINT fk_web FOREIGN KEY (webtoon) REFERENCES webtoon(w_id)
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

CREATE TABLE "user"
(
    u_id SERIAL PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL,
    fullname VARCHAR(200),
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) NULL DEFAULT NULL,
    roles JSONB NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX user_pseudo_key ON "user" USING btree ("pseudo");

CREATE TABLE vote
(
    v_id SERIAL PRIMARY KEY,
    webtoon INT NOT NULL,
    "user" INT NOT NULL,
    vote INT NOT NULL,
    CONSTRAINT fk_web_vote 
        FOREIGN KEY (webtoon) REFERENCES webtoon(w_id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,        
    CONSTRAINT fk_user_vote
        FOREIGN KEY ("user") REFERENCES "user"(u_id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
)

-- INSERT DATA
INSERT INTO category(label) VALUES ('Action'),('Adulte'),('Arts Martiaux'),('Aventure'),('Biographie'),('Combat'),('Comédie'),('Cyberpunk'),('Drame'),('Famille'),('Fantaisie'),('Guerre'),('Historique'),('Horreur'),('Isekai'),('Josei'),('Magie'),('Musique'),('Mystère'),('Politique'),('Post-apocalyptique'),('Psycho'),('Romance'),('Sc-Fi'),('School life'),('Seinen'),('Shojo'),('Shonen'),('Slice of Life'),('Sport'),('Steampunk'),('Surnaturel'),('Thriller'),('Tragédie'),('Webcomic');