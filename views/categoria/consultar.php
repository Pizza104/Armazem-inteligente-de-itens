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
                                <a href="?p=editar/categoria&id=<?= $mostrar['id'] ?>"
                                    class="btn btn-warning">
                                    <i class="i bi-pencil-square"></i>
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