<?php

namespace Charcoal\PropertyFilter;

// from charcoal-app
use Charcoal\App\Module\AbstractModule;

/**
 * Property Filter Module
 */
class PropertyFilterModule extends AbstractModule
{
    const ADMIN_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-property-filter/config/admin.json';
    const APP_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-property-filter/config/config.json';

    /**
     * Setup the module's dependencies.
     *
     * @return AbstractModule
     */
    public function setup()
    {
        $container = $this->app()->getContainer();

        // Define ServiceProviders and Config if needed.

        return $this;
    }
}
