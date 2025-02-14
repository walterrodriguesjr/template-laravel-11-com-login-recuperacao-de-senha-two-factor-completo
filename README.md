<p align="center"> <a href="https://laravel.com" target="_blank"> <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"> </a> </p> <p align="center"> <a href="https://github.com/laravel/framework/actions"> <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"> </a> <a href="https://packagist.org/packages/laravel/framework"> <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"> </a> </p>

---

Template Laravel 11 - Login, Recuperação de Senha e Autenticação em Dois Fatores
Este é um template completo e pronto para uso baseado no Laravel 11, projetado para acelerar o desenvolvimento de projetos SaaS com uma base segura e escalável.

🛠 Recursos Principais
✅ Autenticação de Usuários (Login e Logout).
✅ Recuperação de Senha via e-mail.
✅ Autenticação em Dois Fatores (2FA) com código enviado por e-mail.
✅ Gerenciamento de Sessões Ativas (encerre sessões remotamente).
✅ Histórico de Alterações no perfil do usuário.
✅ Exportação de Dados (JSON, CSV).
✅ Exclusão de Conta com Código de Confirmação.
✅ Painel de Controle Responsivo usando AdminLTE + Bootstrap 5.
✅ Integração com Docker e Laravel Sail para um ambiente pronto para produção.
✅ Banco de Dados PostgreSQL com persistência de dados.
✅ **Configuração de ambiente otimizada com Nginx + PHP + PostgreSQL.

📌 Pré-requisitos
Antes de iniciar, certifique-se de ter instalado:

Docker e Docker Compose
PHP 8.2+ (caso não utilize Docker)
Node.js 18+ e NPM 9+ (para build frontend)

🚀 Como Configurar
1️⃣ Clone o Repositório
git clone https://github.com/walterrodriguesjr/template-laravel-11-com-login-recuperacao-de-senha-two-factor-completo.git

cd template-laravel-11-com-login-recuperacao-de-senha-two-factor-completo

2️⃣ Configuração Inicial
Copie o arquivo .env.example para .env:
cp .env.example .env

Gere a chave da aplicação:
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php82-composer:latest php artisan key:generate

3️⃣ Subir os Containers
Inicie o ambiente Docker com:
docker-compose up -d --build

4️⃣ Instalar Dependências
PHP:
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php82-composer:latest composer install

JavaScript & Frontend:
docker exec -it laravel_app bash
npm install && npm run dev

5️⃣ Executar Migrations e seed padrao
docker exec -it laravel_app bash
php artisan migrate --seed --seeder=AdminUserSeeder

6️⃣ Acessar o Projeto
Acesse o painel no navegador:
http://localhost:8000

7️⃣ logando 
email - laravel_template@gmail.com
senha - Laraveltemplate001!

🔧 Configuração do Docker
O projeto inclui um ambiente Docker configurado com:

📌 Aplicação PHP 8.2 rodando Laravel
📌 Servidor Web Nginx para gerenciar requisições
📌 Banco de Dados PostgreSQL com persistência de dados

📄 Arquivo docker-compose.yml

services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: laravel_app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - .:/var/www
      - ./docker/php/php.ini:/usr/local/etc/php/php.ini
    networks:
      - laravel_network
    depends_on:
      - db

  web:
    image: nginx:latest
    container_name: laravel_web
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - .:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - laravel_network
    depends_on:
      - app

  db:
    image: postgres:latest
    container_name: laravel_db
    restart: unless-stopped
    environment:
      POSTGRES_USER: laravel
      POSTGRES_PASSWORD: secret
      POSTGRES_DB: laravel_db
    ports:
      - "5432:5432"
    volumes:
      - postgres_data:/var/lib/postgresql/data
    networks:
      - laravel_network

networks:
  laravel_network:

volumes:
  postgres_data:

🎨 Tecnologias Utilizadas
Laravel 11 - Framework principal
AdminLTE + Bootstrap 5 - UI moderna
Vite - Build otimizado
Choices.js - Substituição do Select2
SweetAlert2 - Notificações elegantes
jQuery Validation - Validação de formulários
DataTables - Listagem dinâmica
Docker + Sail - Ambiente pronto para produção
PostgreSQL - Banco de dados confiável e escalável

🎯 Estrutura do Projeto
📁 app/
📁 bootstrap/
📁 config/
📁 database/
📁 docker/                   # Arquivos de configuração Docker
    ├── php/
    │   ├── Dockerfile       # Configuração PHP
    │   ├── php.ini          # Configuração do PHP
    ├── nginx/
    │   ├── default.conf     # Configuração Nginx
📁 public/
📁 resources/
   ├── views/                # Arquivos Blade (Frontend)
   ├── js/                   # Scripts JS organizados por funcionalidade
   ├── css/                  # Estilos personalizados
📁 routes/
📁 storage/
📁 tests/
📄 .env.example
📄 docker-compose.yml
📄 vite.config.js
📄 package.json

💡 Como Contribuir
Contribuições são sempre bem-vindas! 🚀

Faça um fork do projeto.
Crie um branch para a sua feature (git checkout -b minha-feature).
Commite suas mudanças (git commit -m 'Adicionei uma nova funcionalidade').
Push para o seu fork (git push origin minha-feature).
Abra um Pull Request.

📞 Contato
💻 Autor: Walter Rodrigues Jr.
📧 Email: walter@example.com
📌 GitHub: github.com/walterrodriguesjr
