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

## Email (Mailtrap)

Configure no `.env` (exemplo no `.env.example`):

- `MAIL_MAILER=smtp`
- `MAIL_HOST=sandbox.smtp.mailtrap.io`
- `MAIL_PORT=2525`
- `MAIL_USERNAME=`
- `MAIL_PASSWORD=`
- `MAIL_ENCRYPTION=tls`
- `MAIL_FROM_ADDRESS=noreply@example.com`
- `MAIL_FROM_NAME=`

### Como testar envio de emails

1. Ative o worker de fila:
   - `./vendor/bin/sail artisan queue:work`
2. Dispare um fluxo:
   - Criar usuário (envia boas‑vindas)
   - Atualizar task para `completed` (envia email de task concluída)
3. Verifique o inbox do Mailtrap.

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
- Laravel Eventos, Listeners, Mail para gerenciamento de envio de emails
- Processar tarefas via Filas e Jobs

## Como formatar o codigo

- `./vendor/bin/sail pint`
