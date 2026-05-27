<?php


class AccountRepository implements RepositoryInterface {
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM accounts WHERE user_id = ?"
        );

        $stmt->execute([$userId]);

        return $stmt->fetchAll();

    }


    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM accounts WHERE id = ?"
        );

        $stmt->execute([$id]);

        $account = $stmt->fetch();

        return $account ?: null;

    }


    public function withdraw(int $accountId, float $amount): void {
        $stmt = $this->db->prepare(
            "UPDATE accounts
            SET balance = balance - ?
            WHERE id =?"
        );

        $stmt->execute([$amount, $accountId]);

    }

    public function deposit(int $accountId, float $amount): void {
        $stmt = $this->db->prepare(
            "UPDATE accounts
            SET balance = balance + ?
            WHERE id = ?"
        );

        $stmt->execute([$amount, $accountId]);
    }

    public function transfer(int $fromAccountId, int $toAccountId, float $amount): void {

        $withdraw = $this->db->prepare(
            "UPDATE accounts
            SET balance = balance - ?
            WHERE id = ?"
        );

        $withdraw->execute([
            $amount,
           $fromAccountId 
        ]);

        $deposit = $this->db->prepare(
            "UPDATE accounts
            SET balance = balance + ?
            WHERE id = ?"
        );

        $deposit->execute([
            $amount,
            $toAccountId
        ]);
    }

    public function getAll(): array {

    $stmt = $this->db->query(
          "SELECT 
            accounts.*, 
            users.name AS owner_name
         FROM accounts
         JOIN users ON accounts.user_id = users.id"
    );

    return $stmt->fetchAll();
    }


}

?>