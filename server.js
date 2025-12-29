import express from "express";
import path from "path";
import { fileURLToPath } from "url";
import dotenv from "dotenv";
import crypto from "crypto";
import mysql from "mysql2/promise";

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 3000;
const HASH_ITERATIONS = 120000;

const pool = mysql.createPool({
  host: process.env.MYSQL_HOST || "localhost",
  user: process.env.MYSQL_USER || "root",
  password: process.env.MYSQL_PASSWORD || "",
  database: process.env.MYSQL_DATABASE || "analise_contrato",
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});

const hashPassword = (password) => {
  const salt = crypto.randomBytes(16).toString("hex");
  const hash = crypto
    .pbkdf2Sync(password, salt, HASH_ITERATIONS, 64, "sha512")
    .toString("hex");
  return `${salt}:${hash}`;
};

const verifyPassword = (password, storedHash) => {
  if (!storedHash || !storedHash.includes(":")) {
    return false;
  }
  const [salt, hash] = storedHash.split(":");
  const computedHash = crypto
    .pbkdf2Sync(password, salt, HASH_ITERATIONS, 64, "sha512")
    .toString("hex");
  return crypto.timingSafeEqual(
    Buffer.from(hash, "hex"),
    Buffer.from(computedHash, "hex")
  );
};

app.use(express.json());
app.use(express.static(path.join(__dirname, "public")));

app.post("/api/register", async (req, res) => {
  const { name, email, password, whatsapp } = req.body || {};

  if (!name || !email || !password || !whatsapp) {
    return res.status(400).json({ message: "Preencha todos os campos." });
  }

  try {
    const [existing] = await pool.execute(
      "SELECT id FROM users WHERE email = ? LIMIT 1",
      [email]
    );

    if (existing.length) {
      return res.status(409).json({ message: "E-mail já cadastrado." });
    }

    const hashedPassword = hashPassword(password);

    const [result] = await pool.execute(
      "INSERT INTO users (name, email, password, whatsapp) VALUES (?, ?, ?, ?)",
      [name, email, hashedPassword, whatsapp]
    );

    return res.status(201).json({ id: result.insertId, name, email });
  } catch (error) {
    console.error("Erro no cadastro:", error);
    return res.status(500).json({ message: "Erro ao cadastrar usuário." });
  }
});

app.post("/api/login", async (req, res) => {
  const { email, password } = req.body || {};

  if (!email || !password) {
    return res.status(400).json({ message: "Informe e-mail e senha." });
  }

  try {
    const [rows] = await pool.execute(
      "SELECT id, name, password FROM users WHERE email = ? LIMIT 1",
      [email]
    );

    if (!rows.length) {
      return res.status(401).json({ message: "Credenciais inválidas." });
    }

    const user = rows[0];
    const isValid = verifyPassword(password, user.password);

    if (!isValid) {
      return res.status(401).json({ message: "Credenciais inválidas." });
    }

    return res.json({ id: user.id, name: user.name });
  } catch (error) {
    console.error("Erro no login:", error);
    return res.status(500).json({ message: "Erro ao autenticar usuário." });
  }
});

app.post("/api/contract", async (req, res) => {
  const { nome, whatsapp, file_url, userId } = req.body || {};

  if (!nome || !whatsapp || !file_url) {
    return res.status(400).json({ message: "Dados do contrato incompletos." });
  }

  try {
    const webhookResponse = await fetch(
      "https://n8n.itadigital.com.br/webhook/analise-contrato",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ nome, whatsapp, file_url }),
      }
    );

    if (!webhookResponse.ok) {
      const errorText = await webhookResponse.text();
      console.error("Falha no webhook:", errorText);
      return res.status(502).json({ message: "Falha ao enviar para o webhook." });
    }

    await pool.execute(
      "INSERT INTO contract_requests (user_id, nome, whatsapp, file_url) VALUES (?, ?, ?, ?)",
      [userId || null, nome, whatsapp, file_url]
    );

    return res.status(200).json({ message: "Contrato enviado com sucesso." });
  } catch (error) {
    console.error("Erro ao enviar contrato:", error);
    return res.status(500).json({ message: "Erro ao processar contrato." });
  }
});

app.get("/api/health", (req, res) => {
  res.json({ status: "ok" });
});

app.listen(PORT, () => {
  console.log(`Servidor rodando em http://localhost:${PORT}`);
});
