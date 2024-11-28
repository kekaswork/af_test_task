<?php

namespace App\Application\Service\Client;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Entity\Loan;
use App\Domain\Client\Service\NotificationServiceInterface;

/**
 * Service responsible for notifying clients about loan decisions.
 */
class NotificationService
{
    public function __construct(
        private NotificationServiceInterface $emailNotificationService,
        private NotificationServiceInterface $smsNotificationService,
    ) {
    }

    /**
     * Notifies the client about an approved loan.
     *
     * @param Loan $loan The approved loan.
     */
    public function notifyLoanApproved(Loan $loan): void
    {
        $message = $this->buildApprovalMessage($loan);

        // Notify by both Email and SMS (per technical task description).
        $this->emailNotificationService->notify($loan->getClient(), $message);
        $this->smsNotificationService->notify($loan->getClient(), $message);
    }

    /**
     * Notifies the client about a declined loan application.
     *
     * @param Client $client The client whose application was declined.
     */
    public function notifyLoanDeclined(Client $client): void
    {
        $message = $this->buildDeclineMessage();

        // Notify by both Email and SMS (per technical task description).
        $this->emailNotificationService->notify($client, $message);
        $this->smsNotificationService->notify($client, $message);
    }

    /**
     * Builds a notification message for an approved loan.
     *
     * @param Loan $loan The approved loan.
     *
     * @return string The formatted message.
     */
    private function buildApprovalMessage(Loan $loan): string
    {
        return sprintf(
            "Congratulations, your loan has been approved!\nLoan Details:\n- Term: %d months\n- Amount: %.2f USD\n- Interest Rate: %.2f%%",
            $loan->getTerm(),
            $loan->getSum(),
            $loan->getInterest()
        );
    }

    /**
     * Builds a notification message for a declined loan application.
     *
     * @return string The formatted decline message.
     */
    private function buildDeclineMessage(): string
    {
        return "Unfortunately, your loan application has been declined. For more details, please contact our support team.";
    }
}
