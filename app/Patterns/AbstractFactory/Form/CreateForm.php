<?php

namespace App\Patterns\AbstractFactory\Form;

use App\Patterns\AbstractFactory\Form\Contracts\ICreateFormFactory;

class CreateForm
{
    private static $instance;

    public function __construct(protected ICreateFormFactory $createFormFactory)
    {
    }

    public static function getInstance(ICreateFormFactory $createFormFactory): self
    {
        if (empty(self::$instance)) {
            self::$instance = new CreateForm($createFormFactory);
        }

        return self::$instance;
    }

    public function getTitle(): string
    {
        return $this->createFormFactory->getTitle();
    }

    public function getBodyElements(): array
    {
        return $this->createFormFactory->getBodyElements();
    }

    public function getSubmitAction(): string
    {
        return $this->createFormFactory->getSubmitAction();
    }
}
