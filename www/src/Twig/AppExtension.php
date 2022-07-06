<?php
namespace App\Twig;

use App\Entity\User;
use App\Service\Payment\SellerService;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    function __construct(private SellerService $sellerService,
                         private LoggerInterface $logger)
    {}

    public function getFunctions()
    {
        return [
            new TwigFunction('capabilitiesEnabled', [$this, 'capabilitiesEnabled']),
            new TwigFunction('waitingActionFromSeller', [$this, 'waitingActionFromSeller']),
        ];
    }

    public function capabilitiesEnabled(User $user): bool
    {
        return $this->sellerService->checkSellerCapabilities($user);
    }

    public function waitingActionFromSeller(User $user): bool{
        return $this->sellerService->waitingActionFromSeller($user);
    }
}