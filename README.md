# AMTracker - Rastreador Pessoal de Animes e Mangás

Este é um projeto web desenvolvido como avaliação para a disciplina de Tópicos Especiais de Desenvolvimento de Sistemas I, utilizando PHP, PostgreSQL e Docker. A aplicação permite ao usuário catalogar e rastrear o progresso de animes e mangás.

## Tecnologias Utilizadas
- PHP (Procedural)
- PostgreSQL
- Apache
- Docker & Docker Compose
- HTML5, CSS3 (Grid/Flexbox), JavaScript

## Como Executar o Projeto Localmente

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/marcojobim/AMTracker.git
    cd AMTrakcer
    ```

2.  **Configure o ambiente:**
    - Crie uma cópia do arquivo `.env.example` e renomeie-a para `.env`.
    - Preencha as variáveis de ambiente no arquivo `.env` (para o ambiente local, os valores padrão devem funcionar).

3.  **Instale as dependências do PHP:**
    ```bash
    composer install
    ```

4.  **Inicie os containers Docker:**
    ```bash
    docker-compose up -d --build
    ```

5.  **Acesse a aplicação:**
    Abra seu navegador e acesse `http://localhost:8080`.