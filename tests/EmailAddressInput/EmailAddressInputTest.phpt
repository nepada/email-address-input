<?php
declare(strict_types = 1);

namespace NepadaTests\EmailAddressInput;

use Nepada\EmailAddress\EmailAddress;
use Nepada\EmailAddress\InvalidEmailAddressException;
use Nepada\EmailAddressInput\EmailAddressInput;
use Nette\Forms\Form;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class EmailAddressInputTest extends Tester\TestCase
{

    public function testSetNullValue(): void
    {
        $input = new EmailAddressInput();
        $input->setValue(null);
        Assert::null($input->getValue());
    }

    public function testSetEmailAddressValue(): void
    {
        $emailAddress = new EmailAddress('example@example.com');
        $input = new EmailAddressInput();
        $input->setValue($emailAddress);
        Assert::type(EmailAddress::class, $input->getValue());
        Assert::same((string) $emailAddress, (string) $input->getValue());
    }

    public function testSetValidStringValue(): void
    {
        $emailAddress = 'example@example.com';
        $input = new EmailAddressInput();
        $input->setValue($emailAddress);
        Assert::type(EmailAddress::class, $input->getValue());
        Assert::same($emailAddress, (string) $input->getValue());
    }

    public function testSetInvalidStringValue(): void
    {
        $input = new EmailAddressInput();
        Assert::exception(
            function () use ($input): void {
                $input->setValue('foo');
            },
            InvalidEmailAddressException::class
        );
    }

    public function testValidDataSubmitted(): void
    {
        $emailAddress = 'example@example.com';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [];
        $_POST = ['email' => $emailAddress];

        $form = new Form();
        $form['email'] = new EmailAddressInput();
        Assert::type(EmailAddress::class, $form['email']->getValue());
        Assert::same($emailAddress, (string) $form['email']->getValue());
        Assert::same(null, $form['email']->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" '
            . 'data-nette-rules=\'[{"op":"optional"},{"op":":email","msg":"Please enter a valid email address."}]\' value="example&#64;example.com">',
            (string) $form['email']->getControl()
        );
    }

    public function testInvalidDataSubmitted(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [];
        $_POST = ['email' => 'foo'];

        $form = new Form();
        $form['email'] = new EmailAddressInput();
        Assert::null($form['email']->getValue());
        Assert::same('Please enter a valid email address.', $form['email']->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" '
            . 'data-nette-rules=\'[{"op":"optional"},{"op":":email","msg":"Please enter a valid email address."}]\' value="foo">',
            (string) $form['email']->getControl()
        );
    }

}


(new EmailAddressInputTest())->run();
