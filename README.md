# Online Etkinlik Biletleme ve Rezervasyon Sistemi

Bu proje, online bir etkinlik biletleme ve rezervasyon sisteminin REST API'sini geliştirmeyi amaçlamaktadır. Sistem, etkinlik listeleme, koltuk rezervasyonu ve bilet satın alma işlemlerini yönetmektedir. API, etkinlikler, koltuklar, rezervasyonlar ve bilet işlemleri gibi temel işlevleri yerine getirir.

## Teknolojiler

Bu proje aşağıdaki teknolojilerle geliştirilmiştir:

-   PHP 8.1+
-   Laravel/Symfony Framework
-   MySQL/PostgreSQL
-   JWT (JSON Web Token) authentication
-   RESTful API standartları

## Gereksinimler

-   PHP 8.1 veya daha yeni bir sürüm
-   Composer
-   Veritabanı (MySQL/PostgreSQL)

## API Endpoint'leri

### Authentication Endpoints

-   **POST /api/auth/register**: Kayıt olma
-   **POST /api/auth/login**: Giriş yapma
-   **POST /api/auth/refresh**: Token yenileme
-   **POST /api/auth/logout**: Çıkış yapma

### Event Endpoints

-   **GET /api/events**: Tüm etkinliklerin listelenmesi, filtreleme
-   **GET /api/events/{id}**: Etkinlik detaylarının listelenmesi
-   **POST /api/events**: Yeni etkinlik oluşturma (Admin only)
-   **PUT /api/events/{id}**: Etkinlik güncelleme (Admin only)
-   **DELETE /api/events/{id}**: Etkinlik Silme/İptal (Admin only)

### Seat Endpoints

-   **GET /api/events/{id}/seats**: Etkinliğe ait koltuk detayları
-   **GET /api/venues/{id}/seats**: Mekana ait koltuk detayları
-   **POST /api/seats/block**: Koltuk bloklama
-   **DELETE /api/seats/release**: Koltuk blok kaldırma

### Reservation Endpoints

-   **POST /api/reservations**: Rezervasyon oluşturma
-   **GET /api/reservations**: Rezervasyon listeleme
-   **GET /api/reservations/{id}**: Rezervasyon detaylarının listelenmesi
-   **POST /api/reservations/{id}/confirm**: Rezervasyon onaylama
-   **DELETE /api/reservations/{id}**: Rezervasyon iptali/silme

## Kurulum

1. Projeyi klonlayın:

    ```bash
    git clone https://github.com/birkanoruc/event-booking-case-study.git
    cd event-booking-case-study
    ```

2. Bağımlılıkları yükleyin:

    ```bash
    composer install
    ```

3. `.env` dosyasını yapılandırın (veritabanı bağlantısı, JWT ayarları, vb.)

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=event_booking_case
    DB_USERNAME=root
    DB_PASSWORD=

    AUTH_GUARD=api

    TOKEN_TYPE=jwt

    JWT_SECRET=sMjjQPcswf58aeZfhtNuH2x8eWdcrXwaLMRtLAQ3Fu8dYK1kpzQjeMtTXalS75DT
    JWT_TTL=60
    JWT_REFRESH_TTL=20160

    API_RATE_LIMIT=60
    API_RATE_LIMIT_TIME=1

4. Veritabanını oluşturun ve migrations'ları çalıştırın:

    ```bash
    php artisan migrate --seed
    ```

5. Uygulamayı çalıştırın:

    ```bash
    php artisan serve
    ```

### Postman Collection

-   \*\*https://www.postman.com/lunar-module-operator-3893016/event-booking-case/overview

## Kullanım

1. Postman collections ve environments export ederek açın, global env ayarlarını api için yapılandırın.
2. --seed oluşturulan ile sahte venue ve seats verileri veritabanınızda olacaktır.
3. admin@gmail.com ve admin123 bilgileri ile adminlere özel endpointlere erişim sağlayabilirsiniz.
4. Etkinlik oluşturun, ardından etkinlik id'si ile koltukları listeleyin.
5. Bir koltuk seçin ve bloklamaya çalışın.
6. Koltuk blokladığınızdan emin olduktan sonra rezervasyon oluşturun.
7. Tickets endpointleri çalışmamaktadır.

## Neleri Barındırıyor?

1. JWT Token
2. Restfull API standartlarında cevaplar
3. Koltuk müsaitlik kontrolü
4. 15 dakikalık rezervasyon kontrolü (dinamik olarak accessor ile kontrol sağlanıyor)
5. Eşzamanlı koltuk revize engeli
6. Rezervasyon süresi dolduğunda koltuklar otomatik serbest bırakma.
7. İnput validate.
8. Uygun hata mesajlar.
9. Request/Response logging.
10. Uygun http durum kodları.
11. Rate limiting.
12. Repository ve Service Layer design pattern
13. n+1 problemleri için with kullanımlar.
14. Kod yorumları.
