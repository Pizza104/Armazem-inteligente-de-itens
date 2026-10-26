<?php
declare(strict_types=1);
require_once __DIR__ . '/../dao/FornecedorDAO.php';

class FornecedorController
{
    private FornecedorDAO $dao;
    public function __construct() { $this->dao = new FornecedorDAO(); }
    public function salvar(): bool
    {
        $fornecedor = new Fornecedor();
        $fornecedor->setRazaoSocial(filter_input(INPUT_POST, 'razao_social') ?? '');
        $fornecedor->setEmail(filter_input(INPUT_POST, 'email') ?? '');
        $fornecedor->setTelefone(filter_input(INPUT_POST, 'telefone') ?? '');
        return $this->dao->salvar($fornecedor);
    }
    public function alterar(): bool
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) return false;
        $fornecedor = (new Fornecedor())->setId($id)->setRazaoSocial(filter_input(INPUT_POST, 'razao_social') ?? '')
            ->setEmail(filter_input(INPUT_POST, 'email') ?? '')->setTelefone(filter_input(INPUT_POST, 'telefone') ?? '');
        return $this->dao->salvar($fornecedor);
    }
    public function listar(): array { return $this->dao->listar(); }
    public function buscarPorId(int $id): ?Fornecedor { return $this->dao->buscarPorId($id); }
    public function pesquisar(string $razaoSocial): array { return $this->dao->pesquisar($razaoSocial); }
}
