<?php

if (COCKPIT_API_REQUEST) {
    $this->on('cockpit.rest.init', function ($routes) {
        $routes['users'] = 'CustomApi\\Controller\\RestApiFiles';
    });
}