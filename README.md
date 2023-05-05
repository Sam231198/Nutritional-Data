# Nutritional Data
Este projeto tem por finalidade extrair dados nutricionais de alimentos diariamente para facilitar a busca dos dados nutricionais dos alimentos.

## Tecnologias
- [PHP 8.1.1](https://www.php.net/)
- [Laravel 10](https://laravel.com/docs/10.x/installation)
- [PostgresSQL 15](https://www.postgresql.org/) (Opcional)

## Como instalar as dependencias
Apos ter instalado todas as ferramentas a acima, faço o clone desse repositório e siga os passos da isntrução:

Para instalar todas a dependencias da aplicação, execute este comando na raiz do projeto:
```bash
composer isntall
```

## Banco de dados
Para o desenvolviemnto do projeto foi utilizado o PostgresSQL 15, no entanto você poderá utilizar qualquer banco realacional que estiver mais familiarizado.

Primeiro vamos configurar o `.env` com os dados do banco:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nutricional
DB_USERNAME=postgres
DB_PASSWORD=11111
```

Apos a configuração do `.env` execute este comando para a criação das tabela:
```bash
php artisan migrate
```
Pronto! Agora o banco de dados já esta pronto para ser utilizado.

## Executando a api
Execute este comando para rodar o projeto:
```bash
php artisan serve
```
O projeto estará rodando na porta local `:8000`.

## Rodar a cron
Caso você queira rodar a cron manualmente, basta executar esse comando:
```bash
php artisan schedule:run
```

Para execcutar apenas a função que a cron chama, execute este comando:
```bash
php artisan app:atualizar-dados
```

Agora basta adicioanr esta linha na sua cron para a execução automatica:
```bash
* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1
```

## Rodar teste unitários
Caso você queira rodar os teste unitários, basta executar o comando a baixo:
```bash
php artisan test --testsuite=Unit --filter NutricionalTest
```

## Endpoint

|Metodo|Endpoint|parametros|Detalhes|
|-|-|-|-|
|`GET`|`/`|None|Exibe os dados da aplicação.|
|`GET`|`/prodructs`|"?where=key:value;key:value&orderBy=key:asc;key:desc"; [Lista de parametros permitidos](###-Lista-de-parametros)|acessar a lista completa dos dados nutricionais dos alimentos.|
|`PUT`|`/prodructs/:code`|Exemplo: `{"label": "test"}`; [Lista de parametros permitidos](###-Lista-de-parametros)|Atualiza os dados da apliação.|
|`DELETE`|`/prodructs/:code`|None| Atualiza `status` para `trash`|
|`GET`|`/prodructs/:code`|None|Busca um alimento especifico pelo código.|

### Lista de parametros
imported_t
- url
- status (excerto para o metodo `PUT`)
- creator
- created_t
- last_modified_t
- product_name
- quantity
- brands
- categories
- labels
- cities
- purchase_places
- stores
- ingredients_text
- traces
- serving_size
- serving_quantity
- nutriscore_score
- nutriscore_grade
- main_category
- image_url

[Video explicativo](https://www.loom.com/embed/72d317b33dfc4a708343c3c8f622570a)
> This is a challenge by [Coodesh](https://coodesh.com/)