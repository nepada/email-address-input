<?php
declare(strict_types = 1);

namespace NepadaTests\Bridges\EmailAddressInputForms;

use Nepada\Bridges\EmailAddressInputForms\TEmailAddressInput;
use Nette;

class TestForm extends Nette\Forms\Form
{

    use TEmailAddressInput;

}
