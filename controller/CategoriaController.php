<?php

declare(strict_types=1);

require_once "../model/Categoria.php";
require_once "../dao/CategoriaDAO.php";

class CategoriaController
{
    private Categoria $categoria;
    private CategoriaDAO $dao;

    public function __construct()
    {
        $this->categoria = new Categoria();
        $this->dao = new CategoriaDAO();
    }

    public function salvar(): bool
    {
        $categoria = new Categoria();

        $categoria->setNome(
            filter_input(INPUT_POST, 'txtnome') ?? ''
        );

        $categoria->setInformacoes(
            filter_input(INPUT_POST, 'txtinformacoes') ?? ''
        );

        return $this->dao->salvar($categoria);
    }

    public function alterar(): bool
    {
        $categoria = new Categoria();

        $categoria->setId(
            filter_input(
                INPUT_POST,
                'txtid',
                FILTER_VALIDATE_INT
            )
        );

        $categoria->setNome(
            //usa o txtnome se nulo pega o vazio À direita
            filter_input(INPUT_POST, 'txtnome') ?? ''
        );

        $categoria->setInformacoes(
            filter_input(INPUT_POST, 'txtinformacoes') ?? ''
        );

        return $this->dao->salvar($categoria);
    }

    public function excluir(int $id): bool
    {
        return $this->dao->excluir($id);
    }

    public function listar(): array
    {
        return $this->dao->listar();
    }

    public function buscarPorId(int $id): ?Categoria
    {
        return $this->dao->buscarPorId($id);
    }

    public function pesquisar(string $campo, string $valor): array
    {
        return $this->dao->pesquisar(
            $campo,
            $valor
        );
    }
}
