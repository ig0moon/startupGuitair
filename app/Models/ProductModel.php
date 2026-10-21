<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'slug', 'name', 'tagline', 'category', 'price', 'old_price',
        'image', 'badge', 'description', 'features', 'specs', 'stock',
        'rating', 'reviews_count', 'featured'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Fallback catalog when database is not connected yet
     */
    protected array $fallbackProducts = [
        [
            'id'            => 1,
            'slug'          => 'guitair-acustico',
            'name'          => 'GuitAIr Acoustic Pro',
            'tagline'       => 'Violão acústico inteligente com reconhecimento de acordes e afinação neural',
            'category'      => 'violao',
            'price'         => 1899.00,
            'old_price'     => 2299.00,
            'image'         => 'img/violao.png',
            'badge'         => 'Mais Vendido',
            'description'   => 'Instrumento acústico inteligente de alta precisão com IA embarcada. Equipado com microfones piezoelétricos inteligentes, guia visual de escala com micro-LEDs no braço e conexão direta com o app GuitAIr. Perfeito tanto para iniciantes acelerarem seu aprendizado quanto para compositores criarem arranjos complexos.',
            'features'      => [
                'Afinação Automática Assistida por IA',
                'Sensores de Toque no Braço com Feedback Tátil',
                'Conexão Bluetooth 5.3 com App GuitAIr',
                'Bateria de Longa Duração (até 40 horas de uso contínuo)',
                'Madeiras Nobres Selecionadas: Tampo em Spruce Maciço'
            ],
            'specs'         => [
                'Tampo'         => 'Spruce Maciço',
                'Braço'         => 'Mahogany com Micro-LEDs Embutidos',
                'Conectividade' => 'Bluetooth 5.3 / USB-C Áudio',
                'Autonomia'     => '40h',
                'Peso'          => '2.1 kg'
            ],
            'stock'         => 35,
            'rating'        => 4.9,
            'reviews_count' => 48,
            'featured'      => 1
        ],
        [
            'id'            => 2,
            'slug'          => 'electric-guitair',
            'name'          => 'Electric GuitAIr Neural',
            'tagline'       => 'Guitarra elétrica do futuro com sintetizador neural e simulação analógica em tempo real',
            'category'      => 'guitarra',
            'price'         => 3499.00,
            'old_price'     => 4199.00,
            'image'         => 'img/guitarra.png',
            'badge'         => 'Lançamento',
            'description'   => 'A revolução das guitarras elétricas. Equipada com processador neural de sinal DSP em tempo real, permite simular mais de 100 pedais lendários, caixas e cabeçotes sem precisar de pedalboards pesados. Interface de áudio 24-bit/96kHz embutida via USB-C com latência inferior a 1.2ms.',
            'features'      => [
                'Processador Neural DSP Quad-Core Integrado',
                'Simulação de 120+ Amplificadores e Pedais Famosos',
                'Saída Direta USB-C com Interface de Áudio 24-bit/96kHz',
                'Captadores Ativos Humbuckers Híbridos',
                'Corpo em Alder Premium com Acabamento Fosco Aeroespacial'
            ],
            'specs'         => [
                'Corpo'         => 'Alder Selecionado',
                'Escala'        => 'Rosewood 24 Trastes Jumbo',
                'DSP'           => 'Neural Audio Engine 64-bit',
                'Latência'      => '< 1.2ms',
                'Conexões'      => 'P10 Hi-Z, USB-C, Fone P2, Bluetooth MIDI'
            ],
            'stock'         => 20,
            'rating'        => 5.0,
            'reviews_count' => 64,
            'featured'      => 1
        ],
        [
            'id'            => 3,
            'slug'          => 'drumia-beats',
            'name'          => 'DrumIA Smart Beats',
            'tagline'       => 'Bateria eletrônica responsiva com inteligência de metrônomo adaptativo e pads sensíveis',
            'category'      => 'bateria',
            'price'         => 4299.00,
            'old_price'     => 4999.00,
            'image'         => 'img/bateria.png',
            'badge'         => 'Destaque',
            'description'   => 'O kit de bateria inteligente que se adapta à sua precisão rítmica. Utiliza redes neurais para analisar suas viradas, timing e groove, sugerindo exercícios personalizados e corrigindo desvios de tempo em frações de segundo. Pads em mesh com resposta idêntica a peles acústicas reais.',
            'features'      => [
                'Módulo com Inteligência Artificial de Correção de Groove',
                'Pads Mesh Dual-Zone Silenciosos de Alto Desempenho',
                'Mais de 500 Samples Gravados em Estúdios Renomados',
                'Integração Instantânea com Qualquer DAW (Ableton, FL, Logic)',
                'Rack de Alumínio Robusto e Compacto Dobrável'
            ],
            'specs'         => [
                'Configuração' => 'Snare 10", 3x Toms 8", Crash 12", Ride 14", Hi-Hat',
                'Módulo'       => 'DrumIA Brain v2',
                'Samples'      => '500+ Sons Gravados em 96kHz',
                'Dimensões'    => 'Ajustável para qualquer espaço'
            ],
            'stock'         => 15,
            'rating'        => 4.8,
            'reviews_count' => 29,
            'featured'      => 1
        ]
    ];

    /**
     * Get all active products with graceful fallback
     */
    public function getActiveProducts(?string $category = null): array
    {
        try {
            $builder = $this->orderBy('id', 'ASC');
            if ($category && $category !== 'todos') {
                $builder->where('category', $category);
            }
            $results = $builder->findAll();
            if (!empty($results)) {
                return array_map([$this, 'formatProduct'], $results);
            }
        } catch (\Throwable $e) {
            // DB not connected or table not created yet, use fallback
        }

        $list = $this->fallbackProducts;
        if ($category && $category !== 'todos') {
            $list = array_values(array_filter($list, fn($p) => $p['category'] === $category));
        }
        return $list;
    }

    /**
     * Get single product by slug or id or color alias
     */
    public function findProduct($identifier): ?array
    {
        // Color mapping for legacy support (amarelo => violão, azul => guitarra, vermelho => bateria)
        $legacyAliases = [
            'amarelo'  => 'guitair-acustico',
            'azul'     => 'electric-guitair',
            'vermelho' => 'drumia-beats',
        ];

        if (is_string($identifier) && isset($legacyAliases[strtolower($identifier)])) {
            $identifier = $legacyAliases[strtolower($identifier)];
        }

        try {
            $product = is_numeric($identifier)
                ? $this->find($identifier)
                : $this->where('slug', $identifier)->first();

            if ($product) {
                return $this->formatProduct($product);
            }
        } catch (\Throwable $e) {
            // Fallback
        }

        foreach ($this->fallbackProducts as $item) {
            if ($item['slug'] === $identifier || (is_numeric($identifier) && $item['id'] == $identifier)) {
                return $item;
            }
        }

        return $this->fallbackProducts[0] ?? null;
    }

    protected function formatProduct(array $p): array
    {
        if (is_string($p['features'] ?? null)) {
            $p['features'] = json_decode($p['features'], true) ?: [];
        }
        if (is_string($p['specs'] ?? null)) {
            $p['specs'] = json_decode($p['specs'], true) ?: [];
        }
        return $p;
    }
}
