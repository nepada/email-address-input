<?php
declare(strict_types = 1);

namespace NepadaTests\Bridges\FormRendererDI;

use Nepada\EmailAddressInput\EmailAddressInput;
use Nette;
use Nette\Forms\Form;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class EmailAddressInputExtensionTest extends Tester\TestCase
{

    public function testEmailAddressInput(): void
    {
        $configurator = new Nette\Configurator();
        $configurator->setTempDirectory(TEMP_DIR);
        $configurator->setDebugMode(true);
        $configurator->addConfig(__DIR__ . '/fixtures/config.neon');
        $configurator->createContainer();

        $form = new Form();
        Assert::type(EmailAddressInput::class, $form->addEmailAddress('test'));
    }

}


(new EmailAddressInputExtensionTest())->run();
