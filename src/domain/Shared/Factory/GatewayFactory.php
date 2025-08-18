<?php

namespace Domain\Shared\Factory;

use Domain\Shared\Contracts\ServiceGatewayContract;

class GatewayFactory
{
    public static function build(string $gateway): ServiceGatewayContract
    {
        
        if (! $gateway) {
            throw new \InvalidArgumentException('Argumento inválido');
        }

        $serviceName = ucfirst(strtolower($gateway));

        $className = 'Domain\\GatewayServices\\'.$serviceName.'\\Services\\'.$serviceName.'Service';

        if (! class_exists($className)) {
            throw new \InvalidArgumentException('namespace failed');
        }

        return new $className;
    }
}
