<?php
declare(strict_types=1);
require_once __DIR__ . '/../dao/ClienteDAO.php';

class ClienteController
{
    private ClienteDAO $dao;
    public function __construct() { $this->dao = new ClienteDAO(); }
    public function salvar(): bool
    {
        $cliente = new Cliente();
        $cliente->setNome(filter_input(INPUT_POST, 'nome') ?? '');
        $cliente->setEmail(filter_input(INPUT_POST, 'email') ?? '');
        $cliente->setTelefone(filter_input(INPUT_POST, 'telefone') ?? '');
        return $this->dao->salvar($cliente);
    }
    public function alterar(): bool
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) return false;
        $cliente = (new Cliente())->setId($id)->setNome(filter_input(INPUT_POST, 'nome') ?? '')
            ->setEmail(filter_input(INPUT_POST, 'email') ?? '')->setTelefone(filter_input(INPUT_POST, 'telefone') ?? '');
        return $this->dao->salvar($cliente);
    }
    public function listar(): array { return $this->dao->listar(); }
    public function buscarPorId(int $id): ?Cliente { return $this->dao->buscarPorId($id); }
    public function pesquisar(string $nome): array { return $this->dao->pesquisar($nome); }
}
