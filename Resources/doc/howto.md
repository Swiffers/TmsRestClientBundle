TmsRestBundle
=============

A service which brings support for "hypermedia" links for REST web services
in client-side

How to use
----------

### Collection

#### Retrieve the hypermedia collection

You need to use the tms_rest_client.crawler service to retrieve
an hypermedia collection. Several methods exist to do it :

* findAll($name, array $params)
* crawle($path, array $params, $absolutePath = false)

The crawler throws some exception of type ApiHttpResponseException if necessary.
That's why the crawler use must be surrounded by try and catch.

try {
    $hypermediaCollection = $this
        ->get('tms_rest_client.crawler')
        ->findAll('offers', array(
            'limit' => 10,
            'page'  => 1
        ))
    ;

    $hypermediaCollection = $this
        ->get('tms_rest_client.crawler')
        ->crawle('offers')
    ;

    $hypermediaCollection = $this
        ->get('tms_rest_client.crawler')
        ->crawle('offers?limit=5')
    ;

    $hypermediaCollection = $this
        ->get('tms_rest_client.crawler')
        ->crawle(
            'http://local.df_operation_client/app_dev.php/offers',
            array(),
            1
        )
    ;
} catch (\Da\ApiClientBundle\Exception\ApiHttpResponseException $e) {
    switch ($e->getHttpCode()) {
        case '404':
            throw new NotFoundHttpException();
        case '500':
            throw new AccessDeniedHttpException($e->getMessage());
    }
}

#### Works with the retrieved hypermedia collection

Well, you retrieved your hypermedia collection in the first step,
we can now play with it !

##### Get all metadata

$hypermediaCollection->getAllMetadata();

##### Get a specific metadata

$hypermediaCollection->getMetadata($metadataName);

##### Get all links

$hypermediaCollection->getAllLinks();

##### Get a specific link

$hypermediaCollection->getLink($linkName);

##### Get data

$hypermediaCollection->getData();

##### Follow a link to get new hypermedia collection

You can follow links of a collection to retrieve a new collection
by calling the new API url in link href.

$hypermediaCollection = $hypermediaCollection->followLink($linkName);

For instance :
$hypermediaCollection = $hypermediaCollection->followLink('nextPage');
$hypermediaCollection = $hypermediaCollection->followLink('lastPage');

##### Get a new collection from the next page

$hypermediaCollection = $hypermediaCollection->nextPage();
NB : it is an alias for $hypermediaCollection->followLink('nextPage');

##### Get a new collection from the previous page

$hypermediaCollection = $hypermediaCollection->previousPage();
NB : it is an alias for $hypermediaCollection->followLink('previousPage');

##### Simple loop

foreach($hypermediaCollection as $hypermediaItem)
{
    // You can use your HypermediaItem object
}

##### Automatic loop to go to the next page if necessary

You need to use the iterator to do this operation :

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

### Item

#### Retrieve the hypermedia item

You need to use the tms_rest_client.crawler service to retrieve
an hypermedia item. One method exists to do it :

* find($name, $id)

The crawler throws some exception of type ApiHttpResponseException if necessary.
That's why the crawler use must be surrounded by try and catch.

try {
    $hypermediaItem = $this
        ->get('tms_rest_client.crawler')
        ->find('offers', $id)
    ;
} catch (\Da\ApiClientBundle\Exception\ApiHttpResponseException $e) {
    switch ($e->getHttpCode()) {
        case '404':
            throw new NotFoundHttpException();
        case '500':
            throw new AccessDeniedHttpException();
    }
}

#### Works with the retrieved hypermedia item

Well, you retrieved your hypermedia item in the first step,
we can now play with it !

##### Get all metadata

$hypermediaItem->getAllMetadata();

##### Get a specific metadata

$hypermediaItem->getMetadata($metadataName);

##### Get all links

$hypermediaItem->getAllLinks();

##### Get a specific link

$hypermediaItem->getLink($linkName);

##### Get data

$hypermediaItem->getData();

##### Follow an embedded link to retrieve embeddeds collection

You can follow embeddeds links of an item to retrieve a new collection
by calling the new API url in link href.

For instance, you can get all the products related to our hypermedia
item offer by calling :

$hypermediaCollection = $hypermediaItem->followEmbedded('products');

In