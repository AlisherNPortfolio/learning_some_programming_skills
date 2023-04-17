<?php

interface Animal
{
}

class Cat implements Animal
{
}
class MaineCoon extends Cat
{
}

echo is_subclass_of(Cat::class, Animal::class); // true
echo '<br>';
echo is_subclass_of(MaineCoon::class, Cat::class); // true
echo '<br>';
echo is_subclass_of(MaineCoon::class, Animal::class); // true
