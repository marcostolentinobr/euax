<?php require_once __DIR__ . '/config.php' ?>
<!doctype html>
<html lang="pt-br">


<header>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- base_url -->
    <base href="<?= URL ?>" />

    <!-- title -->
    <title><?= TITULO ?></title>
</header>

<body class="container">

    <!-- menu -->
    <strong>EUAX</strong> |
    <a href="<?= URL ?>">Início</a> |
    <a href="<?= URL ?>Projeto">Projetos</a> |
    <a href="<?= URL ?>Atividade">Atividades</a>

    <!-- conteudo -->
    <?php require_once RAIZ . '/modulos/View.php' ?>
</body>

</html>