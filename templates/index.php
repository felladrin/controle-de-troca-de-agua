<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Troca de Água</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
    <link href="<?php echo ROOT ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo ROOT ?>/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <script src="<?php echo ROOT ?>/js/jquery.min.js"></script>
    <script src="?>/js/bootstrap.min.js"></script>
    <style>body { padding-top: 45px; font-family: 'Lato', serif; }</style>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand">Controle de Troca de Água</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="span4">
            <div class="row">
                <div class="span3">
                    <table class="table">
                        <h3>Placar</h3>
                        <tr><th>Nome</th><th>Trocas</th></tr>
                        <?php
                        foreach ($pessoas as $pessoa)
                            echo "<tr><td>$pessoa[nome]</td><td>$pessoa[trocas]</td></tr>";
                        ?>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="span4">
                    <h3>Registrar Troca</h3>
                    <form class="input-append" action="<?php echo ROOT ?>/registrar/troca" method="post">
                        <select name="id" class="span2">
                            <option>-- Selecione --</option>
                            <?php
                            foreach ($pessoas as $pessoa)
                                echo "<option value='$pessoa[id]'>$pessoa[nome]</option>";
                            ?>
                        </select>
                        <button class="btn" type="submit">Trocou</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="span4">
                    <h3>Sortear pessoa</h3>
                    <div class="input-append">
                        <input id="nome-sorteado" class="span2" type="text" placeholder="Nome" readonly>
                        <button id="botao-sortear" class="btn" type="button">Sortear</button>
                        <script>
                            $('#botao-sortear').click(function(){
                                $('#nome-sorteado').val("Sorteando...");
                                $.get('<?php echo ROOT ?>/sortear', function(sorteado){
                                    setTimeout(function() { $('#nome-sorteado').val(sorteado) }, 1000);
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="span4">
                    <h3>Adicionar pessoa</h3>
                    <form class="input-append" action="<?php echo ROOT ?>/registrar/pessoa" method="post">
                        <input class="span2" name="nome" type="text" placeholder="Nome">
                        <button class="btn" type="submit">Adicionar</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="span4">
                    <h3>Remover pessoa</h3>
                    <form class="input-append" action="<?php echo ROOT ?>/remover/pessoa" method="post">
                        <select name="id" class="span2">
                            <option>-- Selecione --</option>
                            <?php
                            foreach ($pessoas as $pessoa)
                                echo "<option value='$pessoa[id]'>$pessoa[nome]</option>";
                            ?>
                        </select>
                        <button class="btn" type="submit">Remover</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="span8">
            <table class="table table-bordered">
                <h3>Histórico</h3>
                <tr><th>Nome</th><th>Horário de Troca</th></tr>
                <?php
                foreach ($historico as $registro)
                {
                    $pessoa = $registro->pessoa['nome'];
                    $horario_troca = date('d/m/Y \à\s H:i', $registro['horario_troca']);
                    echo "<tr><td>$pessoa</td><td>$horario_troca</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>