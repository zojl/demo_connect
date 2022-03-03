<?php

namespace App\Connection\Application\Command;

use App\Connection\Domain\ConnectionAdapterFactory;
use Closure;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\ChoiceQuestion;

abstract class AbstractCommand extends Command
{
    /** @var ConnectionAdapterFactory $connectionFactory */
    protected ConnectionAdapterFactory $connectionFactory;

    /** @var ChoiceQuestion $connectServiceQuestion */
    protected ChoiceQuestion $connectServiceQuestion;

    /** @var Closure $nonEmptyValidator */
    protected Closure $nonEmptyValidator;

    /**
     * @return string[]
     */
    abstract protected function getServiceCodes(): array;

    /**
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->connectionFactory = new ConnectionAdapterFactory();
        $this->connectServiceQuestion = new ChoiceQuestion(
            'Which service do you want to use?', $this->getServiceCodes());
        $this->connectServiceQuestion->setErrorMessage('No such service "%s"');
        $this->nonEmptyValidator = function(?string $answer) {
            if (is_null($answer)) {
                throw new RuntimeException('Enter something');
            }

            return $answer;
        };
    }

}