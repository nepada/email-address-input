<?php
declare(strict_types = 1);

namespace NepadaTests\Bridges\EmailAddressInputDI;

use Nepada\EmailAddressInput\EmailAddressInput;
use NepadaTests\TestCase;
use Nette;
use Nette\Forms\Form;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class EmailAddressInputExtensionTest extends TestCase
{

    public function testEmailAddressInput(): void
    {
        $configurator = new Nette\Configurator();
        $configurator->setTempDirectory(TEMP_DIR);
        $configurator->setDebugMode(true);
        $configurator->addConfig(__DIR__ . '/fixtures/config.neon');
        $configurator->createContainer();

        $form = new Form();
        $input = $form->addEmailAddress('test', 'Label');
        Assert::type(EmailAddressInput::class, $input);
        Assert::same('Label', $input->caption);
        Assert::same($input, $form['test']);
    }

}


(new EmailAddressInputExtensionTest())->run();
