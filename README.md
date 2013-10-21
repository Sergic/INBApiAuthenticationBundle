INBApiAuthenticationBundle
==========================

Api authentication bundle for Symfony2

Installation
============

### 1. Add this bundle to your composer.json:

```js
{
    "require": {
        "sergic/api-authentication-bundle": "0.1.*@dev"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update sergic/api-authentication-bundle
```

### 2. Add this bundle to your application's kernel:

``` php
// application/ApplicationKernel.php
public function registerBundles()
{
    return array(
      // ...
        new INB\Bundle\ApiAuthenticationBundle\INBApiAuthenticationBundle(),
      // ...
    );
}
```

### Step 3: Create your User class

with token parameter

``` php
// src/Acme/AcmeBundle/Entity/User.php
/**
 * User Api token
 *
 * @var string
 */
protected $token;

public function __construct()
{
    $this->token = null;
}

/**
 * {@inheritdoc}
 */
public function getToken()
{
    return $this->token;
}

/**
 * {@inheritdoc}
 */
public function setToken($token)
{
    $this->token = $token;

    return $this;
}

/**
 * {@inheritdoc}
 */
public function generateApiToken()
{
    $this->token = md5(md5(time() + rand(11111, 99999)));

    return $this;
}
```

### Step 4: Create your User repository class and init it as a service
``` php
// src/Acme/AcmeBundle/Repositroy/UserRepository.php
use Ft\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param EntityManager $em    The EntityManager to use.
     * @param ClassMetadata $class The class descriptor.
     */
    public function __construct($em, ClassMetadata $class, EncoderFactoryInterface $encoderFactory = null)
    {
        parent::__construct($em, $class);
        $this->encoderFactory = $encoderFactory;
    }
}
```

``` yml
# src/Acme/AcmeBundle/Resources/config/services.yml
parameters:
    acme.user.class: Acme\AcmeBundle\Entity\User
    acme.repository.user.class: Acme\AcmeBundle\Repositroy\UserRepository
services:
    user_metadata:
        class: Doctrine\ORM\Mapping\ClassMetadata
        factory-method: getClassMetadata
        arguments: [%acme.user.class%]
    acme.repository.user:
        class: %acme.repository.user.class%
        arguments: [@doctrine.orm.entity_manager, @user_metadata, @security.encoder_factory]
```

### Step 4: Configure your application's security.yml
``` yaml
# app/config/security.yml
security:
    providers:
        api_provider:
            id: inb_api_auth.provider
    firewalls:
        api:
            provider: api_provider
            pattern: ^(/[^\/]+)/api|^/api
            access_denied_url: ^(/[^\/]+)/api|^/api/unauthorized
            api: { lifetime: '30D' }
            stateless: true
            anonymous: true
    encoders:
        Acme\AcmeBundle\Entity\User: sha512
```