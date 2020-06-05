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
     * @param bool $caseSensitive
     * @return EmailAddressInput
     */
    public function addEmailAddress($name, $label = null, bool $caseSensitive = false): EmailAddressInput
    {
        return $this[$name] = new EmailAddressInput($label, null, $caseSensitive);
    }

}
