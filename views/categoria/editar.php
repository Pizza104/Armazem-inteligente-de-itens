<?php

//Para mostrar erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../controller/CategoriaController.php';

$controller = new CategoriaController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo '<div class="alert alert-danger">ID não informado.</div>';
    exit;
}

$categoria = $controller->buscarPorId($id);

if (!$categoria) {
    echo '<div class="alert alert-danger">Categoria não encontrada.</div>';
    exit;
}

if (isset($_POST['btnalterar'])) {

    if ($controller->alterar()) {

        echo '
            <div class="alert alert-success">
                Categoria alterada com sucesso!
            </div>
            <script>
            setTimeout(function() {
                window.location.href = "?p=categorias";
            }, 1500);
        </script>
        ';

        $categoria = $controller->buscarPorId($id);
    } else {

        echo '
            <div class="alert alert-danger">
                Erro ao alterar categoria.
            </div>
        ';
    }
}
?>

<div class="card">

    <div class="card-header">
        Editar Categoria
    </div>

    <div class="card-body">

        <form method="post">

            <input
                type="hidden"
                name="txtid"
                value="<?= $categoria->getId(); ?>">

            <div class="mb-3">
                <label class="form-label">Nome</label>

                <input
                    type="text"
                    name="txtnome"
                    class="form-control"
                    value="<?= $categoria->getNome(); ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Informações</label>

                <textarea
                    name="txtinformacoes"
                    class="form-control"
                    rows="4"><?= $categoria->getInformacoes(); ?></textarea>
            </div>

            <button
                type="submit"
                name="btnalterar"
                class="btn btn-primary">
                Atualizar
            </button>

        </form>

    </div>

</div>