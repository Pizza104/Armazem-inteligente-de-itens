<?php

declare(strict_types=1);

require_once '../model/Conn.php';
require_once '../model/Categoria.php';


class CategoriaDAO
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = new Conn();
    }

    private function texto(string $texto): string
    {
        return mb_strtoupper(trim($texto));
    }

    public function salvar(Categoria $categoria): bool
    {
        if ($categoria->getId() == null) {

            $sql = "INSERT INTO categoria
                    (nome,informacoes)
                    VALUES
                    (?,?)";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(1, $this->texto($categoria->getNome()));
            $stmt->bindValue(2, $this->texto($categoria->getInformacoes()));
        } else {

            $sql = "UPDATE categoria
                       SET nome=?,
                           informacoes=?
                     WHERE id=?";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(1, $this->texto($categoria->getNome()));
            $stmt->bindValue(2, $this->texto($categoria->getInformacoes()));
            $stmt->bindValue(3, $categoria->getId());
        }

        return $stmt->execute();
    }

    public function listar(): array
    {
        $stmt = $this->conn->query(
            "SELECT *
               FROM categoria
           ORDER BY nome"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?Categoria
    {
        $stmt = $this->conn->prepare(
            "SELECT *
               FROM categoria
              WHERE id=?"
        );

        $stmt->bindValue(1, $id);

        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return null;
        }

        $categoria = new Categoria();

        $categoria->setId((int)$dados['id']);
        $categoria->setNome($dados["nome"]);
        $categoria->setInformacoes($dados["informacoes"]);

        return $categoria;
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM categoria
             WHERE id=?"
        );

        $stmt->bindValue(1, $id);

        return $stmt->execute();
    }

    public function pesquisar(string $campo, string $valor): array
    {
        try {

            $camposPermitidos = [
                "nome",
                "informacoes"
            ];

            if (!in_array($campo, $camposPermitidos, true)) {
                return [];
            }


            if ($campo == "nome") {

                $sql = "SELECT *
                      FROM categoria
                     WHERE nome LIKE ?
                  ORDER BY nome ASC";

                $valorPesquisa = mb_strtoupper($valor) . "%";
            } else if ($campo == "informacoes") {

                $sql = "SELECT *
                      FROM categoria
                     WHERE informacoes LIKE ?
                  ORDER BY nome ASC";

                $valorPesquisa = "%" . mb_strtoupper($valor) . "%";
            } else {
                $sql = "";
                $valorPesquisa = "";
            }


            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(
                1,
                $valorPesquisa,
                PDO::PARAM_STR
            );

            $stmt->execute();


            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
