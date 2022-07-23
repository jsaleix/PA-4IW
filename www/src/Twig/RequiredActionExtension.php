<?php
namespace App\Twig;

use App\Entity\User;
use App\Service\Front\SellerService;
use App\Service\Back\ShopService;

use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RequiredActionExtension extends AbstractExtension
{
    function __construct(private SellerService $sellerService,
                        private ShopService $shopService,
                        private LoggerInterface $logger)
    {}

    public function getFunctions()
    {
        return [
            new TwigFunction('capabilitiesEnabled', [$this, 'capabilitiesEnabled']),
            new TwigFunction('waitingActionFromSeller', [$this, 'waitingActionFromSeller']),
            new TwigFunction('waitingActionFromShop', [$this, 'waitingActionFromShop']),
        ];
    }

    public function capabilitiesEnabled(User $user): bool
    {
        return $this->sellerService->checkSellerCapabilities($user);
    }

    public function waitingActionFromSeller(User $user): bool{
        return $this->sellerService->waitingActionFromSeller($user);
    }

    public function waitingActionFromShop(): bool
    {
        return $this->shopService->waitingActionFromShop();
    }
}