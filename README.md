# Funtastic House — Website & Sistema de Reservas

Site institucional e painel de administração para **Funtastic House**, alojamento turístico temático localizado na zona Oeste de Portugal (Sintra / Ericeira / Mafra). O projeto inclui um site público bilingue (PT/EN), formulário de reservas, galeria interativa, mapa de pontos de interesse e um painel de administração completo.

---

## Stack Tecnológica

| Camada | Tecnologia |
|--------|-----------|
| Backend | PHP 8.2 + Laravel 12 |
| Base de dados | MySQL 8 |
| Frontend público | Bootstrap 5.3, Alpine.js v3, GLightbox, Leaflet.js 1.9.4 |
| Painel admin | Bootstrap 5.3, Alpine.js v3, FullCalendar 6 |
| Mapas | Leaflet.js + OpenStreetMap + Nominatim (geocoding) |
| Email | Laravel Mail (SMTP configurável) |
| Servidor local | XAMPP (Apache + MySQL) |

---

## Funcionalidades

### Site Público
- Página inicial com hero, galeria, destaques e CTA
- Página de cada experiência (Casa Imersiva / Casa Spa) com lightbox de imagens
- Galeria filtrada por categoria
- Mapa interativo "O Que Fazer" com POIs, filtros por categoria, animações `flyTo` e interação bidirecional mapa↔lista
- Formulário de reservas com validação, calendário de disponibilidade e envio de email de confirmação
- Suporte bilingue PT/EN com deteção automática de idioma

### Painel de Administração (`/admin`)
- Dashboard com estatísticas de reservas
- Gestão de reservas (calendário mensal, detalhe, mudança de estado)
- Gestão de experiências (preços, disponibilidade, dados)
- Calendário de preços por período (pricing rules)
- Galeria de imagens (upload múltiplo, reordenação, activar/desactivar)
- Gestão de Pontos de Interesse com geocoding por morada e pin arrastável no mapa
- Gestão de utilizadores (criar, editar, eliminar)
- Configurações gerais, contactos, redes sociais e SEO

---

## Instalação

### Requisitos
- PHP 8.2+
- Composer
- MySQL 8+
- Apache com `mod_rewrite` activo (XAMPP ou similar)

### Passos

```bash
# 1. Clonar o repositório
git clone https://github.com/<repo>/funtastichouse.git
cd funtastichouse

# 2. Instalar dependências PHP
composer install

# 3. Copiar e configurar o .env
cp .env.example .env
php artisan key:generate

# 4. Configurar a base de dados no .env (ver secção abaixo)

# 5. Importar o dump da base de dados
mysql -u root -p funtastichouse < funtastichouse_bd.sql

# 6. Criar o link de storage (se necessário)
php artisan storage:link
```

---

## Configuração `.env` Essencial

```env
APP_NAME="Funtastic House"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://funtastichouse.pt

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=funtastichouse
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.exemplo.pt
MAIL_PORT=587
MAIL_USERNAME=noreply@funtastichouse.pt
MAIL_PASSWORD=password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@funtastichouse.pt
MAIL_FROM_NAME="Funtastic House"
```

---

## VirtualHost Apache (produção)

```apacheconf
<VirtualHost *:80>
    ServerName funtastichouse.pt
    ServerAlias www.funtastichouse.pt
    DocumentRoot /var/www/funtastichouse/public

    <Directory /var/www/funtastichouse/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/funtastichouse_error.log
    CustomLog ${APACHE_LOG_DIR}/funtastichouse_access.log combined
</VirtualHost>
```

---

## Estrutura de Pastas Relevante

```
funtastichouse/
├── app/
│   ├── Http/Controllers/          # Controladores (público + admin)
│   ├── Models/                    # Eloquent models
│   └── Mail/                      # Templates de email
├── resources/
│   ├── views/
│   │   ├── layouts/               # Layouts base (site + admin)
│   │   ├── admin/                 # Vistas do painel de administração
│   │   └── ...                    # Páginas públicas
│   └── lang/                      # Traduções PT/EN
├── routes/
│   └── web.php                    # Todas as rotas
├── database/
│   └── migrations/                # Migrações da base de dados
└── public/
    └── images/                    # Imagens do site (galeria, logo, etc.)
```

---

## Acesso ao Painel de Administração

URL: `/admin/login`

Credenciais de acesso definidas via seeder ou criadas directamente na tabela `users`.

---

## Segurança

- Rate limiting no login (`throttle:5,1`) e no formulário de reservas (`throttle:10,1`)
- Validação de uploads por MIME type detectado pelo servidor (não pela extensão do cliente)
- Nomes de ficheiros aleatórios via `Str::uuid()` — sem exposição de dados originais
- Middleware de autenticação em todas as rotas do painel admin
- CSRF protection activo em todos os formulários
- Timezone configurada para `Europe/Lisbon`

---

## Desenvolvido por

**João Sumbo** — Workmind  
Desenvolvimento web full-stack · 2025
