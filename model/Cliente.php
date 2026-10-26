<?php
declare(strict_types=1);

class Cliente
{
    private ?int $id = null;
    private string $nome = '';
    private string $email = '';
    private string $telefone = '';

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): self { $this->nome = trim($nome); return $this; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = trim($email); return $this; }
    public function getTelefone(): string { return $this->telefone; }
    public function setTelefone(string $telefone): self { $this->telefone = trim($telefone); return $this; }
}
