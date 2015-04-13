<?php

namespace RouteInheritance;

use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\Router\Exception\InvalidArgumentException;
use Zend\Stdlib\ArrayUtils;

class Module
{
    /**
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();

        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'extendRoutes'));
    }

    /**
     * @param ModuleEvent $e
     * @throws InvalidArgumentException
     */
    public function extendRoutes(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);

        // Allow extension of routes with overriding capability
        if (isset($config['router']['route-inheritance']) && is_array($config['router']['route-inheritance'])) {
            foreach ($config['router']['route-inheritance'] as $newRoute => $routeConfig) {
                // Not going to override any existing routes
                if (isset($config['router']['routes'][$newRoute])) {
                    throw new InvalidArgumentException('Cannot extend route to existing route id.');
                }

                // parent route must be provided
                if (!isset($routeConfig['extends'])) {
                    throw new InvalidArgumentException('Parent route must be defined.');
                }

                // parent route must exist
                if (!isset($config['router']['routes'][$routeConfig['extends']])) {
                    throw new InvalidArgumentException('Parent route does not exist.');
                }

                // If there are overrides provided, they must be iterable
                if (isset($routeConfig['overrides']) && !is_array($routeConfig['overrides'])) {
                    throw new InvalidArgumentException('Route overrides must be iterable.');
                }

                $newRouteConfig = $config['router']['routes'][$routeConfig['extends']];
                $newRouteConfig = ArrayUtils::merge($newRouteConfig, $routeConfig['overrides']);
                $config['router']['routes'][$newRoute] = $newRouteConfig;
            }
        }

        // Pass the changed configuration back to the listener:
        $configListener->setMergedConfig($config);
    }
}
