<?php



class UserRepository implements RepositoryInterface {
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByCardNumber(string $cardNumber): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE card_number = ?"
        );

        $stmt->execute([$cardNumber]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;


    }

    public function getAll(): array {
        $stmt = $this->db->query
        ("SELECT * from users");

        return $stmt->fetchAll();
    }


}

?>


