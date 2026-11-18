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