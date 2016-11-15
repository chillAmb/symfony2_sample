<?php
namespace Plugin\Stamp;

use Eccube\Application;
use Eccube\Exception\CartException;

class StampEvent
{

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

}

