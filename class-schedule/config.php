<?php

use humhub\modules\space\widgets\Menu;

return [
    'id' => 'class-schedule',
    'class' => 'humhub\modules\class_schedule\Module',
    'namespace' => 'humhub\modules\class_schedule',
    'events' => [

        [
            'class' => 'humhub\modules\calendar\interfaces\CalendarService',
            'event' => 'findItems', 
            'callback' => ['humhub\modules\class_schedule\Events', 'onGetCalendarItems']
        ],
        [
            'class' => 'humhub\modules\calendar\interfaces\CalendarService',
            'event' => 'getItemTypes',
            'callback' => ['humhub\modules\class_schedule\Events', 'onGetCalendarItemTypes']
        ],
        [
            'class' => 'humhub\modules\space\widgets\Header',
            'event' => 'init',
            'callback' => ['humhub\modules\class_schedule\Events', 'onSpaceHeaderInit']
        ]
    ],
];