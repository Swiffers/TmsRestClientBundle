# TMS REST CLIENT #

## API CONFIGURATION ##

```yml
da_api_client:
    api:
        operation:
            endpoint_root:  http://operation-manager.local.digifid.fr/api
            security_token: 1x612aoluwckgc00c8kccgskwog4gw88osw8w4sko8o0owso80
```

## API METHODS ##

### HYPERMEDIA COLLECTION ###

*Find all*
```php
$hypermediaCollection = $this
    ->get('tms_rest_client.hypermedia.crawler')
    ->go('operation')
    ->find('offers', $query)
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

*Execute an action (insert, update, delete, ...)*
```php
$hypermediaItem->executeAction($name, array $params = array(), $method = '');
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
    ->get('tms_rest_client.hypermedia.crawler')
    ->go('operation')
    ->findOne('offers', $id)
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

*Set a data field*
```php
$hypermediaItem->setDataField($field, $value);
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

*Execute an action (insert, update, delete, ...)*
```php
$hypermediaItem->executeAction($name, array $params = array(), $method = '');
```

### HYPERMEDIA INFO ###

*Find one*
```php
$hypermediaItem = $this
    ->get('tms_rest_client.hypermedia.crawler')
    ->go('operation')
    ->getInfoPath('offers')
;
```

*Execute an action (insert, update, delete, ...)*
```php
$hypermediaItem->executeAction($name, array $params = array(), $method = '');
```
