<?php

require_once '../controller/CategoriaController.php';

if (isset($_POST['btnsalvar'])) {

    $controller = new CategoriaController();

    if ($controller->salvar()) {
?>
        <div class="alert alert-success">
            Categoria cadastrada com sucesso!
        </div>
        <script>
            setTimeout(function() {
                window.location.href = '?p=categorias';
            }, 1500);
        </script>
    <?php
    } else {
    ?>
        <div class="alert alert-danger">
            Erro ao cadastrar categoria.
        </div>
<?php
    }
}
?>

<div class="card">

    <div class="card-header">
        Nova Categoria
    </div>

    <div class="card-body">

        <form method="post">

            <div class="mb-3">
                <label class="form-label">Nome</label>

                <input
                    type="text"
                    name="txtnome"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Informações</label>

                <textarea
                    name="txtinformacoes"
                    class="form-control"
                    rows="4"></textarea>
            </div>

            <button
                type="submit"
                name="btnsalvar"
                class="btn btn-success">
                Salvar
            </button>

        </form>

    </div>

</div>