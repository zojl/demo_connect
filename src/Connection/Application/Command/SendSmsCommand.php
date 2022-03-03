<?php

namespace App\Connection\Application\Command;

use App\Connection\Domain\ConnectionAdapterFactory;
use App\Connection\Infrastructure\Adapter\SmsServiceAdapterInterface;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class SendSmsCommand extends AbstractCommand
{
    protected static $defaultName = 'connection:send_sms';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');
        $smsToQuestion = new Question('Where do you want to send SMS? ');
        $smsToQuestion->setValidator($this->nonEmptyValidator);
        $smsFromQuestion = new Question('Who\'s sending? ');
        $smsFromQuestion->setValidator($this->nonEmptyValidator);
        $textQuestion = new Question('Type your message: ');
        $textQuestion->setValidator($this->nonEmptyValidator);

        try {
            /** @var SmsServiceAdapterInterface $smsService */
            $smsService = $this->connectionFactory->createConnection(
                $questionHelper->ask($input, $output, $this->connectServiceQuestion),
                ConnectionAdapterFactory::SERVICE_TYPE_SMS
            );

            $smsTo = $questionHelper->ask($input, $output, $smsToQuestion);
            $smsFrom = $questionHelper->ask($input, $output, $smsFromQuestion);
            $smsText = $questionHelper->ask($input, $output, $textQuestion);
            $smsResult = $smsService->sendSms($smsFrom, $smsTo, $smsText);
        } catch (InvalidArgumentException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        if ($smsResult->getIsSuccess()) {
            $output->writeln(sprintf('Successfully sent an sms "%s" from %s to %s', $smsText, $smsFrom, $smsTo));

            return Command::SUCCESS;
        }

        $output->write(sprintf('Sending sms "%s" from %s to %s failed', $smsText, $smsFrom, $smsTo));
        if (!is_null($smsResult->getErrorMessage())) {
            $output->writeln(sprintf(': %s', $smsResult->getErrorMessage()));
        }

        return Command::FAILURE;
    }

    /**
     * @inheritDoc
     */
    protected function getServiceCodes(): array
    {
        return $this->connectionFactory->getAvailableSmsServicesCodes();
    }
}