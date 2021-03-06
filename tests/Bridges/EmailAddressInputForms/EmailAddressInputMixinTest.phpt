<?php
declare(strict_types = 1);

namespace NepadaTests\Bridges\EmailAddressInputForms;

use Nepada\EmailAddressInput\EmailAddressInput;
use NepadaTests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class EmailAddressInputMixinTest extends TestCase
{

    public function testAddEmailAddress(): void
    {
        $form = new TestForm();
        $input = $form->addEmailAddress('test', 'Label');
        Assert::type(EmailAddressInput::class, $input);
        Assert::same('Label', $input->caption);
        Assert::same($input, $form['test']);
    }

}

(new EmailAddressInputMixinTest())->run();
