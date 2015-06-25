# RouteInheritance
Allows ZF2 routes to extend other routes, overriding options.

Why?
----
```
I had some large and complicated routes set up for a project and needed to
"duplicate" the routes with alterations without having to copy, paste, and
modify the whole route array.
```

Installation
------------
- Use composer as described here: https://packagist.org/packages/hickeroar/zf2-route-inheritance
- Add `RouteInheritance` to your `config/application.config.php`

Usage
-----
```php
'router' => [
    'routes' => [
        'my-original-route' => [
            'type' => 'literal',
            'options' => [
                'route'    => '/orig-url',
                'defaults' => [
                    'controller' => 'Application\Controller\IndexController',
                    'action'     => 'index',
                ],
            ],
        ],
    ],
    // "Copies" my-original-route to my-new-route and overrides/adds various things.
    // This currently uses a merge, so removing config keys is not supported.
    'inheritance' => [
        'my-new-route' => [
            'extends' => 'my-original-route',
            'configuration' => [
                'type' => 'segment',
                'options' => [
                    'route'    => '/new-url/:segment',
                    'constraints' => [
                        'segment' => '[a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => 'Application\Controller\IndexController',
                        'action'     => 'new',
                    ],
                ],
            ],
        ],
    ],
],
```

License
-------
```
The MIT License (MIT)

Copyright (c) 2015 Ryan Vennell

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
