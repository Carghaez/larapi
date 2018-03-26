<?php

namespace Carghaez\Larapi\Providers;

class EventsServiceProvider
{
    public static function fire($event, $params = [])
    {
        $events = config('events');
        if (
            isset($events) &&
            isset($events[$event])
        ) {
            $class = '';
            $method = '';
            if(is_array($events[$event])) {
                if (isset($events[$event]['class'])) {
                    $class = $events[$event]['class'];
                }
                if (isset($events[$event]['method'])) {
                    $method = $events[$event]['method'];
                }
            } else {
                $class = $events[$event];
            }
            if (!empty($class) && class_exists($class)) {
                if(!empty($method)) {
                    $obj = new $class();
                    if(method_exists($obj, $method)) {
                        $obj->$method($params);
                    }
                } else {
                    new $class($params);
                }
            }
        }
    }
}
