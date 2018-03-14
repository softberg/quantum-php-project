<?php

return array(
    
    array ('', 'GET', 'MainController', 'indexAction'),
    array ('main/home', 'GET', 'MainController', 'homeAction'),
    array ('main/[:alpha]/[:num]', 'GET', 'MainController', 'greatingsAction'),
    array ('tandz/[:num]', 'POST', 'contacts', 'submit'),
       
);
