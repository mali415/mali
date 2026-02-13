# Terminal Takip Portalı - Sprint 1

## Kurulum
1. XAMPP kurulumu sonrası Apache ve MySQL'i çalıştırın.
2. MySQL'de `terminal_portal` isimli bir veritabanı oluşturun.
3. `database/schema.sql` dosyasını içe aktarın.
4. `database/seed.sql` dosyasını içe aktarın.
5. Projeyi `htdocs/terminal-portal` altına koyun ve Apache web root olarak `/public` klasörünü işaretleyin.
6. Giriş: `admin@local` / `Admin123!`

## Route Listesi
### UI
- GET `/login`
- POST `/login`
- GET `/logout`
- GET `/dashboard`
- GET `/machines`
- GET `/machines/add`
- GET `/machines/edit?id=`

### API
- POST `/api/auth/login`
- POST `/api/auth/logout`
- GET `/api/auth/me`
- GET `/api/machines`
- POST `/api/machines`
- GET `/api/machines/{id}`
- PUT `/api/machines/{id}`
- DELETE `/api/machines/{id}`

## Permission Listesi
- `auth.login` : Oturum açma
- `dashboard.view` : Dashboard görüntüleme
- `machine.view` : Makine listeleme
- `machine.create` : Makine oluşturma
- `machine.edit` : Makine düzenleme
- `machine.delete` : Makine silme
- `audit.view` : Audit kayıtlarını görüntüleme

## Dev Notları
Yeni bir modül eklemek için:
1. `app/models` altına model ekleyin.
2. `app/controllers` altında controller oluşturun.
3. `app/middleware/permission_mw.php` üzerinden gerekli permission kodunu doğrulayın.
4. `public/index.php` içerisinde route tanımı ekleyin.
5. `views/pages` altına liste/form sayfalarını ekleyin ve AdminLTE layoutlarını kullanın.
