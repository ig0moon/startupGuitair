<?php

namespace App\Controllers;

use Config\Database;

class TestDb extends BaseController
{
    public function index()
    {
        header('Content-Type: text/plain; charset=utf-8');

        echo "=== DIAGNÓSTICO DE CONEXÃO AO BANCO ===\n\n";

        // 1. Mostrar configurações resolvidas (ocultando senha)
        $config = config('Database');
        $dbConfig = $config->default ?? [];

        echo "Configurações lidas pelo CodeIgniter:\n";
        echo " - Hostname: " . ($dbConfig['hostname'] ?? '(vazio)') . "\n";
        echo " - Port: " . ($dbConfig['port'] ?? '(vazio)') . "\n";
        echo " - Database: " . ($dbConfig['database'] ?? '(vazio)') . "\n";
        echo " - Username: " . ($dbConfig['username'] ?? '(vazio)') . "\n";
        echo " - Password: " . (!empty($dbConfig['password']) ? '****** (preenchida)' : '(vazia)') . "\n";
        echo " - DBDriver: " . ($dbConfig['DBDriver'] ?? '(vazio)') . "\n";
        echo " - Encrypt (SSL): " . print_r($dbConfig['encrypt'] ?? false, true) . "\n\n";

        echo "Variáveis de ambiente brutas detectadas:\n";
        $envKeys = [
            'database.default.hostname', 'database_default_hostname',
            'database.default.database', 'database_default_database',
            'database.default.username', 'database_default_username',
            'database.default.port', 'database_default_port',
            'database.default.encrypt', 'database_default_encrypt',
            'database.default.ssl_ca', 'database_default_ssl_ca',
        ];
        foreach ($envKeys as $key) {
            $val = getenv($key) ?: (isset($_ENV[$key]) ? $_ENV[$key] : null);
            if ($val !== null) {
                echo " - {$key}: {$val}\n";
            }
        }
        echo "\n";

        // 2. Testar conexão nativa MySQLi direta (para isolar problemas do CI4)
        echo "--- Teste 1: Conexão direta via PHP MySQLi nativo ---\n";
        try {
            $mysqli = mysqli_init();
            if (!empty($dbConfig['encrypt']) && is_array($dbConfig['encrypt'])) {
                $ca = $dbConfig['encrypt']['ssl_ca'] ?? null;
                $verify = $dbConfig['encrypt']['ssl_verify'] ?? true;
                echo "Configurando SSL nativo (CA: {$ca})...\n";
                mysqli_ssl_set($mysqli, NULL, NULL, $ca, NULL, NULL);
                if (!$verify) {
                    mysqli_options($mysqli, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
                }
            }
            
            $connected = @mysqli_real_connect(
                $mysqli,
                $dbConfig['hostname'],
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['database'],
                (int)($dbConfig['port'] ?: 3306)
            );

            if ($connected) {
                echo "SUCESSO! Conectado diretamente via mysqli.\n";
                $versionRes = mysqli_query($mysqli, "SELECT VERSION() as v");
                if ($versionRes && $row = mysqli_fetch_assoc($versionRes)) {
                    echo "Versão do Servidor: " . $row['v'] . "\n";
                }
                
                // Checar se a tabela products existe
                $tableRes = mysqli_query($mysqli, "SHOW TABLES LIKE 'products'");
                if ($tableRes && mysqli_num_rows($tableRes) > 0) {
                    echo "Tabela 'products' encontrada no banco!\n";
                    $countRes = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM products");
                    $countRow = mysqli_fetch_assoc($countRes);
                    echo "Total de produtos cadastrados: " . ($countRow['total'] ?? 0) . "\n";
                } else {
                    echo "AVISO: Tabela 'products' NÃO existe no banco. O schema.sql foi executado?\n";
                }
                mysqli_close($mysqli);
            } else {
                echo "FALHA ao conectar via mysqli: (" . mysqli_connect_errno() . ") " . mysqli_connect_error() . "\n";
            }
        } catch (\Throwable $e) {
            echo "ERRO EXCEÇÃO MySQLi: " . $e->getMessage() . "\n";
        }

        echo "\n--- Teste 2: Conexão via CodeIgniter Database Service ---\n";
        try {
            $db = \Config\Database::connect();
            $db->initialize();
            echo "SUCESSO! CodeIgniter conectou ao banco.\n";
            $query = $db->query("SELECT DATABASE() as db");
            $row = $query->getRow();
            echo "Banco ativo: " . ($row->db ?? 'nenhum') . "\n";
        } catch (\Throwable $e) {
            echo "FALHA no CodeIgniter DB: " . $e->getMessage() . "\n";
        }

        exit;
    }
}
