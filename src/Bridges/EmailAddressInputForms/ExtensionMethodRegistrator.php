<?php
declare(strict_types = 1);

namespace Nepada\Bridges\EmailAddressInputForms;

use Nepada\EmailAddressInput\EmailAddressInput;
use Nette;
use Nette\Forms\Container;

class ExtensionMethodRegistrator
{

    use Nette\StaticClass;

    public static function register(): void
    {
        Container::extensionMethod(
            'addEmailAddress',
            function (Container $container, $name, $label = null, bool $caseSensitive = false): EmailAddressInput {
                $control = new EmailAddressInput($label, null, $caseSensitive);
                $container[$name] = $control;
                return $control;
            },
        );
    }

}
