<?php
declare(strict_types = 1);

namespace NepadaTests\EmailAddressInput;

use Nepada\EmailAddress\CaseInsensitiveEmailAddress;
use Nepada\EmailAddress\EmailAddress;
use Nepada\EmailAddress\InvalidEmailAddressException;
use Nepada\EmailAddress\RfcEmailAddress;
use Nepada\EmailAddressInput\EmailAddressInput;
use NepadaTests\TestCase;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class EmailAddressInputTest extends TestCase
{

    public function testSetNullValue(): void
    {
        $input = new EmailAddressInput();
        $input->setValue(null);
        Assert::null($input->getValue());
    }

    public function testSetEmailAddressValue(): void
    {
        $emailAddress = RfcEmailAddress::fromString('example@example.com');
        $input = new EmailAddressInput();
        $input->setValue($emailAddress);
        Assert::type(CaseInsensitiveEmailAddress::class, $input->getValue());
        Assert::same((string) $emailAddress, (string) $input->getValue());
    }

    public function testSetValidStringValue(): void
    {
        $emailAddress = 'example@example.com';
        $input = new EmailAddressInput();
        $input->setValue($emailAddress);
        Assert::type(CaseInsensitiveEmailAddress::class, $input->getValue());
        Assert::same($emailAddress, (string) $input->getValue());
    }

    public function testSetInvalidStringValue(): void
    {
        $input = new EmailAddressInput();
        Assert::exception(
            function () use ($input): void {
                $input->setValue('foo');
            },
            InvalidEmailAddressException::class,
        );
    }

    public function testSetInvalidValue(): void
    {
        $input = new EmailAddressInput();
        Assert::exception(
            function () use ($input): void {
                $input->setValue(42);
            },
            \InvalidArgumentException::class,
        );
    }

    public function testCaseSensitivityEnabled(): void
    {
        $emailAddress = CaseInsensitiveEmailAddress::fromString('Example@Example.com');
        $input = new EmailAddressInput();
        $input->setCaseSensitive(true);
        $input->setValue($emailAddress);
        Assert::type(RfcEmailAddress::class, $input->getValue());
        Assert::same((string) $emailAddress, (string) $input->getValue());
    }

    public function testNoDataSubmitted(): void
    {
        $this->resetHttpGlobalVariables();
        $_POST = ['email' => ''];

        $form = $this->createForm();
        $emailAddressInput = new EmailAddressInput();
        $form['email'] = $emailAddressInput;
        $form->fireEvents();

        Assert::null($emailAddressInput->getValue());
        Assert::same(null, $emailAddressInput->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" '
            . 'data-nette-rules=\'['
            . '{"op":":email","msg":"Please enter a valid email address."}]\'>',
            (string) $emailAddressInput->getControl(),
        );
    }

    public function testEmptyValueSubmitted(): void
    {
        $this->resetHttpGlobalVariables();
        $_POST = ['email' => '@'];

        $form = $this->createForm();
        $emailAddressInput = new EmailAddressInput();
        $form['email'] = $emailAddressInput;
        $emailAddressInput->setEmptyValue('@');
        $form->fireEvents();

        Assert::null($emailAddressInput->getValue());
        Assert::same(null, $emailAddressInput->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" '
            . 'data-nette-rules=\'['
            . '{"op":":email","msg":"Please enter a valid email address."}]\' data-nette-empty-value="&#64;" value="&#64;">',
            (string) $emailAddressInput->getControl(),
        );
    }

    public function testValidDataSubmitted(): void
    {
        $this->resetHttpGlobalVariables();
        $_POST = ['email' => 'Example@Example.com'];

        $form = $this->createForm();
        $emailAddressInput = new EmailAddressInput();
        $form['email'] = $emailAddressInput;
        $form->fireEvents();

        Assert::type(EmailAddress::class, $emailAddressInput->getValue());
        Assert::same('Example@Example.com', (string) $emailAddressInput->getValue());
        Assert::same(null, $emailAddressInput->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" '
            . 'data-nette-rules=\'['
            . '{"op":":email","msg":"Please enter a valid email address."}]\' value="Example&#64;Example.com">',
            (string) $emailAddressInput->getControl(),
        );
    }

    public function testInvalidDataSubmitted(): void
    {
        $this->resetHttpGlobalVariables();
        $_POST = ['email' => 'foo'];

        $form = $this->createForm();
        $emailAddressInput = new EmailAddressInput();
        $form['email'] = $emailAddressInput;
        $emailAddressInput->setRequired('true');
        $form->fireEvents();

        Assert::null($emailAddressInput->getValue());
        Assert::same('Please enter a valid email address.', $emailAddressInput->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" required '
            . 'data-nette-rules=\'[{"op":":filled","msg":"true"},{"op":":email","msg":"Please enter a valid email address."}]\' value="foo">',
            (string) $emailAddressInput->getControl(),
        );
    }

    private function resetHttpGlobalVariables(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [];
        $_COOKIE['_nss'] = '1';
        $_POST = [];
        $_GET = [];
    }

    private function createForm(): Form
    {
        $form = new Form();
        $form->onSubmit[] = function (): void {
        };
        return $form;
    }

}


(new EmailAddressInputTest())->run();
