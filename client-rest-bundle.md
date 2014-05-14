# TMS REST CLIENT #

## API CONFIGURATION ##

```yml
tms_rest_client:
    crawlers:
        operation_manager:
            da_api_client: da_api_client.api.operation
                resources: 
                    offers:
                        path: "/offers"
                        methods:
                            patchStatus:
                                path:       "/{id}/status"
                                method:     PATCH
                                arguments:  []
    
                    operations: 
                        path: "/operations"
    
                    operation_offers:
                        path: "/operations/{id}/offers"
    
                    sfr_offers:
                        path: "/customer/1/offers"
        hydrators:
            Tms\Bundle\OperationBundle\Entity: OfferHydrator
```

## API METHODS ##

### HYPERMEDIA COLLECTION ###

*Find all*
```php
$hypermediaCollection = $this
    ->get('tms_rest_client.crawlers.operation_manager.offers')
    ->findAll($query, HypermediaCollection::AUTO_SWITCH_PAGE_OFF / ON)
;
```

*Get a metadata*
```php
$hypermediaCollection->getMetaData($metadataName, $default);
```

*Get all metadata*
```php
$hypermediaCollection->getAllMetaData();
```

*Has metadata*
```php
$hypermediaCollection->hasMetaData($metadataName);
```

*Get data*
```php
$hypermediaCollection->getData();
```

*Go to next page*
```php
$hypermediaCollection
    ->nextPage()
    ->nextPage()
    ->nextPage()
;
```

*Go to previous page*
```php
$hypermediaCollection
    ->previousPage()
    ->previousPage()
    ->previousPage()
;
```

*Go to last page*
```php
$hypermediaCollection->lastPage();
```

*Go to first page*
```php
$hypermediaCollection->firstPage();
```

*Go to page X*
```php
$hypermediaCollection->page($page);
```

*Get the links*
```php
$hypermediaCollection->getLinks();
```

*Get a link*
```php
$hypermediaCollection->getLink($linkName);
```

*Has a link*
```php
$hypermediaCollection->hasLink($linkName);
```

*Follow a link*
```php
$hypermediaCollection->followLink($linkName, $linkParams, $isCrawlable);
```

*Loop*
```php
foreach ($hypermediaCollection as $hypermediaSingle) {
    $single = $hypermediaSingle->getData();
}
```

### HYPERMEDIA ITEM ###

*Find one*
```php
$hypermediaItem = $this
    ->get('tms_rest_client.crawlers.operation_manager.offers')
    ->findOneBy(array('id' => 1))
;
```

*Get embedded*
```php
$hypermediaItem = $hypermediaSingle->getEmbedded($embeddedName = null);
```

*Get metadata*
```php
$hypermediaItem->getMetaData($metadataName = null);
```

*Get data*
```php
$hypermediaItem->getData(HyperMediaSingle::HYDRATOR_MODE_ASSOC/HYDRATOR_MODE_OBJECT);
```

*Get the links*
```php
$hypermediaItem->getLinks();
```

*Get a link*
```php
$hypermediaItem->getLink($linkName);
```

*Has a link*
```php
$hypermediaItem->hasLink($linkName);
```

*Follow a link*
```php
$hypermediaItem->followLink($linkName, $linkParams, $isCrawlable);
```

------------------------------------------

*Delete an item*
```php
$this->get('tms_rest_client.operation.offers')->delete($hypermediaItem);
```

*Update an item*
```php
$this->get('tms_rest_client.operation.offers')->update($hypermediaItem);
```

## HYDRATORS ##

HydratorInterface implémentée par :

* ArrayHydrator
* ObjectHydrator
