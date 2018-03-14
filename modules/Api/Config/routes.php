<?php

return array(
    
    array ('api/v1/users', 'GET', 'apiController', 'index'),
    array ('api/v1/users/test', 'GET', 'apiController', 'test'),
    array ('api/v1/user/login', 'POST', 'apiController', 'login'),
    array ('api/v1/user/[:num]', 'GET', 'apiController', 'single'),
    array ('api/v1/user', 'POST', 'apiController', 'create'),
    array ('api/v1/user/[:num]', 'PUT', 'apiController', 'update'),
    array ('api/v1/user/[:num]', 'DELETE', 'apiController', 'delete'),
    
);
