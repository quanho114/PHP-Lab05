<?php
 
class PatientRepository
{
    public function __construct(private PDO $db) {}
 
    public function countAll(string $keyword = '', string $gender = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM patients";
        $conditions = [];
        $params = [];
 
        if ($keyword !== '') {
            $conditions[] = "(name LIKE :keyword_name OR email LIKE :keyword_email OR phone LIKE :keyword_phone)";
            $params['keyword_name'] = '%' . $keyword . '%';
            $params['keyword_email'] = '%' . $keyword . '%';
            $params['keyword_phone'] = '%' . $keyword . '%';
        }
 
        if ($gender !== '') {
            $conditions[] = "gender = :gender";
            $params['gender'] = $gender;
        }
 
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
 
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }
 
    public function getPaginated(string $keyword, string $gender, int $limit, int $offset, string $sort, string $direction): array
    {
        $allowedSorts = ['id', 'name', 'email', 'phone', 'gender', 'created_at'];
        $allowedDirections = ['asc', 'desc'];
 
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }
        if (!in_array(strtolower($direction), $allowedDirections, true)) {
            $direction = 'desc';
        }
 
        $sql = "SELECT id, name, email, phone, gender, created_at FROM patients";
        $conditions = [];
        $params = [];
 
        if ($keyword !== '') {
            $conditions[] = "(name LIKE :keyword_name OR email LIKE :keyword_email OR phone LIKE :keyword_phone)";
            $params['keyword_name'] = '%' . $keyword . '%';
            $params['keyword_email'] = '%' . $keyword . '%';
            $params['keyword_phone'] = '%' . $keyword . '%';
        }
 
        if ($gender !== '') {
            $conditions[] = "gender = :gender";
            $params['gender'] = $gender;
        }
 
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
 
        $sql .= " ORDER BY {$sort} {$direction} LIMIT :limit OFFSET :offset";
 
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
 
        return $stmt->fetchAll();
    }
 
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
 
    public function create(array $data): bool
    {
        $sql = "INSERT INTO patients (name, email, phone, gender, note)
                VALUES (:name, :email, :phone, :gender, :note)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?: null,
                'gender' => $data['gender'],
                'note' => $data['note'] ?: null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Patient email already exists.');
            }
            throw $e;
        }
    }
 
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE patients
                SET name = :name, email = :email, phone = :phone,
                    gender = :gender, note = :note, updated_at = NOW()
                WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?: null,
                'gender' => $data['gender'],
                'note' => $data['note'] ?: null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Patient email already exists.');
            }
            throw $e;
        }
    }
 
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM patients WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
