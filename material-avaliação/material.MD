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

        $categoria->setId($dados["id"]);
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
            } else {

                $sql = "SELECT *
                      FROM categoria
                     WHERE informacoes LIKE ?
                  ORDER BY nome ASC";

                $valorPesquisa = "%" . mb_strtoupper($valor) . "%";
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


########################################

<?php

//o php irá exigir a tipificação dos atributos e métodos
declare(strict_types=1);

class Categoria
{
    // a interrogação indica que pode ser nulo
    private ?int $id = null;
    private string $nome = "";
    private string $informacoes = "";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = trim($nome);
        return $this;
    }

    public function getInformacoes(): string
    {
        return $this->informacoes;
    }

    public function setInformacoes(string $informacoes): self
    {
        $this->informacoes = trim($informacoes);
        return $this;
    }
}

#########################################################
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
        $this->categoria->setNome(
            filter_input(INPUT_POST, "txtnome")
        );
        $this->categoria->setInformacoes(
            filter_input(INPUT_POST, "txtinformacoes")
        );

        return  $this->dao->salvar($this->categoria);
    }

    public function alterar(): bool
    {
        $this->categoria->setId(
            (int) filter_input(INPUT_POST, "txtid")
        );

        $this->categoria->setNome(
            filter_input(INPUT_POST, "txtnome")
        );

        $this->categoria->setInformacoes(
            filter_input(INPUT_POST, "txtinformacoes")
        );

        return  $this->dao->salvar($this->categoria);
    }

    public function excluir(int $id): bool
    {
        return  $this->dao->excluir($id);
    }

    public function listar(): array
    {
        return  $this->dao->listar();
    }

    public function buscarPorId(int $id): ?Categoria
    {
        return  $this->dao->buscarPorId($id);
    }

    public function pesquisar(string $campo, string $valor): array
    {

        return  $this->dao->pesquisar(
            $campo,
            $valor
        );
    }
}

###############################################################

    <h3 class="mt-3 text-primary">
        Categoria
    </h3>

    <div class="card shadow mt-3"><!-- acrescentei um card com sombra aqui tbm -->
        <form method="post" name="formsalvar" id="formSalvar" class="m-3" enctype="multipart/form-data">

            <div class="form-group row">
                <label for="txtnome" class="col-sm-2 col-form-label">
                    Nome
                </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="txtnome" name="txtnome" placeholder="Categoria"
                        value="">
                </div>
            </div>
            <div class="form-group row">
                <label for="txtinformacoes" class="col-sm-2 col-form-label">
                    Informações
                </label>
                <div class="col-sm-10">
                    <textarea name="txtinformacoes" id="txtinformacoes" rows="3" placeholder="Informações aqui" class="form-control"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-10">
                    <input type="submit"
                        class="btn btn-primary"
                        name="btnsalvar"
                        value="Cadastrar">
                </div>
                <!-- faltou um link aqui-->
                <a href="?p=categorias" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>

    <?php

    if (filter_input(INPUT_POST, 'btnsalvar')) {

        require_once '../controller/CategoriaController.php';
        $controller = new CategoriaController();
        if ($controller->salvar()) {
    ?>
            <div class="alert alert-primary mt-3" role="alert">
                Categoria - cadastro efetuado com sucesso.
            </div>

            <meta http-equiv="refresh" content="0.2;URL=?p=categorias">

        <?php
        } else {
        ?>
            <div class="alert alert-danger mt-3" role="alert">
                Categoria - erro ao cadastrar.
            </div>

            <meta http-equiv="refresh" content="0.2;URL=?p=categorias">

    <?php
        }
    }

    ##########################################################

    <div class="col-sm-12 mb-4">

    <div class="card shadow mb-4">
        <!-- striped é para zebrar as linhas, cada uma com uma cor-->
        <div class="table-responsive-sm mt-4">
            <h3 class="ml-3">
                Listar Categorias
                <a class="btn btn-success float-right mb-3 mr-3" href="?p=add/categoria"><i class="bi bi-database-fill-add"></i></a>
                <a class="btn btn-primary float-right mb-3 mr-3" href="?p=pesquisar/categoria"><i class="bi bi-search"></i></a>
            </h3>

            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Informações</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    require_once '../controller/CategoriaController.php';
                    

                    $controller = new CategoriaController();

                    $dados = $controller->listar();

                    foreach ($dados as $mostrar) {
                    ?>
                        <tr>
                            <td><?= $mostrar['id'] ?></td>
                            <td><?= $mostrar['nome'] ?></td>
                            <td><?= $mostrar['informacoes'] ?></td>
                            <td>
                                <a href="?p=excluir/categoria&id=<?= $mostrar['id'] ?>"
                                    class="btn btn-danger"
                                    title="Excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir?')">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>