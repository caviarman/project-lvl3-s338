<?php

$router->get('/', ['as' => 'domains.main', 'uses' => 'DomainController@main']);

$router->post('/domains', ['as' => 'domains.save', 'uses' => 'DomainController@save']);

$router->get('/domains/{id}', ['as' => 'domains.show', 'uses' => 'DomainController@show']);

$router->get('/domains', ['as' => 'domains.all', 'uses' => 'DomainController@showAll']);
