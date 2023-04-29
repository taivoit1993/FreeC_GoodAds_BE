## SETUP BE
- Use: **docker compose up -d** to start docker
- Use command: **docker exec -it server_container bash** access to source code
- Run command: **apt get update**
- Then: **composer update**
- Copy env: **cp .env.example .env**
- Run migrate: **php artisan migrate**
- Access http://localhost:9000/ url backend

### Please use account google to test:
- email: **taivo.devops@gmail.com**
- pass: **taivodevops2023**