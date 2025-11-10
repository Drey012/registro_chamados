<?php
/**
 * Arquivo de Conexão com o Banco de Dados MySQL
 * Sistema de Chamados - Suporte TI
 */

// Definir o charset para UTF-8
header('Content-Type: text/html; charset=utf-8');

// Configurações do banco de dados
define('DB_HOST', 'localhost');      // Host do banco de dados
define('DB_USER', 'root');           // Usuário do banco de dados
define('DB_PASS', '');               // Senha do banco de dados
define('DB_NAME', 'sistema_chamados'); // Nome do banco de dados
define('DB_CHARSET', 'utf8mb4');     // Charset da conexão

/**
 * Classe para gerenciar a conexão com o banco de dados
 */
class Database {
    private static $conexao = null;
    
    /**
     * Obtém a conexão com o banco de dados (Singleton)
     * @return mysqli Objeto de conexão MySQLi
     */
    public static function getConexao() {
        if (self::$conexao === null) {
            try {
                // Criar conexão
                self::$conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                // Verificar conexão
                if (self::$conexao->connect_error) {
                    throw new Exception('Erro de conexão: ' . self::$conexao->connect_error);
                }
                
                // Definir charset
                if (!self::$conexao->set_charset(DB_CHARSET)) {
                    throw new Exception('Erro ao definir charset: ' . self::$conexao->error);
                }
                
            } catch (Exception $e) {
                error_log($e->getMessage());
                die('Erro ao conectar com o banco de dados. Verifique as configurações.');
            }
        }
        
        return self::$conexao;
    }
    
    /**
     * Fecha a conexão com o banco de dados
     */
    public static function fecharConexao() {
        if (self::$conexao !== null) {
            self::$conexao->close();
            self::$conexao = null;
        }
    }
    
    /**
     * Escapa strings para prevenir SQL Injection
     * @param string $string String a ser escapada
     * @return string String escapada
     */
    public static function escapar($string) {
        $conn = self::getConexao();
        return $conn->real_escape_string($string);
    }
}

/**
 * Função auxiliar para obter a conexão
 * @return mysqli
 */
function getDB() {
    return Database::getConexao();
}

// Testar conexão (remova ou comente em produção)
// try {
//     $conn = Database::getConexao();
//     echo "Conexão estabelecida com sucesso!";
// } catch (Exception $e) {
//     echo "Erro: " . $e->getMessage();
// }
?>