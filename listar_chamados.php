<?php
/**
 * Script para listar os chamados mais recentes
 * Sistema de Chamados - Suporte TI
 */

// Incluir arquivo de conexão
require_once 'conexao.php';

// Definir cabeçalho para retornar JSON
header('Content-Type: application/json; charset=utf-8');

// Inicializar resposta
$response = array(
    'success' => false,
    'chamados' => array(),
    'message' => ''
);

try {
    // Obter conexão com banco de dados
    $conn = Database::getConexao();
    
    // Preparar consulta SQL para buscar os últimos 10 chamados
    $sql = "SELECT 
                id,
                solicitante,
                email,
                departamento,
                prioridade,
                categoria,
                descricao,
                status,
                DATE_FORMAT(data_criacao, '%d/%m/%Y às %H:%i:%s') as data_criacao,
                data_atualizacao
            FROM chamados 
            ORDER BY data_criacao DESC 
            LIMIT 10";
    
    // Executar consulta
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception('Erro ao buscar chamados: ' . $conn->error);
    }
    
    // Processar resultados
    $chamados = array();
    
    while ($row = $result->fetch_assoc()) {
        $chamados[] = array(
            'id' => $row['id'],
            'solicitante' => $row['solicitante'],
            'email' => $row['email'],
            'departamento' => $row['departamento'],
            'prioridade' => $row['prioridade'],
            'categoria' => $row['categoria'],
            'descricao' => $row['descricao'],
            'status' => $row['status'],
            'data_criacao' => $row['data_criacao']
        );
    }
    
    // Definir resposta de sucesso
    $response['success'] = true;
    $response['chamados'] = $chamados;
    $response['total'] = count($chamados);
    
    // Liberar resultado
    $result->free();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    
    // Log do erro (opcional)
    error_log('Erro ao listar chamados: ' . $e->getMessage());
}

// Retornar resposta em JSON
echo json_encode($response);
?>