<?php
declare(strict_types = 1);

namespace Nepada\Bridges\EmailAddressInputDI;

use Nepada\Bridges\EmailAddressInputForms\ExtensionMethodRegistrator;
use Nette;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

class EmailAddressInputExtension extends CompilerExtension
{

    public function getConfigSchema(): Nette\Schema\Schema
    {
        return Nette\Schema\Expect::structure([]);
    }

    public function afterCompile(ClassType $class): void
    {
        $init = $class->getMethods()['initialize'];
        $init->addBody(ExtensionMethodRegistrator::class . '::register();');
    }

}
