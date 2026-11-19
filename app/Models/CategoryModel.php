<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DAO da tabela categories (tabela "pai" do relacionamento categories 1:N products).
 * Todo SQL de categoria fica aqui; o controller só chama estes métodos.
 */
class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'description'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|alpha_dash|max_length[100]',
    ];
    protected $validationMessages = [
        'name' => [
            'required'   => 'O nome da categoria é obrigatório.',
            'min_length' => 'O nome da categoria precisa ter pelo menos 2 letras.',
        ],
        'slug' => [
            'alpha_dash' => 'O identificador só pode ter letras, números e hífen.',
        ],
    ];

    /** Categorias com o número de produtos ativos e o total de produtos de cada uma. */
    public function listWithProductCount(): array
    {
        try {
            return $this->select(
                            'categories.*, '
                            . '(SELECT COUNT(*) FROM products p WHERE p.category_id = categories.id AND p.active = 1) AS product_count, '
                            . '(SELECT COUNT(*) FROM products p WHERE p.category_id = categories.id) AS linked_count',
                            false
                        )
                        ->orderBy('categories.name', 'ASC')
                        ->findAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** Lista simples (id, nome, slug) para selects e filtros. */
    public function listAll(): array
    {
        try {
            return $this->select('id, name, slug')->orderBy('name', 'ASC')->findAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /** Id da categoria pelo slug, ou null se não existir. */
    public function idBySlug(string $slug): ?int
    {
        $row = $this->select('id')->where('slug', $slug)->first();
        return $row ? (int)$row['id'] : null;
    }

    /** Gera um slug único a partir do texto informado. */
    public function uniqueSlug(string $text, ?int $exceptId = null): string
    {
        $base = slugify($text) ?: 'categoria';
        $slug = $base;
        $n    = 2;
        while ($this->slugTaken($slug, $exceptId)) {
            $slug = $base . '-' . $n++;
        }
        return $slug;
    }

    protected function slugTaken(string $slug, ?int $exceptId): bool
    {
        $builder = $this->builder()->where('slug', $slug);
        if ($exceptId) {
            $builder->where('id !=', $exceptId);
        }
        return $builder->countAllResults() > 0;
    }

    /**
     * CREATE: cadastra a categoria.
     * @return array ['success' => bool, 'message' => string, 'id' => int]
     */
    public function createCategory(string $name, ?string $slug, ?string $description): array
    {
        $data = [
            'name'        => trim($name),
            'slug'        => $this->uniqueSlug($slug ?: $name),
            'description' => $description !== null && trim($description) !== '' ? trim($description) : null,
        ];

        if (!$this->insert($data)) {
            return ['success' => false, 'message' => implode(' ', $this->errors())];
        }
        return ['success' => true, 'message' => 'Categoria "' . esc($data['name']) . '" criada.', 'id' => (int)$this->getInsertID()];
    }

    /** UPDATE: altera nome, slug e descrição. */
    public function updateCategory(int $id, string $name, ?string $slug, ?string $description): array
    {
        if (!$this->find($id)) {
            return ['success' => false, 'message' => 'Categoria não encontrada.'];
        }

        $data = [
            'name'        => trim($name),
            'slug'        => $this->uniqueSlug($slug ?: $name, $id),
            'description' => $description !== null && trim($description) !== '' ? trim($description) : null,
        ];

        if (!$this->update($id, $data)) {
            return ['success' => false, 'message' => implode(' ', $this->errors())];
        }
        return ['success' => true, 'message' => 'Categoria "' . esc($data['name']) . '" atualizada.'];
    }

    /**
     * DELETE: só apaga categoria sem produtos vinculados
     * (a chave estrangeira products.category_id usa ON DELETE RESTRICT).
     */
    public function deleteCategory(int $id): array
    {
        $category = $this->find($id);
        if (!$category) {
            return ['success' => false, 'message' => 'Categoria não encontrada.'];
        }

        $linked = $this->db->table('products')->where('category_id', $id)->countAllResults();
        if ($linked > 0) {
            return [
                'success' => false,
                'message' => 'A categoria "' . esc($category['name']) . '" ainda tem ' . $linked
                           . ' produto(s) vinculado(s). Mova ou exclua esses produtos antes.',
            ];
        }

        $this->delete($id);
        return ['success' => true, 'message' => 'Categoria "' . esc($category['name']) . '" excluída.'];
    }
}
