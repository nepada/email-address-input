<?php
declare(strict_types = 1);

namespace Nepada\Bridges\EmailAddressInputForms;

use Nepada\EmailAddressInput\EmailAddressInput;
use Nette\Utils\Html;

trait EmailAddressInputMixin
{

    public function addEmailAddress(string|int $name, string|Html|null $label = null, bool $caseSensitive = false): EmailAddressInput
    {
        return $this[$name] = new EmailAddressInput($label, null, $caseSensitive);
    }

}
