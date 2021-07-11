<?php

return [
    'plugin' => [
        'name' => 'Backend Traffic Cop',
        'description' => 'Prevent back-end conflicts if a model has been updated since its last retrieval and display a confirmation popup.',
        'tab' => 'Customization',
    ],
    'popup' => [
        'message' => 'Another user has updated this record since this page was loaded. Would you like to save anyway?'
    ],
];
