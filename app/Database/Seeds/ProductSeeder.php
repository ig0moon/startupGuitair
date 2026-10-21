<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'slug'          => 'guitair-acustico',
                'name'          => 'GuitAIr Acoustic Pro',
                'tagline'       => 'Violão acústico inteligente com reconhecimento de acordes e afinação neural',
                'category'      => 'violao',
                'price'         => 1899.00,
                'old_price'     => 2299.00,
                'image'         => 'img/violao.png',
                'badge'         => 'Mais Vendido',
                'description'   => 'Instrumento acústico inteligente de alta precisão com IA embarcada. Equipado com microfones piezoelétricos inteligentes, guia visual de escala com micro-LEDs no braço e conexão direta com o app GuitAIr. Perfeito tanto para iniciantes acelerarem seu aprendizado quanto para compositores criarem arranjos complexos.',
                'features'      => json_encode([
                    'Afinação Automática Assistida por IA',
                    'Sensores de Toque no Braço com Feedback Tátil',
                    'Conexão Bluetooth 5.3 com App GuitAIr',
                    'Bateria de Longa Duração (até 40 horas de uso contínuo)',
                    'Madeiras Nobres Selecionadas: Tampo em Spruce Maciço'
                ], JSON_UNESCAPED_UNICODE),
                'specs'         => json_encode([
                    'Tampo'         => 'Spruce Maciço',
                    'Braço'         => 'Mahogany com Micro-LEDs Embutidos',
                    'Conectividade' => 'Bluetooth 5.3 / USB-C Áudio',
                    'Autonomia'     => '40h',
                    'Peso'          => '2.1 kg'
                ], JSON_UNESCAPED_UNICODE),
                'stock'         => 35,
                'rating'        => 4.9,
                'reviews_count' => 48,
                'featured'      => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'slug'          => 'electric-guitair',
                'name'          => 'Electric GuitAIr Neural',
                'tagline'       => 'Guitarra elétrica do futuro com sintetizador neural e simulação analógica em tempo real',
                'category'      => 'guitarra',
                'price'         => 3499.00,
                'old_price'     => 4199.00,
                'image'         => 'img/guitarra.png',
                'badge'         => 'Lançamento',
                'description'   => 'A revolução das guitarras elétricas. Equipada com processador neural de sinal DSP em tempo real, permite simular mais de 100 pedais lendários, caixas e cabeçotes sem precisar de pedalboards pesados. Interface de áudio 24-bit/96kHz embutida via USB-C com latência inferior a 1.2ms.',
                'features'      => json_encode([
                    'Processador Neural DSP Quad-Core Integrado',
                    'Simulação de 120+ Amplificadores e Pedais Famosos',
                    'Saída Direta USB-C com Interface de Áudio 24-bit/96kHz',
                    'Captadores Ativos Humbuckers Híbridos',
                    'Corpo em Alder Premium com Acabamento Fosco Aeroespacial'
                ], JSON_UNESCAPED_UNICODE),
                'specs'         => json_encode([
                    'Corpo'         => 'Alder Selecionado',
                    'Escala'        => 'Rosewood 24 Trastes Jumbo',
                    'DSP'           => 'Neural Audio Engine 64-bit',
                    'Latência'      => '< 1.2ms',
                    'Conexões'      => 'P10 Hi-Z, USB-C, Fone P2, Bluetooth MIDI'
                ], JSON_UNESCAPED_UNICODE),
                'stock'         => 20,
                'rating'        => 5.0,
                'reviews_count' => 64,
                'featured'      => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'slug'          => 'drumia-beats',
                'name'          => 'DrumIA Smart Beats',
                'tagline'       => 'Bateria eletrônica responsiva com inteligência de metrônomo adaptativo e pads sensíveis',
                'category'      => 'bateria',
                'price'         => 4299.00,
                'old_price'     => 4999.00,
                'image'         => 'img/bateria.png',
                'badge'         => 'Destaque',
                'description'   => 'O kit de bateria inteligente que se adapta à sua precisão rítmica. Utiliza redes neurais para analisar suas viradas, timing e groove, sugerindo exercícios personalizados e corrigindo desvios de tempo em frações de segundo. Pads em mesh com resposta idêntica a peles acústicas reais.',
                'features'      => json_encode([
                    'Módulo com Inteligência Artificial de Correção de Groove',
                    'Pads Mesh Dual-Zone Silenciosos de Alto Desempenho',
                    'Mais de 500 Samples Gravados em Estúdios Renomados',
                    'Integração Instantânea com Qualquer DAW (Ableton, FL, Logic)',
                    'Rack de Alumínio Robusto e Compacto Dobrável'
                ], JSON_UNESCAPED_UNICODE),
                'specs'         => json_encode([
                    'Configuração' => 'Snare 10", 3x Toms 8", Crash 12", Ride 14", Hi-Hat',
                    'Módulo'       => 'DrumIA Brain v2',
                    'Samples'      => '500+ Sons Gravados em 96kHz',
                    'Dimensões'    => 'Ajustável para qualquer espaço'
                ], JSON_UNESCAPED_UNICODE),
                'stock'         => 15,
                'rating'        => 4.8,
                'reviews_count' => 29,
                'featured'      => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($products);

        // Usuários padrão
        $users = [
            [
                'name'          => 'Usuário Demo',
                'email'         => 'demo@guitair.com.br',
                'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'role'          => 'user',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Admin GuitAIr',
                'email'         => 'admin@guitair.com.br',
                'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'role'          => 'admin',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
