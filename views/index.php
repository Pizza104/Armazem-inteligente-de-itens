<?php
include_once 'cabecalho.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Backend PHP</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="../assets/icone.png" type="image/png">
</head>

<body>

    <?php include_once 'navbar.php'; ?>

    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12">
                <?php
                // Captura e sanitiza o parâmetro
                $pagina = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_SPECIAL_CHARS);

                // Define páginas permitidas (lista branca)
                include_once 'rotas.php';

                // Página padrão
                if (empty($pagina)) {
                    $pagina = 'index';
                }

                // Verifica se está na lista branca
                if (array_key_exists($pagina, $paginasPermitidas)) {
                    include_once $paginasPermitidas[$pagina];
                } else {
                    http_response_code(404);
                ?>
                    <div class="alert alert-danger" role="alert">
                        Erro 404 - Página não encontrada!
                    </div>
                <?php
                }
                ?>
            </div>
        </div><!--fim linha conteúdo-->

    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>