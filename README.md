# API CRUD

## Como clonar o projeto

- `git clone https://github.com/ericvalle146/api-crud.git`
- `cd api-crud`

## Como rodar o projeto

1. Copie o arquivo de ambiente:
   - `cp .env.example .env`
2. Instale as dependencias PHP:
   - `composer install`
3. Suba os containers com o Sail:
   - `./vendor/bin/sail up -d`
4. Configure as variaveis do `.env` para o banco padrao Postgres iniciado pelo Sail.
5. Gere a key e rode as migrations:
   - `./vendor/bin/sail artisan key:generate`
   - `./vendor/bin/sail artisan migrate`

Se precisar parar os containers:
- `./vendor/bin/sail down`

## Como rodar os testes

- `./vendor/bin/sail test`

## Rotas de api
- `As rotas de API você encontra na pasta routes/doc`


## Decisões técnicas adotadas

- Laravel como framework principal pela produtividade e padrão de organização.
- Laravel Sail para padronizar o ambiente com Docker.
- PostgreSQL como banco padrão (definido no `compose.yaml`).
- Actions Pattern para isolar regras de negócio por caso de uso em `app/Actions`.
- DTOs com `wendelladriel/laravel-validated-dto` para validar e tipar entrada de dados.
- Laravel Pint para padronizar o estilo do código PHP.
- Laravel Sanctum para autenticação
- Laravel Permission para associar usuários a funções e permissões.

## Como formatar o codigo

- `./vendor/bin/sail pint`
