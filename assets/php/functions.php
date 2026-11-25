<?php
/**
 * funcoes.php
 *
 * Funções auxiliares utilizadas nos componentes de interface do sistema.
 *
 * @author Vitor Miguel
 */

/**
 * Inicia a sessão PHP apenas se nenhuma sessão estiver ativa no momento.
 *
 * Evita avisos de sessão duplicada quando múltiplos arquivos são incluídos na mesma página.
 *
 * @return void
 */
function iniciarSessaoSegura(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Verifica se existe um usuário autenticado na sessão atual.
 *
 * @return bool True se o usuário estiver logado, false caso contrário.
 */
function usuarioEstaLogado(): bool
{
    iniciarSessaoSegura();
    return isset($_SESSION['usuario']);
}

/**
 * Redireciona o navegador para outra página e encerra a execução do script.
 *
 * O exit garante que nenhum código HTML ou PHP abaixo do redirecionamento
 * seja processado ou enviado antes da troca de página.
 *
 * @param string $pagina Caminho ou nome do arquivo de destino ('inicio.php').
 * @return void
 */
function redirecionar(string $pagina): void
{
    header("Location: {$pagina}");
    exit;
}

function exigirLogin(): void
{
    iniciarSessaoSegura();
    if (!usuarioEstaLogado()) {
        redirecionar('login.php');
    }
}

function usuarioPodeAcessarDashboard(): bool
{
    iniciarSessaoSegura();
    
    if (!usuarioEstaLogado()) {
        return false;
    }
    $tipo = $_SESSION['usuario']['tipo'] ?? 'Usuário';
    $idLoja = $_SESSION['usuario']['id_loja'] ?? null;

    return ($tipo === 'Administrador' || !empty($idLoja));
}

function gerarTokenCsrf(): string
{
    iniciarSessaoSegura();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validarTokenCsrf(?string $token): bool
{
    iniciarSessaoSegura();
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function e(?string $valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}