<?php
declare(strict_types=1);

class Fornecedor
{
    private ?int $id = null;
    private string $razaoSocial = '';
    private string $email = '';
    private string $telefone = '';

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getRazaoSocial(): string { return $this->razaoSocial; }
    public function setRazaoSocial(string $razaoSocial): self { $this->razaoSocial = trim($razaoSocial); return $this; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = trim($email); return $this; }
    public function getTelefone(): string { return $this->telefone; }
    public function setTelefone(string $telefone): self { $this->telefone = trim($telefone); return $this; }
}
