\c AMTracker_db;

CREATE TYPE tipo_obra AS ENUM ('Anime', 'Mangá');
CREATE TYPE status_lancamento_obra AS ENUM ('Em lançamento', 'Completo', 'Hiato');
CREATE TYPE status_consumo_obra AS ENUM ('Interesse', 'Em andamento', 'Concluído', 'Abandonado');

CREATE TABLE generos(
    id SERIAL PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE obras(
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    tipo tipo_obra NOT NULL,
    status_lancamento status_lancamento_obra NOT NULL,
    imagem_capa VARCHAR(255),
    resenha_pessoal TEXT,
    nota_pessoal DECIMAL(3,1) CHECK (nota_pessoal >= 0 AND nota_pessoal <= 10),
    status_consumo status_consumo_obra NOT NULL,
    data_inicio DATE,
    data_fim DATE,
    total_episodios INT,
    progresso_episodios INT,
    total_capitulos INT,
    progresso_capitulos INT
);

CREATE TABLE genero_obra(
    obra_id INT NOT NULL REFERENCES obras(id) ON DELETE CASCADE,
    genero_id INT NOT NULL REFERENCES generos(id) ON DELETE CASCADE,
    PRIMARY KEY (obra_id, genero_id)
);

INSERT INTO generos(nome) VALUES
('Ação'),
('Aventura'),
('Comédia'),
('Drama'),
('Esportes'),
('Fantasia'),
('Ficção Científica'),
('Isekai'),
('Luta'),
('Mistério'),
('Psicológico'),
('Romance'),
('Slice of Life'),
('Sobrenatural'),
('Suspense');


