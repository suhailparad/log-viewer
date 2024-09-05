<?php

return[

    //Custom title for Log viewer page
    'custom_title' => 'Laravel Log Viewer',

    //Set true if multi tenant support log viewer
    'multi_tenant' => false,

    //Back URL
    'back_url' => '/logs',

    //Log viewer route prefix
    'route_prefix' => 'logs',

    //Middleware
    'middlewares' => ['web','auth']

];
