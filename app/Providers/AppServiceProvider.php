<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Uygulamanın başlatılması sırasında, modelin lazy loading (tembel yükleme) özelliği kontrol edilir.
     * Üretim ortamı dışında lazy loading devre dışı bırakılır.
     * Lazy Loading devre dışı bırakıldığında, N+1 sorgu problemleri oluşmasını engellemek için geliştiriciler eager loading kullanmaya zorlanır.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
    }
}
