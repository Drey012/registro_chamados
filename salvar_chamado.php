<?php
/**
 * Script para salvar novo chamado no banco de dados
 * Sistema de Chamados - Suporte TI
 */

// Incluir arquivo de conexão
require_once 'conexao.php';

// Definir cabeçalho para retornar JSON
header('Content-Type: application/json; charset=utf-8');

// Inicializar resposta
$response = array(
    'success' => false,
    'message' => ''
);

try {
    // Verificar se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido');
    }
    
    // Validar e sanitizar dados recebidos
    $solicitante = isset($_POST['solicitante']) ? trim($_POST['solicitante']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : '';
    $prioridade = isset($_POST['prioridade']) ? trim($_POST['prioridade']) : '';
    $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
    
    // Validações
    if (empty($solicitante)) {
        throw new Exception('Nome do solicitante é obrigatório');
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('E-mail inválido');
    }
    
    if (empty($departamento)) {
        throw new Exception('Departamento é obrigatório');
    }
    
    if (empty($prioridade)) {
        throw new Exception('Prioridade é obrigatória');
    }
    
    if (empty($categoria)) {
        throw new Exception('Categoria é obrigatória');
    }
    
    if (empty($descricao)) {
        throw new Exception('Descrição do problema é obrigatória');
    }
    
    // Validar valores dos campos ENUM
    $prioridades_validas = array('Baixa', 'Média', 'Alta', 'Crítica');
    if (!in_array($prioridade, $prioridades_validas)) {
        throw new Exception('Prioridade inválida');
    }
    
    $categorias_validas = array('Hardware', 'Software', 'Rede', 'E-mail', 'Impressora', 'Outro');
    if (!in_array($categoria, $categorias_validas)) {
        throw new Exception('Categoria inválida');
    }
    
    // Obter conexão com banco de dados
    $conn = Database::getConexao();
    
    // Preparar consulta SQL
    $sql = "INSERT INTO chamados (solicitante, email, departamento, prioridade, categoria, descricao, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'aberto')";
    
    // Preparar statement
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Erro ao preparar consulta: ' . $conn->error);
    }
    
    // Bind dos parâmetros
    $stmt->bind_param('ssssss', $solicitante, $email, $departamento, $prioridade, $categoria, $descricao);
    
    // Executar consulta
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Chamado registrado com sucesso!';
        $response['id'] = $stmt->insert_id;
    } else {
        throw new Exception('Erro ao inserir chamado: ' . $stmt->error);
    }
    
    // Fechar statement
    $stmt->close();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    
    // Log do erro (opcional)
    error_log('Erro ao salvar chamado: ' . $e->getMessage());
}

// Retornar resposta em JSON
echo json_encode($response);
?>