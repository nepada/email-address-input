<?php
declare(strict_types = 1);

namespace Nepada\Bridges\EmailAddressInputForms;

use Nepada\EmailAddressInput\EmailAddressInput;
use Nette\Utils\Html;

trait EmailAddressInputMixin
{

    /**
     * @param string|int $name
     * @param string|Html|null $label
     * @return EmailAddressInput
     */
    public function addEmailAddress($name, $label = null): EmailAddressInput
    {
        return $this[$name] = new EmailAddressInput($label);
    }

}
