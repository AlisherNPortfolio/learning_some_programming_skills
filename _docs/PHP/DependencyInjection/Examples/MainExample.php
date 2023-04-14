<?php

class Profile
{
    private $setting;

    public function setSetting(Setting $setting)
    {
        $this->setting = $setting;
    }
}

class Setting
{
    public $isActive = true;
    //...
}

$setting = new Setting();
$profile = new Profile();
$profile->setSetting($setting);
