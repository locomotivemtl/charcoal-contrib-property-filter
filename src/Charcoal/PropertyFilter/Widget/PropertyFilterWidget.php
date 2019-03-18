<?php

namespace Charcoal\PropertyFilter\Widget;

// from charcoal-admin
use Charcoal\Admin\AdminWidget;
use Charcoal\Admin\Support\HttpAwareTrait;
use Charcoal\Admin\Widget\FormPropertyWidget;

// from charcoal-factory
use Charcoal\Factory\FactoryInterface;

// from charcoal-core
use Charcoal\Model\ModelInterface;

// from charcoal-property
use Charcoal\Property\PropertyInterface;

// from charcoal-ui
use Charcoal\Ui\Layout\LayoutAwareInterface;
use Charcoal\Ui\Layout\LayoutAwareTrait;

// from pimple
use Pimple\Container;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class JobFiltersWidget
 */
class PropertyFilterWidget extends AdminWidget implements
    LayoutAwareInterface
{
    use HttpAwareTrait;
    use LayoutAwareTrait;

    /**
     * @var PropertyInterface[]|array $propertyFilters
     */
    private $propertyFilters;

    /**
     * @var string $objType
     */
    protected $objType;

    /**
     * @var ModelInterface $proto
     */
    private $proto;

    /**
     * Store the factory instance for the current class.
     *
     * @var FactoryInterface
     */
    private $widgetFactory;

    /**
     * @var array $propertiesOptions
     */
    private $propertiesOptions = [];

    /**
     * @param Container $container Pimple DI container.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        // Satisfies HttpAwareTrait dependencies
        $this->setHttpRequest($container['request']);

        $this->setWidgetFactory($container['widget/factory']);

        /** Satisfies {@see \Charcoal\Ui\Layout\LayoutAwareInterface} */
        $this->setLayoutBuilder($container['layout/builder']);
    }

    /**
     * @return string
     */
    public function type()
    {
        return 'charcoal/property-filter/widget/property-filter';
    }

    /**
     * @param array $data The widget data.
     * @return self
     */
    public function setData(array $data)
    {
        parent::setData($data);

        $this->mergeDataSources($data);

        return $this;
    }

    /**
     * Retrieve the default data sources (when setting data on an entity).
     *
     * @return string[]
     */
    protected function defaultDataSources()
    {
        return [static::DATA_SOURCE_REQUEST, static::DATA_SOURCE_OBJECT];
    }

    /**
     * Retrieve the accepted metadata from the current request.
     *
     * @return array
     */
    public function acceptedRequestData()
    {
        return [
            'obj_type',
        ];
    }

    /**
     * Fetch metadata from the current request.
     *
     * @return array
     */
    public function dataFromRequest()
    {
        return $this->httpRequest()->getParams($this->acceptedRequestData());
    }

    /**
     * @return \Generator
     */
    public function filters()
    {
        $obj         = $this->proto();
        $props       = $obj->metadata()->properties();
        $propOptions = $this->propertiesOptions();

        $activeFilters = array_intersect_key($props, array_flip($this->propertyFilters()));

        foreach ($activeFilters as $propertyIdent => $propertyMetadata) {
            $prop = $this->createFormProperty($propertyMetadata);
            $prop->setPropertyIdent($propertyIdent);

            if (!empty($propOptions[$propertyIdent])) {
                $propertyOptions = $propOptions[$propertyIdent];

                if (is_array($propertyOptions)) {
                    $prop->mergePropertyData($propertyOptions);
                }
            }

            yield $propertyIdent => $prop;
        }
    }

    /**
     * @param array $data Optional. The form property data to set.
     * @return FormPropertyWidget
     */
    public function createFormProperty(array $data = null)
    {
        $p = $this->widgetFactory()->create(FormPropertyWidget::class);
        if ($data !== null) {
            $p->setData($data);
        }

        return $p;
    }

    /**
     * @param boolean $reload If true, reload will be forced.
     * @throws InvalidArgumentException If the object type is not defined / can not create prototype.
     * @return object
     */
    private function proto($reload = false)
    {
        if ($this->proto === null || $reload) {
            $objType = $this->objType();
            if ($objType === null) {
                throw new InvalidArgumentException(sprintf(
                    '%s Can not create an object prototype: object type is null.',
                    get_class($this)
                ));
            }
            $this->proto = $this->modelFactory()->create($objType);
        }

        return $this->proto;
    }

    // GETTERS AND SETTERS
    // ==========================================================================

    /**
     * @return array|PropertyInterface[]
     */
    public function propertyFilters()
    {
        return $this->propertyFilters;
    }

    /**
     * @param array|PropertyInterface[] $propertyFilters PropertyFilters for JobFiltersWidget.
     * @return self
     */
    public function setPropertyFilters($propertyFilters)
    {
        $this->propertyFilters = $propertyFilters;

        return $this;
    }

    /**
     * @return string
     */
    public function objType()
    {
        return $this->objType;
    }

    /**
     * @param string $objType ObjType for JobFiltersWidget.
     * @return self
     */
    public function setObjType($objType)
    {
        $this->objType = $objType;

        return $this;
    }

    /**
     * Retrieve the widget factory.
     *
     * @throws RuntimeException If the widget factory was not previously set.
     * @return FactoryInterface
     */
    protected function widgetFactory()
    {
        if ($this->widgetFactory === null) {
            throw new RuntimeException(sprintf(
                'Widget Factory is not defined for "%s"',
                get_class($this)
            ));
        }

        return $this->widgetFactory;
    }

    /**
     * Set an widget factory.
     *
     * @param FactoryInterface $factory The factory to create widgets.
     * @return void
     */
    private function setWidgetFactory(FactoryInterface $factory)
    {
        $this->widgetFactory = $factory;
    }

    /**
     * @param array $properties The options to customize the group properties.
     * @return self
     */
    public function setPropertiesOptions(array $properties)
    {
        $this->propertiesOptions = $properties;

        return $this;
    }

    /**
     * @return array
     */
    public function propertiesOptions()
    {
        return $this->propertiesOptions;
    }
}
