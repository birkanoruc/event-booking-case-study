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

### Postman Collection

-   **\* https://www.postman.com/lunar-module-operator-3893016/event-booking-case/overview \_**

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

Artık uygulamanız çalışıyor ve API'yi kullanabilirsiniz.
