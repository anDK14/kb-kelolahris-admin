<?php

namespace App\Filament\Widgets;

use App\Models\Faq;
use App\Models\Module;
use App\Models\Submodule;
use App\Models\MobileFeature;
use App\Models\MobileModule;
use App\Models\WebsiteFeatureContent;
use App\Models\MobilefeatureContent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Statistik Website
            Stat::make('Total Modul Website', Module::count())
                ->description('Jumlah modul website')
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('primary')
                ->url(route('filament.admin.resources.modules.index')),

            Stat::make('Total Fitur Website', Module::count())
                ->description('Jumlah fitur website')
                ->descriptionIcon('heroicon-o-globe-alt')
                ->color('secondary')
                ->url(route('filament.admin.resources.submodules.index')),

            Stat::make('Konten Website', WebsiteFeatureContent::count())
                ->description('Total konten website')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('success')
                ->url(route('filament.admin.resources.website-feature-contents.index')),

            // Statistik Mobile
            Stat::make('Total Modul Mobile', MobileModule::count())
                ->description('Jumlah modul mobile')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('primary')
                ->url(route('filament.admin.resources.mobile-modules.index')),

            Stat::make('Total Fitur Mobile', MobileFeature::count())
                ->description('Jumlah fitur mobile')
                ->descriptionIcon('heroicon-o-globe-alt')
                ->color('secondary')
                ->url(route('filament.admin.resources.mobile-features.index')),

            Stat::make('Konten Mobile', MobileFeatureContent::count())
                ->description('Total konten mobile')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('success')
                ->url(route('filament.admin.resources.mobile-feature-contents.index')),

            // Statistik FAQ
            // Stat::make('Total FAQ', Faq::count())
            //     ->description('Pertanyaan yang sering ditanyakan')
            //     ->descriptionIcon('heroicon-m-question-mark-circle')
            //     ->color('warning'),

            // // Statistik Views
            // Stat::make('Total Views Website', Submodule::sum('view_count'))
            //     ->description('Total view fitur website')
            //     ->descriptionIcon('heroicon-m-eye')
            //     ->color('info'),

            // Stat::make('Total Views Mobile', MobileFeature::sum('view_count'))
            //     ->description('Total view fitur mobile')
            //     ->descriptionIcon('heroicon-m-eye')
            //     ->color('info'),
        ];
    }
}