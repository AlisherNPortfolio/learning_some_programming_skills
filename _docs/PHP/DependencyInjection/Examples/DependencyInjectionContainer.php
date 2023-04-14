<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'Container.php';

interface ContainerInterface
{
    public function get($id);

    public function has($id);
}


class Setting
{

}

class Profile
{
    public function __construct(private Setting $setting)
    {

    }
}

$container = new Container();
$container->set('Profile');

$profile = $container->get('Profile');
print_r($profile);
