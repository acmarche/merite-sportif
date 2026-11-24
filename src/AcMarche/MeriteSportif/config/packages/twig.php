<?php

use AcMarche\MeriteSportif\Setting\SettingService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'form_themes' => [
            'bootstrap_5_layout.html.twig',
            '@AcMarcheMeriteSportif/form/trix_widget.html.twig',
        ],
        'paths' => [
            '%kernel.project_dir%/src/AcMarche/MeriteSportif/templates' => 'AcMarcheMeriteSportif',
        ],
        'globals' => [
            'bootcdn' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css',
            'setting' => '@'.SettingService::class,
        ],
    ]);
};
