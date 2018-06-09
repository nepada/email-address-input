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

    public function testNoDataSubmitted(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [];
        $_POST = ['email' => ''];

        $form = new Form();
        $form['email'] = new EmailAddressInput();
        $form->fireEvents();

        Assert::null($form['email']->getValue());
        Assert::same(null, $form['email']->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" '
            . 'data-nette-rules=\'[{"op":"optional"},{"op":":email","msg":"Please enter a valid email address."}]\'>',
            (string) $form['email']->getControl()
        );
    }

    public function testEmptyValueSubmitted(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [];
        $_POST = ['email' => '@'];

        $form = new Form();
        $form['email'] = new EmailAddressInput();
        $form['email']->setEmptyValue('@');
        $form->fireEvents();

        Assert::null($form['email']->getValue());
        Assert::same(null, $form['email']->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" '
            . 'data-nette-rules=\'[{"op":"optional"},{"op":":email","msg":"Please enter a valid email address."}]\' data-nette-empty-value="&#64;" value="&#64;">',
            (string) $form['email']->getControl()
        );
    }

    public function testValidDataSubmitted(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_FILES = [];
        $_POST = ['email' => 'Example@Example.com'];

        $form = new Form();
        $form['email'] = new EmailAddressInput();
        $form->fireEvents();

        Assert::type(EmailAddress::class, $form['email']->getValue());
        Assert::same('Example@Example.com', (string) $form['email']->getValue());
        Assert::same(null, $form['email']->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" '
            . 'data-nette-rules=\'[{"op":"optional"},{"op":":email","msg":"Please enter a valid email address."}]\' value="Example&#64;Example.com">',
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
        $form['email']->setRequired('true');
        $form->fireEvents();

        Assert::null($form['email']->getValue());
        Assert::same('Please enter a valid email address.', $form['email']->getError());
        Assert::same(
            '<input type="email" name="email" id="frm-email" required '
            . 'data-nette-rules=\'[{"op":":filled","msg":"true"},{"op":":email","msg":"Please enter a valid email address."}]\' value="foo">',
            (string) $form['email']->getControl()
        );
    }

}


(new EmailAddressInputTest())->run();
