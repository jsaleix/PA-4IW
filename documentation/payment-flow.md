

## Creating a Stripe product

**SneakersController->addMP()** to publish a product on the marketplace
or 
**AdminShopController->publish()** to publish on the shop

Both call
**sneakerService->publish(Sneaker $sneaker, User $publisher, bool $fromShop = true )**
It creates a stripe product using its id as the stripe product's id


## Payment flow

**MarketplaceController->checkout()**
or 
**ShopController->checkout()**

Both call **PaymentService->generatePaymentIntent(Sneaker $sneaker, User $buyer)**

which checks again the sneaker origin (shop or marketplace), then call
(if it's from the shop) 
**PaymentService->generateMPIntent($sneaker, $buyer)**

or if it's from the marketplace, 
**PaymentService->generateShopIntent($sneaker, $buyer)**

Both returns a checkout url to proceed to payment onto the stripe website
Once the payment is successful, Stripe will emit an event, which the application will listen


**WebhookController** is used for the stripe *webhooks*
WebhookController->index() is an endpoint called by each event emitted from Stripe, it only manages the event emitted when the payment intent succeeds, and otherwise if the checkout session expired or is canceled

# Events

## PAYMENT INTENT EXPIRED OR CANCELED

Calls **PaymentService->removeInvoice($invoice)**

## PAYMENT INTENT SUCCEEDED

Calls **PaymentService->confirmPayment($invoice)**

Which itself calls the right method according to the sneaker origin (from the shop or the marketplace) among *confirmPaymentShop()* and *confirmPaymentMP()*

## confirmPaymentShop( Invoice $invoice, Sneaker $sneaker )

Updates the stock, if the new stock number is under 1, set the sneaker to sold
Updates the sneaker payment status to SOLD_STATUS

## confirmPaymentMP( Invoice $invoice, Sneaker $sneaker )

Set the sneaker to sold
Set the sneaker payment status to SOLD_STATUS

Transfers the funds from our Stripe account to the user connected account
Takes the price set in the invoice and removes our 10% fees before sending it to the seller