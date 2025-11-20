<?php

namespace App\Providers\Filament;

use App\Filament\Resources\RoleResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use App\Filament\Pages\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarFullyCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->resources([
                RoleResource::class,
            ])
            ->login(Login::class)
                        ->renderHook(
                'panels::head.start',
                fn() => '<script>
        document.addEventListener("DOMContentLoaded", function () {
            const originalTitle = document.title;
            if (originalTitle.includes(" - ")) {
                const parts = originalTitle.split(" - ");
                document.title = parts[1] + " | " + parts[0];
            }
        });
    </script>'
            )
            ->font('Poppins')
            // ->viteTheme('resources/css/filament/admin/login.css')
            ->darkMode(false)
            ->brandName('Admin Knowledge Base KelolaHR')
            ->brandLogo(asset('https://www.kelolahr.id/wp-content/uploads/2023/09/new-logo-khr.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('https://play-lh.googleusercontent.com/Zo06LmwO3b3uh9h7W-GycTOkz12hlqP2wlTg0A1gpo26ffb2_SPHLiWSnqBpHP9fe7s'))
            ->colors([
                'primary' => [
                    50  => '#E6F2F1',
                    100 => '#CCE5E3',
                    200 => '#99CBC7',
                    300 => '#66B1AB',
                    400 => '#33978F',
                    500 => '#045257', // utama
                    600 => '#034A4E',
                    700 => '#024245',
                    800 => '#02393C',
                    900 => '#013133',
                ],
                'secondary' => [
                    50  => '#FFF3E6',
                    100 => '#FFE7CC',
                    200 => '#FFCF99',
                    300 => '#FFB766',
                    400 => '#FF9F33',
                    500 => '#FC9E49', // utama
                    600 => '#E68F42',
                    700 => '#CC7E3A',
                    800 => '#B36E33',
                    900 => '#995E2B',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
