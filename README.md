Email address form input
========================

[![Build Status](https://travis-ci.org/nepada/email-address-input.svg?branch=master)](https://travis-ci.org/nepada/email-address-input)
[![Coverage Status](https://coveralls.io/repos/github/nepada/email-address-input/badge.svg?branch=master)](https://coveralls.io/github/nepada/email-address-input?branch=master)
[![Downloads this Month](https://img.shields.io/packagist/dm/nepada/email-address-input.svg)](https://packagist.org/packages/nepada/email-address-input)
[![Latest stable](https://img.shields.io/packagist/v/nepada/email-address-input.svg)](https://packagist.org/packages/nepada/email-address-input)


Installation
------------

Via Composer:

```sh
$ composer require nepada/email-address-input
```

### Option A: install form container extension method via DI extension

```yaml
extensions:
    - Nepada\Bridges\EmailAddressInputDI\EmailAddressInputExtension
```

It will register extension method `addEmailAddress($name, $label)` to `Nette\Forms\Container`.


### Option B: use trait in your base form/container class

You can also use `TEmailAddressInput` trait in your base form/container class to add method `addEmailAddress($name, $label)`.

Example:

```php

trait TFormControls
{

    use Nepada\Bridges\EmailAddressInputForms\TEmailAddressInput;

    public function addContainer($name)
    {
        $control = new Container;
        $control->setCurrentGroup($this->getCurrentGroup());
        if ($this->currentGroup !== null) {
            $this->currentGroup->add($control);
        }
        return $this[$name] = $control;
    }

}

class Container extends Nette\Forms\Container
{

    use TFormControls;

}

class Form extends Nette\Forms\Form
{

    use TFormControls;

}

``` 


Usage
-----

`EmailAddressInput` is form control that uses email address value object to represent its value (see [nepada/email-address](https://github.com/nepada/email-address) for further details).
It automatically validates the user input and `getValue()` method always returns `EmailAddress` instance, or `null` if the input is not filled.

```php
$emailAddressInput = $form->addEmailAddress('E-mail');
$emailAddressInput->setValue('42'); // InvalidEmailAddressException is thrown
$emailAddressInput->setValue('example@example.com'); // the value is internally converted to EmailAddress value object
$emailAddressInput->getValue(); // EmailAddress instance for example@example.com 
```
