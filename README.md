# Armazém Inteligente de Itens

Sistema PHP com MySQL para organizar cadastros e acompanhar o estoque. A interface é responsiva e funciona em telas pequenas e grandes.

## O que o sistema oferece

- Painel com contagem de itens, unidades, clientes, fornecedores e alertas de reposição.
- Cadastro e consulta de produtos, com código/SKU, categoria, fornecedor, quantidade inicial, custo e limite mínimo.
- Entradas e saídas de estoque com atualização transacional do saldo e histórico por data.
- Bloqueio de saída acima do saldo disponível.
- Cadastros e listagens de categorias, fornecedores e clientes, com busca rápida.
- Validação no servidor, queries preparadas PDO, escape de saída HTML e proteção CSRF.
- Configuração por variáveis de ambiente; mensagens de erro não expõem detalhes técnicos do banco.

## Requisitos

- PHP 8.1 ou superior, com PDO e PDO MySQL.
- MySQL 8+ ou MariaDB compatível.
- Servidor web Apache/Nginx ou servidor embutido do PHP para desenvolvimento.

## Preparar o banco

Execute `Procedure.txt` em um MySQL que permita criar as tabelas. Por exemplo:

```bash
mysql -u root -p < Procedure.txt
```

Em provedores gerenciados, o banco pode já ter sido criado e o usuário pode não ter permissão para `CREATE DATABASE`. Nesse caso, selecione o banco provisionado e ajuste as linhas iniciais de `Procedure.txt` (`CREATE DATABASE` e `USE`) para o nome fornecido pelo provedor antes de executar o restante do script.

Por padrão, o app conecta localmente a `127.0.0.1`, banco `empresa`, usuário `root` e senha vazia. Para outro ambiente, defina:

```text
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=empresa
DB_USER=seu_usuario
DB_PASS=sua_senha
DB_CHARSET=utf8mb4
```

Não use o usuário root nem senha vazia em produção. Configure as credenciais no ambiente do servidor; nunca as comite no repositório.

## Publicar na Vercel

O projeto inclui `Dockerfile.vercel` e `Caddyfile` com FrankenPHP, mais `vercel.json` para selecionar o runtime container. A Vercel constrói a imagem e encaminha as rotas para o servidor PHP. O container escuta a variável `PORT`, usa `80` como padrão e serve os arquivos estáticos do app.

A Vercel não fornece um banco MySQL persistente embutido. Antes do primeiro deploy, crie ou escolha um serviço **MySQL externo** que aceite conexões de saída da Vercel. Execute nele o esquema de `Procedure.txt` e confira a região, a porta e as exigências TLS do provedor. No projeto Vercel, abra **Settings → Environment Variables** e cadastre as variáveis abaixo para Production e, se quiser testar antes, Preview:

```text
APP_ENV=production
DB_HOST=host-do-mysql
DB_PORT=3306
DB_NAME=nome-do-banco
DB_USER=usuario-do-banco
DB_PASS=senha-do-banco
DB_CHARSET=utf8mb4
CSRF_SECRET=uma-chave-aleatoria-com-pelo-menos-32-caracteres
```

Gere um valor forte para `CSRF_SECRET`, por exemplo com `openssl rand -hex 32`. Não use esse exemplo literalmente nem compartilhe a chave. O app exige essa variável na Vercel porque assina os cookies CSRF e as mensagens temporárias sem depender de sessões gravadas no disco local do container.

Depois, importe o repositório no painel da Vercel, mantendo a raiz do projeto como **Root Directory**, e faça o deploy. O arquivo `Dockerfile.vercel` usa a extensão PHP `pdo_mysql`; não é necessário configurar um comando de build Node nem um diretório estático de saída.

Para testar a imagem localmente, em uma máquina com Docker instalado:

```bash
docker build -f Dockerfile.vercel -t armazem-vercel .
docker run --rm -p 8080:80 \
  -e PORT=80 \
  -e DB_HOST=host.docker.internal \
  -e DB_PORT=3306 \
  -e DB_NAME=empresa \
  -e DB_USER=seu_usuario \
  -e DB_PASS=sua_senha \
  -e CSRF_SECRET="$(openssl rand -hex 32)" \
  -e APP_ENV=production \
  armazem-vercel
```

O sistema de arquivos do container Vercel não é armazenamento permanente. Este app mantém os dados de negócio no MySQL remoto e usa cookies assinados, não arquivos de sessão locais. Se o MySQL bloquear conexões externas ou exigir uma CA/TLS específica, siga as instruções do provedor de banco antes do deploy.

## Executar localmente sem Docker

Na raiz do projeto:

```bash
php -S 127.0.0.1:8000
```

Acesse <http://127.0.0.1:8000>. Os arquivos Bootstrap e Bootstrap Icons são carregados por CDN, então a primeira renderização precisa de conexão à internet.

## Limites atuais

Este protótipo não possui autenticação, perfis de usuário, permissões, compras/vendas, edição ou exclusão de cadastros. Não o exponha publicamente com informações reais sem adicionar login, autorização e uma política de backup. Movimentações de estoque registradas são preservadas no histórico.
