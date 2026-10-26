<?php

//o php irá exigir a tipificação dos atributos e métodos
declare(strict_types=1);

class Categoria
{
    // a interrogação indica que pode ser nulo
    private ?int $id = null;
    private string $nome = "";
    private string $informacoes = "";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = trim($nome);
        return $this;
    }

    public function getInformacoes(): string
    {
        return $this->informacoes;
    }

    public function setInformacoes(string $informacoes): self
    {
        $this->informacoes = trim($informacoes);
        return $this;
    }
}
