<?php

require_once __DIR__ . "/connect.php";

/**
 * Classe responsável por manipular os dados da tabela users.
 *
 * Esta classe centraliza as operações de:
 * - listagem
 * - busca por ID
 * - cadastro
 * - atualização
 * - exclusão
 *
 * Isso melhora a organização do projeto e evita repetição de código.
 */
class User
{
    /**
     * Armazena a conexão com o banco de dados.
     *
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Construtor da classe.
     *
     * Sempre que um objeto User for criado,
     * a conexão com o banco será carregada.
     */
    public function __construct()
    {
        $this->pdo = Connect::getInstance();
    }

    /**
     * Busca um usuário pelo e-mail.
     * * @param string $email
     * @return array|false
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([":email" => $email]);

        return $stmt->fetch();
    }

    /**
     * Retorna todos os usuários cadastrados,
     * ordenados pelo ID em ordem crescente.
     *
     * @return array
     */
    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    /**
     * Busca um usuário pelo ID.
     *
     * Se encontrar, retorna um array com os dados do usuário.
     * Se não encontrar, retorna false.
     *
     * @param int $id
     * @return array|false
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch();
    }

    /**
     * Cadastra um novo usuário no banco de dados.
     *
     * @param string $name
     * @param string $email
     * @param string $document
     * @return bool
     */
    public function create(string $name, string $email, string $document): bool
    {
        
        if ($this->findByEmail($email)) {
        return false; 
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, document)
            VALUES (:name, :email, :document)
        ");

        return $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":document" => $document
        ]);
    }

    /**
     * Atualiza os dados de um usuário existente.
     *
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $document
     * @return bool
     */
    public function update(int $id, string $name, string $email, string $document): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET name = :name,
                email = :email,
                document = :document
            WHERE id = :id
        ");

        return $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":email" => $email,
            ":document" => $document
        ]);
    }

    /**
     * Exclui um usuário com base no ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");

        return $stmt->execute([
            ":id" => $id
        ]);
    }
}
