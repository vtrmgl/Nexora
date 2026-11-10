<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'paginas/header.php' ?>

    <main>
        <h1 id="texto-indicador">Indicadores</h1>
        <h2 id="titulo-indicador-dashboard">Diário</h2>
        <div class="indicadores-dashboard">
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos realizados</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos enviados</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos entregues</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Renda (R$)</p>
                <p id="qtd-preco-metricas-dashboard">R$ XXX,XX</p>
            </div>
        </div>
        <h2 id="titulo-indicador-dashboard">Semanal</h2>
        <div class="indicadores-dashboard">
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos realizados</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos enviados</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos entregues</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Renda (R$)</p>
                <p id="qtd-preco-metricas-dashboard">R$ XXX,XX</p>
            </div>
        </div>
        <h2 id="titulo-indicador-dashboard">Mensal</h2>
        <div class="indicadores-dashboard">
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos realizados</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos enviados</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Pedidos entregues</p>
                <p id="qtd-preco-metricas-dashboard">XX</p>
            </div>
            <div class="linha-vertical-metrica-dashboard"></div>
            <div class="metrica-indicador-dashboard">
                <p id="titulo-metrica-dashboard">Renda (R$)</p>
                <p id="qtd-preco-metricas-dashboard">R$ XXX,XX</p>
            </div>
        </div>
        <h1>Pedidos Recentes</h1>
        <div class="tabela-pedidos-dashboard">
            <ul>
                <li class="li-dobra-pedidos-dashboard-esquerdo">Pedido</li>
                <li>#XXX</li>
                <li>#XXX</li>
                <li>#XXX</li>
                <li>#XXX</li>
                <li>#XXX</li>
            </ul>
            <ul>
                <li>Nome</li>
                <li>Luiz</li>
                <li>João</li>
                <li>Maria</li>
                <li>Thiago</li>
                <li>José</li>
            </ul>
            <ul>
                <li>Produto</li>
                <li>Mouse</li>
                <li>Teclado</li>
                <li>Monitor</li>
                <li>Processador</li>
                <li>Mousepad</li>
            </ul>
            <ul>
                <li>Preço</li>
                <li>R$ 100,00</li>
                <li>R$ 100,00</li>
                <li>R$ 700,00</li>
                <li>R$ 900,00</li>
                <li>R$ 50,00</li>
            </ul>
            <ul>
                <li class="li-dobra-pedidos-dashboard-direito">Status</li>
                <li>Enviado</li>
                <li>Enviado</li>
                <li>Preparando</li>
                <li>Enviado</li>
                <li>Entregue</li>
            </ul>
        </div>
    </main>

    <?php include 'paginas/footer.php' ?>
    
</body>
</html>