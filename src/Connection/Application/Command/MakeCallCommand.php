<?php

namespace App\Connection\Application\Command;

use App\Connection\Domain\ConnectionAdapterFactory;
use App\Connection\Infrastructure\Adapter\CallServiceAdapterInterface;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeCallCommand extends AbstractCommand
{
    protected static $defaultName = 'connection:make_call';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');
        $callToQuestion = new Question('Where do you want to call? ');
        $callToQuestion->setValidator($this->nonEmptyValidator);
        $callFromQuestion = new Question('Who\'s calling? ');
        $callFromQuestion->setValidator($this->nonEmptyValidator);

        try {
            /** @var CallServiceAdapterInterface $callService */
            $callService = $this->connectionFactory->createConnection(
                $questionHelper->ask($input, $output, $this->connectServiceQuestion),
                ConnectionAdapterFactory::SERVICE_TYPE_CALL
            );

            $callTo = $questionHelper->ask($input, $output, $callToQuestion);
            $callFrom = $questionHelper->ask($input, $output, $callFromQuestion);
            $callResult = $callService->call($callFrom, $callTo);
        } catch (InvalidArgumentException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        if ($callResult->getIsSuccess()) {
            $output->writeln(sprintf('Successfully called from %s to %s', $callFrom, $callTo));

            return Command::SUCCESS;
        }

        $output->write(sprintf('Call from %s to %s failed', $callFrom, $callTo));
        if (!is_null($callResult->getErrorMessage())) {
            $output->writeln(sprintf(': %s', $callResult->getErrorMessage()));
        }

        return Command::FAILURE;
    }

    /**
     * @inheritDoc
     */
    protected function getServiceCodes(): array
    {
        return $this->connectionFactory->getAvailableCallServicesCodes();
    }
}