<?php

include_once 'Conn.php';

//Extensão PHP Getters & Setters

class Categoria
{
    private $id;
    private $nome;
    private $informacoes;
    private $conn;
    private $tabela = "categoria";

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getInformacoes()
    {
        return $this->informacoes;
    }

    public function setInformacoes($informacoes)
    {
        $this->informacoes = $informacoes;
        return $this;
    }

    public function salvar()
    {
        try {
            $this->conn = new Conn();
            $sql = "CALL salvar_categoria(?, ?, ?)";
            $executar = $this->conn->prepare($sql);
            $executar->bindValue(1, $this->id);
            $executar->bindValue(2, mb_strtoupper($this->nome));
            $executar->bindValue(3, mb_strtoupper($this->informacoes));
            return $executar->execute() == 1 ? true : false;
        } catch (PDOException $erro) {
            echo $erro->getMessage();
        }
    }

    public function listar($var_id)
    {
        try {
            $this->conn = new Conn();
            $sql = "CALL listar_categoria(?)";
            $executar = $this->conn->prepare($sql);
            $executar->bindValue(1, $var_id);
            return $executar->execute() == 1 ? $executar->fetchAll() : false;
        } catch (PDOException $erro) {
            echo $erro->getMessage();
        }
    }

    public function excluir()
    {
        try {
            $this->conn = new Conn();
            $sql = "DELETE FROM {$this->tabela} WHERE id = ?";
            $executar = $this->conn->prepare($sql);
            $executar->bindValue(1, $this->id);
            return $executar->execute() == 1 ? true : false;
        } catch (PDOException $erro) {
            echo $erro->getMessage();
        }
    }

    public function inserir()
    {
        try {
            $this->conn = new Conn();
            $sql = "INSERT INTO {$this->tabela} VALUES (?, ?, ?)";
            $executar = $this->conn->prepare($sql);
            $executar->bindValue(1, $this->id);
            $executar->bindValue(2, mb_strtoupper($this->nome));
            $executar->bindValue(3, mb_strtoupper($this->informacoes));
            return $executar->execute() == 1 ? true : false;
        } catch (PDOException $erro) {
            echo $erro->getMessage();
        }
    }

    public function alterar()
    {
        try {
            $this->conn = new Conn();
            $sql = "UPDATE {$this->tabela} 
                    SET nome = ?, informacoes = ? 
                    WHERE id = ?";
            $executar = $this->conn->prepare($sql);
            $executar->bindValue(1, mb_strtoupper($this->nome));
            $executar->bindValue(2, mb_strtoupper($this->informacoes));
            $executar->bindValue(3, $this->id);
            return $executar->execute() == 1 ? true : false;
        } catch (PDOException $erro) {
            echo $erro->getMessage();
        }
    }

    public function listarSemProcedure()
    {
        try {
            $this->conn = new Conn();
            $sql = "SELECT * FROM {$this->tabela} ORDER BY nome";
            $executar = $this->conn->prepare($sql);
            return $executar->execute() == 1 ? $executar->fetchAll() : false;
        } catch (PDOException $erro) {
            echo $erro->getMessage();
        }
    }

    public function consultarPorID()
    {
        try {
            $this->conn = new Conn();
            $sql = "SELECT * FROM {$this->tabela} WHERE id = ?";
            $executar = $this->conn->prepare($sql);
            $executar->bindValue(1, $this->id);
            return $executar->execute() == 1 ? $executar->fetch() : false;
        } catch (PDOException $erro) {
            echo $erro->getMessage();
        }
    }

    public function crudPhp($opcao)
    {
        try {
            $this->conn = new Conn();
            switch ($opcao) {
                case 'I':
                    $sql = "INSERT INTO {$this->tabela}
                        (nome, informacoes)
                        VALUES (?, ?)";
                    $executar = $this->conn->prepare($sql);
                    $executar->bindValue(1, mb_strtoupper($this->nome));
                    $executar->bindValue(2, mb_strtoupper($this->informacoes));
                    break;

                case 'A':
                    $sql = "UPDATE {$this->tabela}
                           SET nome = ?,
                               informacoes = ?
                         WHERE id = ?";
                    $executar = $this->conn->prepare($sql);
                    $executar->bindValue(1, mb_strtoupper($this->nome));
                    $executar->bindValue(2, mb_strtoupper($this->informacoes));
                    $executar->bindValue(3, $this->id);
                    break;

                case 'E':
                    $sql = "DELETE FROM {$this->tabela}
                        WHERE id = ?";
                    $executar = $this->conn->prepare($sql);
                    $executar->bindValue(1, $this->id);
                    break;

                default:
                    return false;
            }

            return $executar->execute() == 1 ? true : false;
        } catch (PDOException $exc) {

            echo $exc->getMessage();
        }
    }

    public function pesquisar($filtros)
    {
        $where = "";
        $params = "";
        $this->conn = new Conn();

        if (!empty($filtros[0] == 'nome')) {
            $where = "nome LIKE ?";
            $params = mb_strtoupper($filtros[1]) . "%";
        }
        if (!empty($filtros[0] == 'informacoes')) {
            $where = "informacoes LIKE ?";
            $params = "%" . mb_strtoupper($filtros[1]) . "%";
        }
        $sql = "SELECT * FROM {$this->tabela} WHERE $where ORDER BY nome ASC";

        $executar = $this->conn->prepare($sql);
        $executar->bindValue(1, $params);

        return $executar->execute() == 1 ? $executar->fetchAll() : false;
    }
}
