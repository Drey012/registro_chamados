-- =============================================
-- SCRIPT DE CRIAÇÃO DO BANCO DE DADOS
-- Sistema de Chamados - Suporte TI
-- =============================================

-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS sistema_chamados 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Usar o banco de dados
USE sistema_chamados;

-- =============================================
-- TABELA: chamados
-- =============================================
CREATE TABLE IF NOT EXISTS chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    departamento VARCHAR(50) NOT NULL,
    prioridade ENUM('Baixa', 'Média', 'Alta', 'Crítica') NOT NULL DEFAULT 'Média',
    categoria ENUM('Hardware', 'Software', 'Rede', 'E-mail', 'Impressora', 'Outro') NOT NULL,
    descricao TEXT NOT NULL,
    status ENUM('aberto', 'em-andamento', 'resolvido', 'cancelado') NOT NULL DEFAULT 'aberto',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_prioridade (prioridade),
    INDEX idx_data_criacao (data_criacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- INSERIR DADOS DE EXEMPLO
-- =============================================
INSERT INTO chamados (solicitante, email, departamento, prioridade, categoria, descricao, status, data_criacao) VALUES
('Maria Silva', 'maria.silva@empresa.com', 'Financeiro', 'Alta', 'Software', 'Sistema ERP apresentando erro ao gerar relatórios de fechamento mensal', 'em-andamento', '2025-11-10 09:30:00'),
('João Santos', 'joao.santos@empresa.com', 'Vendas', 'Média', 'Hardware', 'Mouse sem fio não está funcionando corretamente', 'resolvido', '2025-11-10 10:15:00'),
('Ana Costa', 'ana.costa@empresa.com', 'RH', 'Crítica', 'Rede', 'Sem acesso à internet em todo o departamento', 'aberto', '2025-11-10 11:00:00'),
('Pedro Oliveira', 'pedro.oliveira@empresa.com', 'Marketing', 'Baixa', 'Impressora', 'Impressora de rede imprimindo com qualidade inferior', 'aberto', '2025-11-10 11:45:00'),
('Carla Souza', 'carla.souza@empresa.com', 'Administrativo', 'Alta', 'E-mail', 'Não consigo receber e-mails desde ontem à tarde', 'em-andamento', '2025-11-10 12:20:00');

-- =============================================
-- VIEWS ÚTEIS
-- =============================================

-- View para chamados abertos
CREATE OR REPLACE VIEW chamados_abertos AS
SELECT * FROM chamados 
WHERE status = 'aberto' 
ORDER BY 
    CASE prioridade
        WHEN 'Crítica' THEN 1
        WHEN 'Alta' THEN 2
        WHEN 'Média' THEN 3
        WHEN 'Baixa' THEN 4
    END,
    data_criacao DESC;

-- View para estatísticas
CREATE OR REPLACE VIEW estatisticas_chamados AS
SELECT 
    COUNT(*) as total_chamados,
    SUM(CASE WHEN status = 'aberto' THEN 1 ELSE 0 END) as abertos,
    SUM(CASE WHEN status = 'em-andamento' THEN 1 ELSE 0 END) as em_andamento,
    SUM(CASE WHEN status = 'resolvido' THEN 1 ELSE 0 END) as resolvidos,
    SUM(CASE WHEN status = 'cancelado' THEN 1 ELSE 0 END) as cancelados
FROM chamados;

-- =============================================
-- PROCEDURES ÚTEIS
-- =============================================

-- Procedure para atualizar status do chamado
DELIMITER //
CREATE PROCEDURE atualizar_status_chamado(
    IN p_id INT,
    IN p_novo_status VARCHAR(20)
)
BEGIN
    UPDATE chamados 
    SET status = p_novo_status,
        data_atualizacao = CURRENT_TIMESTAMP
    WHERE id = p_id;
END //
DELIMITER ;

-- =============================================
-- CONSULTAS ÚTEIS PARA TESTES
-- =============================================

-- Ver todos os chamados
-- SELECT * FROM chamados ORDER BY data_criacao DESC;

-- Ver apenas chamados abertos
-- SELECT * FROM chamados_abertos;

-- Ver estatísticas
-- SELECT * FROM estatisticas_chamados;

-- Buscar chamados por prioridade
-- SELECT * FROM chamados WHERE prioridade = 'Alta' ORDER BY data_criacao DESC;

-- Buscar chamados por departamento
-- SELECT * FROM chamados WHERE departamento = 'Financeiro' ORDER BY data_criacao DESC;

-- Atualizar status de um chamado (exemplo)
-- CALL atualizar_status_chamado(1, 'resolvido');