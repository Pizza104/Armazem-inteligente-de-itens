<div class="col-sm-12 mb-4">

    <div class="card shadow mb-4">
        <div class="table-responsive-sm mt-4">
            <h3 class="ml-3">
                Pesquisar Categorias
                <a class="btn btn-primary float-right mb-3 mr-3" href="?p=categorias">
                    <i class="bi bi-list-task"></i>
                </a>
            </h3>

            <!-- Filtros -->
            <form id="form-filtro" class="form-inline mb-3 ml-3" method="post">
                <input type="text" class="form-control mr-2" name="nome" placeholder="Nome">
                <input type="text" class="form-control mr-2" name="informacoes" placeholder="Informações">
                <input type="submit" class="btn btn-primary" name="enviar" value="Pesquisar">
                <button type="reset" id="btn-reset" class="btn btn-secondary ml-2">Limpar</button>
            </form>

            <table class="table table-striped table-sm" id="tabela-estados">
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

                    if (filter_input(INPUT_POST, 'enviar')) {


                        $nome = trim(filter_input(INPUT_POST, 'nome') ?? '');

                        $informacoes = trim(
                            filter_input(INPUT_POST, 'informacoes') ?? ''
                        );


                        if ($nome == "" && $informacoes == "") {

                            echo "
        <script>
            alert('Preencha ao menos um campo para pesquisar');
        </script>";
                        } else {


                            require_once "../controller/CategoriaController.php";


                            $controller = new CategoriaController();


                            if ($nome != "") {

                                $dados = $controller->pesquisar(
                                    "nome",
                                    $nome
                                );
                            } else if ($informacoes != "") {

                                $dados = $controller->pesquisar(
                                    "informacoes",
                                    $informacoes
                                );
                            } else {
                                $dados = "";
                            }

                            foreach ($dados as $mostrar) {

                    ?>

                                <tr>

                                    <td><?= $mostrar['id']; ?></td>

                                    <td><?= $mostrar['nome']; ?></td>

                                    <td><?= $mostrar['informacoes']; ?></td>

                                    <td>

                                        <a href="?p=excluir/categoria&id=<?= $mostrar['id']; ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Tem certeza que deseja excluir?')">

                                            <i class="bi bi-x-circle"></i>

                                        </a>

                                    </td>

                                </tr>


                    <?php

                            }
                        }
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>