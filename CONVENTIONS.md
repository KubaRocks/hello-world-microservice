# Conventions and Design

### Where should I write this class?

_Is it representing an application purpose, such as "register a customer" or "find company bank account"?_

It should live in `/src/app`

_Is the class representing a business concept that should not care about where it is stored or method of communication?_

It should live in `/src/domain`

_Is my class specific to the communication?_ For example, is it aware of a JSON Request, or from a CLI

Then it should live in `/src/api`

_Is the class a way to interface with something framework or vendor-specific, such as a way to communicate with a database or a specific type of message bus?

_Persisting data is a business concept! Where should I put my database detail?_

You should write an interface in `/src/domain` to describe the business concept, e.g.:

```php 
<?php

declare(strict_types=1);

namespace Cushon\Customer;

use Cushon\Customer\CustomerId;
use Cushon\Customer\CustomerDetails;

interface CustomerDetailsRepository
{
    public function fetchCustomerDetails(CustomerId $customerId): CustomerDetails;
}
```
You then can create an implementation of the above somewhere in `/src/infra`:

```php 
<?php

declare(strict_types=1);

namespace Infra\Repository;

use Cushon\Customer\CustomerDetailsRepository;
use Cushon\Customer\CustomerId;
use Cushon\Customer\CustomerDetails;

final class DbalCustomerDetails implements CustomerDetailsRepository
{
    public function fetchCustomerDetails(CustomerId $customerId): CustomerDetails
    {
        // specific way of doing things in the database to meet the obligation of the
        // interface and business design.
    }
}
```
