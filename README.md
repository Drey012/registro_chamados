# 📋 Sistema de Chamados - Suporte TI

Sistema completo para gerenciamento de chamados de suporte técnico com PHP, MySQL e jQuery.

---

## 📁 Estrutura de Arquivos

```
sistema-chamados/
│
├── index.html              # Interface principal do sistema
├── conexao.php            # Configuração da conexão com MySQL
├── salvar_chamado.php     # API para salvar novos chamados
├── listar_chamados.php    # API para listar chamados
└── database.sql           # Script de criação do banco de dados
```

---

## 🚀 Instalação e Configuração

### **1. Pré-requisitos**

- PHP 7.4 ou superior
- MySQL 5.7 ou superior / MariaDB 10.3 ou superior
- Servidor web (Apache, Nginx, etc.) ou XAMPP/WAMP
- jQuery 3.7.1 (carregado via CDN)

---

### **2. Configurar o Banco de Dados**

#### **Opção A: Via phpMyAdmin**

1. Acesse o phpMyAdmin (geralmente em `http://localhost/phpmyadmin`)
2. Clique em "SQL" no menu superior
3. Copie e cole o conteúdo do arquivo `database.sql`
4. Clique em "Executar"

#### **Opção B: Via linha de comando**

```bash
# Acesse o MySQL
mysql -u root -p

# Execute o script SQL
source caminho/para/database.sql

# Ou execute diretamente
mysql -u root -p < database.sql
```

---

### **3. Configurar a Conexão com o Banco**

Abra o arquivo `conexao.php` e ajuste as configurações:

```php
define('DB_HOST', 'localhost');      // Host do banco
define('DB_USER', 'root');           // Seu usuário MySQL
define('DB_PASS', '');               // Sua senha MySQL
define('DB_NAME', 'sistema_chamados'); // Nome do banco
```

---

### **4. Configurar o Servidor Web**

#### **Usando XAMPP/WAMP:**

1. Copie todos os arquivos para a pasta `htdocs` (XAMPP) ou `www` (WAMP)
2. Acesse: `http://localhost/sistema-chamados/index.html`

#### **Usando PHP Built-in Server (desenvolvimento):**

```bash
cd pasta-do-projeto
php -S localhost:8000
```

Acesse: `http://localhost:8000/index.html`

---

## 🔧 Estrutura do Banco de Dados

### **Tabela: chamados**

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT (PK, AI) | ID único do chamado |
| `solicitante` | VARCHAR(100) | Nome do solicitante |
| `email` | VARCHAR(100) | E-mail do solicitante |
| `departamento` | VARCHAR(50) | Departamento |
| `prioridade` | ENUM | Baixa, Média, Alta, Crítica |
| `categoria` | ENUM | Hardware, Software, Rede, etc. |
| `descricao` | TEXT | Descrição do problema |
| `status` | ENUM | aberto, em-andamento, resolvido, cancelado |
| `data_criacao` | TIMESTAMP | Data de criação (automática) |
| `data_atualizacao` | TIMESTAMP | Data da última atualização |

---

## 🎯 Funcionalidades

✅ **Cadastro de Chamados**
- Formulário completo com validação
- 6 campos obrigatórios
- Validação client-side e server-side

✅ **Listagem em Tempo Real**
- Atualização automática via AJAX
- Exibe últimos 10 chamados
- Sem necessidade de recarregar a página

✅ **Sistema de Status**
- Aberto (amarelo)
- Em Andamento (azul)
- Resolvido (verde)

✅ **Segurança**
- Prepared Statements (prevenção SQL Injection)
- Validação de dados
- Sanitização de inputs
- Tratamento de erros

✅ **Interface Moderna**
- Design responsivo
- Animações suaves
- Gradientes e efeitos visuais
- Compatível com mobile

---

## 🔍 Testando o Sistema

### **1. Testar Conexão com Banco**

Descomente as linhas no final do arquivo `conexao.php`:

```php
try {
    $conn = Database::getConexao();
    echo "Conexão estabelecida com sucesso!";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
```

Acesse: `http://localhost/sistema-chamados/conexao.php`

### **2. Verificar Dados de Exemplo**

Execute no MySQL:

```sql
USE sistema_chamados;
SELECT * FROM chamados;
```

Você verá 5 chamados de exemplo já inseridos.

### **3. Testar Cadastro**

1. Abra o `index.html` no navegador
2. Preencha o formulário
3. Clique em "Registrar Chamado"
4. O novo chamado aparecerá na lista automaticamente

---

## 📊 Consultas Úteis

```sql
-- Ver todos os chamados ordenados por data
SELECT * FROM chamados ORDER BY data_criacao DESC;

-- Ver apenas chamados abertos
SELECT * FROM chamados WHERE status = 'aberto';

-- Ver estatísticas
SELECT 
    status, 
    COUNT(*) as total 
FROM chamados 
GROUP BY status;

-- Buscar por departamento
SELECT * FROM chamados WHERE departamento = 'Financeiro';

-- Atualizar status de um chamado
UPDATE chamados 
SET status = 'resolvido' 
WHERE id = 1;
```

---

## 🛠️ Troubleshooting

### **Erro: "Erro ao conectar com o banco de dados"**

- Verifique se o MySQL está rodando
- Confirme usuário e senha em `conexao.php`
- Verifique se o banco `sistema_chamados` existe

### **Erro: "Access denied for user"**

```sql
-- Criar usuário e dar permissões
CREATE USER 'usuario'@'localhost' IDENTIFIED BY 'senha';
GRANT ALL PRIVILEGES ON sistema_chamados.* TO 'usuario'@'localhost';
FLUSH PRIVILEGES;
```

### **Chamados não aparecem na lista**

- Abra o console do navegador (F12) e verifique erros
- Teste a URL: `http://localhost/sistema-chamados/listar_chamados.php`
- Verifique se há dados no banco: `SELECT * FROM chamados;`

### **Erro de charset/acentuação**

- Verifique se o banco está em `utf8mb4`
- Certifique-se que os arquivos estão salvos em UTF-8
- Confirme o charset na conexão

---

## 📝 Próximas Melhorias (Opcional)

- [ ] Sistema de login/autenticação
- [ ] Atribuição de chamados para técnicos
- [ ] Histórico de alterações
- [ ] Anexos de arquivos
- [ ] Notificações por e-mail
- [ ] Painel de estatísticas
- [ ] Filtros e busca avançada
- [ ] API RESTful completa

---

## 👨‍💻 Tecnologias Utilizadas

- **Frontend:** HTML5, CSS3, JavaScript, jQuery 3.7.1
- **Backend:** PHP 7.4+
- **Banco de Dados:** MySQL 5.7+ / MariaDB 10.3+
- **Arquitetura:** AJAX para comunicação assíncrona

---

## 📄 Licença

Este projeto é livre para uso educacional e comercial.

---

## 🤝 Suporte

Se encontrar problemas, verifique:
1. Logs de erro do PHP (`error_log`)
2. Console do navegador (F12 → Console)
3. Logs do MySQL
4. Permissões de arquivo/pasta

---

**Desenvolvido com ❤️ para facilitar o gerenciamento de suporte técnico!**
