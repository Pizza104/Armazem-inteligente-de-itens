<?php
declare(strict_types=1);
require_once __DIR__ . '/../model/Conn.php';
require_once __DIR__ . '/../model/Fornecedor.php';

class FornecedorDAO
{
    private PDO $conn;
    public function __construct() { $this->conn = new Conn(); }

    public function salvar(Fornecedor $fornecedor): bool
    {
        if ($fornecedor->getId() === null) {
            $stmt = $this->conn->prepare('INSERT INTO fornecedor (razao_social, email, telefone) VALUES (?, ?, ?)');
            return $stmt->execute([$fornecedor->getRazaoSocial(), $fornecedor->getEmail(), $fornecedor->getTelefone()]);
        }
        $stmt = $this->conn->prepare('UPDATE fornecedor SET razao_social = ?, email = ?, telefone = ? WHERE id = ?');
        return $stmt->execute([$fornecedor->getRazaoSocial(), $fornecedor->getEmail(), $fornecedor->getTelefone(), $fornecedor->getId()]);
    }

    public function listar(): array
    {
        return $this->conn->query('SELECT id, razao_social, email, telefone FROM fornecedor ORDER BY razao_social')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?Fornecedor
    {
        $stmt = $this->conn->prepare('SELECT id, razao_social, email, telefone FROM fornecedor WHERE id = ?');
        $stmt->execute([$id]);
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$d) return null;
        return (new Fornecedor())->setId((int)$d['id'])->setRazaoSocial($d['razao_social'])->setEmail($d['email'])->setTelefone($d['telefone']);
    }

    public function pesquisar(string $razaoSocial): array
    {
        $stmt = $this->conn->prepare('SELECT id, razao_social, email, telefone FROM fornecedor WHERE razao_social LIKE ? ORDER BY razao_social');
        $stmt->execute(['%' . $razaoSocial . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
