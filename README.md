
# PA-Symfony

Repository projet annuel en Symfony 4a


## Getting started

  

```bash

docker-compose build --pull --no-cache

docker-compose up -d

```

## Loading data fixtures

Use the following command in the php container

  

```bash

php bin/console doctrine:fixtures:load

```

## Listening Stripe events locally

To use the Stripe webhooks without hosting the application, you might need to install the Stripe CLI
https://stripe.com/docs/stripe-cli

Once the settings are done, use the following command in your shell

```bash

stripe listen --forward-to localhost/webhook/stripe

```

## Documentation

[link to the documentation files](documentation/)
