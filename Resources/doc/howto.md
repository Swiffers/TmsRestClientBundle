TmsRestBundle
=============

A service which brings support for "hypermedia" links for REST web services
in client-side

How to use
----------

### Collection

#### Retrieve the hypermedia collection

You need to use the tms_rest_client.hypermedia.crawler service to retrieve
an hypermedia collection. Several methods exist to do it :

* `findAll($name, array $params)`
* `crawle($path, array $params, $absolutePath = false)`

The crawler throws some exception of type ApiHttpResponseException if necessary.
That's why the crawler use must be surrounded by try and catch.

```php
try {
    $hypermediaCollection = $this
        ->get('tms_rest_client.hypermedia.crawler')
        ->go('operation')
        ->find('offers', array(
            'limit' => 10,
            'page'  => 1
        ))
    ;

    $hypermediaCollection = $this
        ->get('tms_rest_client.hypermedia.crawler')
        ->go('operation')
        ->crawle('offers')
    ;

    $hypermediaCollection = $this
        ->get('tms_rest_client.hypermedia.crawler')
        ->go('operation')
        ->crawle('offers?limit=5')
    ;

    $hypermediaCollection = $this
        ->get('tms_rest_client.hypermedia.crawler')
        ->go('operation')
        ->crawle(
            'http://operation-manager.local.digifid.fr/api/offers',
            array(),
            true
        )
    ;
} catch (\Da\ApiClientBundle\Exception\ApiHttpResponseException $e) {
    switch ($e->getHttpCode()) {
        case 403:
            throw new AccessDeniedHttpException($e->getMessage());
        case 404:
            throw new NotFoundHttpException();
    }
}
```

#### Works with the retrieved hypermedia collection

Well, you retrieved your hypermedia collection in the first step,
we can now play with it !

##### Get all metadata

```php
$hypermediaCollection->getAllMetadata();
```

##### Get a specific metadata

```php
$hypermediaCollection->getMetadata($metadataName);
```

##### Get all links

```php
$hypermediaCollection->getLinks();
```

##### Get a specific link

```php
$hypermediaCollection->getLink($linkName);
```

##### Get data

```php
$hypermediaCollection->getData();
```

##### Follow a link to get new hypermedia collection

You can follow links of a collection to retrieve a new collection
by calling the new API url in link href.

```php
$hypermediaCollection = $hypermediaCollection->followLink($linkName);
```

For instance :
```php
$hypermediaCollection = $hypermediaCollection->followLink('nextPage');
$hypermediaCollection = $hypermediaCollection->followLink('lastPage');
```

##### Get a new collection from the next page

```php
$hypermediaCollection = $hypermediaCollection->nextPage();
```
NB : it is an alias for `$hypermediaCollection->followLink('nextPage');`

##### Get a new collection from the previous page

```php
$hypermediaCollection = $hypermediaCollection->previousPage();
```
NB : it is an alias for `$hypermediaCollection->followLink('previousPage');`

##### Simple loop

```php
foreach($hypermediaCollection as $hypermediaItem)
{
    // You can use your HypermediaItem object
}
```

##### Automatic loop to go to the next page if necessary

You need to use the iterator to do this operation :

```php
$iterator = new HypermediaCollectionIterator($hypermediaCollection);
while($iterator->valid()) // Iterator is valid while there is a next item
{
    var_dump($iterator->current()); // Current HypermediaItem
    $iterator->next(); // Move the cursor to this next position
    if(!$iterator->valid() && $hypermediaCollection->hasNextPage()) {
        $hypermediaCollection = $hypermediaCollection->nextPage();
        $iterator = new HypermediaCollectionIterator(
            $hypermediaCollection
        );
    }
}
```

### Item

#### Retrieve the hypermedia item

You need to use the tms_rest_client.hypermedia.crawler service to retrieve
an hypermedia item. One method exists to do it :

* `find($name, $id)`

The crawler throws some exception of type ApiHttpResponseException if necessary.
That's why the crawler use must be surrounded by try and catch.

```php
try {
    $hypermediaItem = $this
        ->get('tms_rest_client.hypermedia.crawler')
        ->go('operation')
        ->find('offers', $id)
    ;
} catch (\Da\ApiClientBundle\Exception\ApiHttpResponseException $e) {
    switch ($e->getHttpCode()) {
        case 403:
            throw new AccessDeniedHttpException();
        case 404:
            throw new NotFoundHttpException();
    }
}
```

#### Works with the retrieved hypermedia item

Well, you retrieved your hypermedia item in the first step,
we can now play with it !

##### Get all metadata

```php
$hypermediaItem->getAllMetadata();
```

##### Get a specific metadata

```php
$hypermediaItem->getMetadata($metadataName);
```

##### Get all links

```php
$hypermediaItem->getLinks();
```

##### Get a specific link

```php
$hypermediaItem->getLink($linkName);
```

##### Get data

```php
$hypermediaItem->getData();
```

##### Set a data field

*Set a data field*
```php
$hypermediaItem->setDataField($field, $value);
```

##### Follow an embedded link to retrieve embeddeds collection

You can follow embeddeds links of an item to retrieve a new collection
by calling the new API url in link href.

For instance, you can get all the products related to our hypermedia
item offer by calling :

```php
$hypermediaCollection = $hypermediaItem->followEmbedded('products');
```

### Info path

The info path gives you some informations to manipulate your API.
You can retrieve it like that:

```php
$hypermedia = $this
    ->get('tms_rest_client.hypermedia.crawler')
    ->go('operation')
    ->getPathInfo('offers')
;

### Actions

On all your hypermedia, you have some available actions like update or delete for intance.
You can execute this actions like this:

```php
$hypermedia->executeAction($name, array $params = array(), $method = '');
```

Where:
* `$name` is the name of the action.
* `$params` are the parameters of the query (they are automaticaly retrieved from the data if you do not give them).
* `$method` is the HTTP method in case there is many available methods for the same action.

If the url of the action is a mask with some `{param}` placeholder, they will be replaced with the value of the corresponding parameters (here "param") given in the `$params` array (`array('params' => 'value')`).