<?php
declare(strict_types = 1);

namespace Nepada\EmailAddressInput;

use Nepada\EmailAddress\EmailAddress;
use Nepada\EmailAddress\InvalidEmailAddressException;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Nette\Utils\Strings;

class EmailAddressInput extends TextInput
{

    /**
     * @param string|Html|null $label
     * @param int|null $maxLength
     */
    public function __construct($label = null, ?int $maxLength = null)
    {
        parent::__construct($label, $maxLength);
        $this->setNullable();
        $this->addRule(Form::EMAIL);
    }

    public function getValue(): ?EmailAddress
    {
        $value = parent::getValue();
        return $value;
    }

    /**
     * @internal
     * @param mixed $value
     * @return static
     */
    public function setValue($value): self
    {
        if (is_string($value)) {
            $value = EmailAddress::fromString($value);
        } elseif ($value !== null && !$value instanceof EmailAddress) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Value must be null, EmailAddress instance, or string with a valid email address, %s given in field "%s".',
                    gettype($value),
                    $this->name
                )
            );
        }

        parent::setValue($value);
        return $this;
    }

    /**
     * @param EmailAddress|string|null $value
     * @return static
     */
    public function setDefaultValue($value): self
    {
        parent::setDefaultValue($value);
        return $this;
    }

    public function loadHttpData(): void
    {
        $value = $this->getHttpData(Form::DATA_LINE);

        if ($value === '' || $value === Strings::trim($this->translate($this->emptyValue))) {
            $this->value = null;
            $this->rawValue = $value;
            return;
        }

        try {
            $this->setValue($value);
        } catch (InvalidEmailAddressException $exception) {
            $this->value = null;
            $this->rawValue = $value;
        }
    }

    public function isFilled(): bool
    {
        return $this->rawValue !== '' && $this->rawValue !== Strings::trim($this->translate($this->emptyValue));
    }

}
