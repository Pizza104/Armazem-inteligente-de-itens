<?php
declare(strict_types=1);
require_once __DIR__ . '/../model/Conn.php';
require_once __DIR__ . '/../model/Cliente.php';

class ClienteDAO
{
    private PDO $conn;
    public function __construct() { $this->conn = new Conn(); }
    
    public function salvar(Cliente $cliente): bool
    {
        if ($cliente->getId() === null) {
            $stmt = $this->conn->prepare('INSERT INTO cliente (nome, email, telefone) VALUES (?, ?, ?)');
            return $stmt->execute([$cliente->getNome(), $cliente->getEmail(), $cliente->getTelefone()]);
        }
        $stmt = $this->conn->prepare('UPDATE cliente SET nome = ?, email = ?, telefone = ? WHERE id = ?');
        return $stmt->execute([$cliente->getNome(), $cliente->getEmail(), $cliente->getTelefone(), $cliente->getId()]);
    }

    public function listar(): array
    {
        return $this->conn->query('SELECT id, nome, email, telefone FROM cliente ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?Cliente
    {
        $stmt = $this->conn->prepare('SELECT id, nome, email, telefone FROM cliente WHERE id = ?');
        $stmt->execute([$id]);
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$d) return null;
        return (new Cliente())->setId((int)$d['id'])->setNome($d['nome'])->setEmail($d['email'])->setTelefone($d['telefone']);
    }

    public function pesquisar(string $nome): array
    {
        $stmt = $this->conn->prepare('SELECT id, nome, email, telefone FROM cliente WHERE nome LIKE ? ORDER BY nome');
        $stmt->execute(['%' . $nome . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
