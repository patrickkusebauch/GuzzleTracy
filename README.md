# Neo4jTracy
A Tracy debug bar for Guzzle Connection

## Example

```php
<?php

use DanceEngineer\GuzzleTracy\Panel;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Profiling\Middleware;
use Tracy\Debugger;

final class FitBitClientFactory
{

    public static function create(): Client
    {
        $panel = new Panel();
        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        
        $stack->push(new Middleware($panel));
        $debugBar = Debugger::getBar();
        $debugBar->addPanel($panel, 'fitbit');
        
        return new Client(['handler' => $stack]);
    }
}
```