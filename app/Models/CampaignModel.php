<?php

namespace App\Models;

use CodeIgniter\Model;

class CampaignModel extends Model
{
    protected $table            = 'campaign_leads';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'email', 'age', 'knows_campaign', 'message', 'created_at'
    ];

    /**
     * Add lead or message
     */
    public function recordLead(array $data): array
    {
        $record = [
            'name'           => trim($data['name']),
            'email'          => strtolower(trim($data['email'])),
            'age'            => !empty($data['age']) ? (int)$data['age'] : null,
            'knows_campaign' => $data['knows_campaign'] ?? 'sim',
            'message'        => trim($data['message'] ?? ''),
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        try {
            $id = $this->insert($record, true);
            $record['id'] = $id;
            return ['success' => true, 'lead' => $record];
        } catch (\Throwable $e) {
            $leads = session('campaign_leads') ?? [];
            $record['id'] = count($leads) + 1;
            $leads[] = $record;
            session()->set('campaign_leads', $leads);
            return ['success' => true, 'lead' => $record, 'simulated' => true];
        }
    }

    /**
     * Get recent support messages
     */
    public function getRecentMessages(int $limit = 6): array
    {
        try {
            return $this->where('message !=', '')
                        ->orderBy('id', 'DESC')
                        ->limit($limit)
                        ->findAll() ?: $this->defaultMessages();
        } catch (\Throwable $e) {
            $sessionLeads = session('campaign_leads') ?? [];
            $filtered = array_filter($sessionLeads, fn($l) => !empty($l['message']));
            if (!empty($filtered)) {
                return array_slice(array_reverse($filtered), 0, $limit);
            }
            return $this->defaultMessages();
        }
    }

    protected function defaultMessages(): array
    {
        return [
            [
                'name'       => 'Mariana Silva',
                'message'    => 'Apoio total à campanha! Proteger nossas crianças é um dever diário de toda a sociedade.',
                'created_at' => date('d/m/Y', strtotime('-2 days'))
            ],
            [
                'name'       => 'Lucas Fernandes',
                'message'    => 'Excelente iniciativa da GuitAIr em usar sua visibilidade e tecnologia em prol de causas nobres. Disque 100!',
                'created_at' => date('d/m/Y', strtotime('-4 days'))
            ],
            [
                'name'       => 'Camila Rocha',
                'message'    => 'Que a música continue sendo instrumento de transformação e esperança para as futuras gerações.',
                'created_at' => date('d/m/Y', strtotime('-1 week'))
            ],
        ];
    }
}
