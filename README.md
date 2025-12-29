# AnaliseContrato

Sistema de análise de contratos com páginas de cadastro, login e dashboard moderno.

## Requisitos

- Node.js 18+
- MySQL

## Configuração do banco

Crie o banco e as tabelas abaixo:

```sql
CREATE DATABASE itaweb64_analisecontrato;
USE itaweb64_analisecontrato;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  whatsapp VARCHAR(30) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contract_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  nome VARCHAR(120) NOT NULL,
  whatsapp VARCHAR(30) NOT NULL,
  file_url TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_contract_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

Crie um arquivo `.env` com as credenciais:

```
MYSQL_HOST=localhost
MYSQL_USER=root
MYSQL_PASSWORD=
MYSQL_DATABASE=itaweb64_analisecontrato
PORT=3000
```

## Rodar o projeto

```bash
npm install
npm run dev
```

Acesse `http://localhost:3000`.

URL de produção: `https://analisecontrato.itadigital.com.br`.
