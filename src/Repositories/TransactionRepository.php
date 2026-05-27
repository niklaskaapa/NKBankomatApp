<?php


class TransactionRepository implements RepositoryInterface {
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(
        ?int $fromAccountId,
        ?int $toAccountId,
        string $type,
        float $amount,
        string $createdAt
    ): void {

        $stmt = $this->db->prepare(
            "INSERT INTO transactions
            (from_account_id, to_account_id, type, amount, created_at )
            VALUES (?, ?, ?, ?, ?)"

        );

        $stmt->execute([
            $fromAccountId,
            $toAccountId,
            $type,
            $amount,
            $createdAt
        ]);


    }

    public function getAll(): array {

    $stmt = $this->db->query(
            "SELECT 
            t.*,
            fa.account_type AS from_account_type,
            ta.account_type AS to_account_type
         FROM transactions AS t
         LEFT JOIN accounts fa ON t.from_account_id = fa.id
         LEFT JOIN accounts ta ON t.to_account_id = ta.id
         ORDER BY t.id DESC"
    );

    return $stmt->fetchAll();
    }



}

?>