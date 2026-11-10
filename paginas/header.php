<header>
    <div class="topo">
        <a href="inicio.php" class="logo">
            <img src="img/logo-completa-nexora.svg" alt="logo-Nexora">
        </a>
        <div class="pesquisa">
            <input type="search" id="barra-pesquisa" placeholder="Pesquisa">
            <div class="pesquisa-dropdown">
                <div class="secao-pesquisa">
                    <h3>Visto recentemente</h3>
                    <ul>
                        <li><a href="#">Mouse</a></li>
                        <li><a href="#">Monitor</a></li>
                        <li><a href="#">Gabinete</a></li>
                    </ul>
                </div>
                <hr>
                <div class="secao-pesquisa">
                    <h3>Categorias</h3>
                    <div class="colunas-categorias-pesquisa">
                        <ul>
                            <li><a href="produtos.php#novidade">Novidades</a></li>
                            <li><a href="produtos.php#ofertas">Ofertas</a></li>
                            <li><a href="produtos.php#processadores">Processadores</a></li>
                        </ul>
                        <ul>
                            <li><a href="produtos.php#placas-de-video">Placas de Vídeo</a></li>
                            <li><a href="produtos.php#memorias-ram">Memorias RAM</a></li>
                            <li><a href="produtos.php#ssds">SSDs</a></li>
                        </ul>
                        <ul>
                            <li><a href="produtos.php#coolers">Coolers</a></li>
                            <li><a href="produtos.php#placas-mae">Placas Mãe</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!--
        Logout e perfil só devem aparecer se a sessão estiver ativa, dashboard só aparece se o usuário tiver uma loja.
        Login só aparece se a sessão não estiver ativa
            -->

        <nav>
            <a href="inicio.php" title="Início">
                <img src="img/home.svg" alt="Início" class="icone-cabecalho">
            </a>
            <a href="perfil.php" title="Perfil">
                <img src="img/person.svg" alt="Perfil" class="icone-cabecalho">
            </a>
            <a href="dashboard.php" title="Dashboard">
                <img src="img/dashboard.svg" alt="Dashboard" class="icone-cabecalho">
            </a>
            <a href="login.php" title="Login">
                <img src="img/login.svg" alt="Login" class="icone-cabecalho">
            </a>
            <a href="index.php" title="Logout">
                <img src="img/logout.svg" alt="Logout" class="icone-cabecalho">
            </a>
            <a href="sobre.php" title="Sobre">
                <img src="img/info.svg" alt="Sobre" class="icone-cabecalho">
            </a>
            <a href="contato.php" title="Contato">
                <img src="img/envelope.svg" alt="Contato" class="icone-cabecalho">
            </a>
        </nav>
    </div>
</header>