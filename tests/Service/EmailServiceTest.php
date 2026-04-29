<?php

namespace App\Tests\Service;

use App\Service\EmailService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailServiceTest extends TestCase
{
    public function testSendCallsMailerWithCorrectParameters(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) {
                return $email->getFrom()[0]->getAddress() === 'sender@example.com'
                    && $email->getTo()[0]->getAddress() === 'recipient@example.com'
                    && $email->getSubject() === 'Test Subject'
                    && $email->getTextBody() === 'Test message body';
            }));

        $service = new EmailService($mailer);
        $service->send('sender@example.com', 'recipient@example.com', 'Test Subject', 'Test message body');
    }
}
