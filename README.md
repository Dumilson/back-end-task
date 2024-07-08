# Projeto To Do List

Este documento fornece instruções detalhadas sobre como configurar e executar o projeto <b>To do List</b>.

## Pré-requisitos

Antes de começar, certifique-se de ter os seguintes programas instalados em sua máquina:

- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)
- [PHP](https://www.php.net/) (versão 8.0 ou superior)
- [Node.js](https://nodejs.org/) 
- Um servidor de banco de dados como [MySQL](https://www.mysql.com/) ou [PostgreSQL](https://www.postgresql.org/)

## Passos para rodar a aplicação

### 1. Clonar o projeto do GitHub

Abra seu terminal e execute o seguinte comando para clonar o repositório:

```sh
git clone https://github.com/Dumilson/back-end-task.git
```

### 2. Instalar as dependências

Navegue até o diretório do projeto e instale as dependências do PHP usando o Composer:

```sh
cd back-end-task
composer install
```

### 3. Configurar as chaves no .env

Renomeie o arquivo `.env.example` para `.env`:

```sh
cp .env.example .env
```

Gere uma chave de aplicativo Laravel:

```sh
php artisan key:generate
```

### 4. Configurar o banco de dados

Abra o arquivo `.env` e configure as seguintes variáveis de ambiente de acordo com seu servidor de banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_de_dados
DB_USERNAME=seu_usuário
DB_PASSWORD=sua_senha
```

### 5. Migrar o banco de dados e rodar os seeders

Execute as migrações para criar as tabelas no banco de dados:

```sh
php artisan migrate
```

Em seguida, execute o seeder para adicionar os usuários ao banco de dados:

```sh
php artisan db:seed --class=UserSeeder
```

### 6. Informações pré-definidas inseridas pelo seed

O seeder `UserSeeder` irá adicionar os seguintes usuários ao banco de dados:

- **Admin User**
  - Email: admin@admin.com
  - Senha: 123456


Certifique-se de que o servidor do banco de dados está em execução e as credenciais configuradas no arquivo `.env` são corretas.

### 7. Iniciar o servidor de desenvolvimento

Finalmente, inicie o servidor de desenvolvimento do Laravel:

```sh
php artisan serve
```

A aplicação estará disponível em [http://localhost:8000](http://localhost:8000).

## Processo de Deploy

O projeto está configurado para deploy automático usando GitHub Actions. O fluxo de trabalho é o seguinte:

1. Todas as atualizações devem ser feitas na branch `develop`.
2. Quando as alterações estiverem prontas para ir para produção, abra um Pull Request da branch `develop` para a branch `master`.
3. Quando o Pull Request for aprovado e mesclado na branch `master`, o GitHub Actions iniciará o processo de deploy automaticamente.

### Documentação de API em Produção 

<a href="https://todo.domingosbraganha.tech/">https://todo.domingosbraganha.tech/</a>



