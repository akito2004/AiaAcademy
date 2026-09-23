<?php
return [
    '/docentes' => ['controller' => 'IndexController', 'action' => 'index'],
    '/docentes/ver/(:num)' => ['controller' => 'IndexController', 'action' => 'verDetalle', 'params' => [1]],
];