<?php

return array(
    
    array ('admin/[:alpha]', 'GET', 'dashboard', 'index'),
    array ('admin/dashboard', 'GET', 'dashboard', 'index'),
    array ('admin/users/[:num]', 'GET', 'dashboard', 'usersList'),
    array ('admin/users/manager/status/[:alpha]', 'GET', 'dashboard', 'usersList'),
    
);
