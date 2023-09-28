<?php
declare(strict_types = 1);

namespace Nepada\EmailAddressInput;

use Nepada\EmailAddress\CaseInsensitiveEmailAddress;
use Nepada\EmailAddress\EmailAddress;
use Nepada\EmailAddress\InvalidEmailAddressException;
use Nepada\EmailAddress\RfcEmailAddress;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Nette\Utils\Strings;

class EmailAddressInput extends TextInput
{

    private bool $caseSensitive;

    public function __construct(string|Html|null $label = null, ?int $maxLength = null, bool $caseSensitive = false)
    {
        parent::__construct($label, $maxLength);
        $this->caseSensitive = $caseSensitive;
        $this->setNullable();
        $this->addRule(Form::EMAIL);
    }

    public function setCaseSensitive(bool $caseSensitive): void
    {
        $this->caseSensitive = $caseSensitive;
    }

    public function getValue(): ?EmailAddress
    {
        /** @var EmailAddress|null $value */
        $value = parent::getValue();
        return $value;
    }

    /**
     * @internal
     * @return $this
     */
    public function setValue(mixed $value): static
    {
        if (is_string($value) || $value instanceof EmailAddress) {
            $value = $this->toEmailAddress($value);
        } elseif ($value !== null) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Value must be null, EmailAddress instance, or string with a valid email address, %s given in field "%s".',
                    gettype($value),
                    $this->name,
                ),
            );
        }

        parent::setValue($value);
        return $this;
    }

    /**
     * @param EmailAddress|string|null $value
     * @return $this
     */
    public function setDefaultValue(mixed $value): static
    {
        parent::setDefaultValue($value);
        return $this;
    }

    public function loadHttpData(): void
    {
        /** @var string $value */
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

    /**
     * @throws InvalidEmailAddressException
     */
    private function toEmailAddress(string|EmailAddress $value): EmailAddress
    {
        if ($this->caseSensitive) {
            return RfcEmailAddress::fromString((string) $value);
        }

        return CaseInsensitiveEmailAddress::fromString((string) $value);
    }

}
