<?php

namespace GlpiPlugin\Archisw\Capacity;

use Glpi\Asset\Capacity\AbstractCapacity;
use Glpi\Asset\CapacityConfig;

class AppStructureCapacity extends AbstractCapacity
{
    public function getLabel(): string
    {
        return __('Apps structures', 'archisw');
    }

    public function getIcon(): string
    {
        return 'ti ti-hierarchy';
    }

    public function getDescription(): string
    {
        return __('Link application structures (software components) to this asset', 'archisw');
    }

    public function getSearchOptions(string $classname): array
    {
        return [];
    }

    public function getCloneRelations(): array
    {
        return [];
    }

    public function isUsed(string $classname): bool
    {
        return false;
    }

    public function getCapacityUsageDescription(string $classname): string
    {
        return '';
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        // Registra il custom asset come tipo associabile in archisw
        \PluginArchiswSwcomponent::registerType($classname);

        // Aggiunge la tab "Apps structures" sull'asset custom
        \CommonGLPI::registerStandardTab($classname, \PluginArchiswSwcomponent_Item::class);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
    }
}