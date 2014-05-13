#######################
## API CONFIGURATION ##
#######################

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

tms_rest_client:
    namespaces:
        operation:
            resources:
                offer:
                    da_api_client: da_api_client.api.operation
                    api:
                        find:
                            path: /offers
                            method: GET
                            #hydrator: 'hypermedia_single'
                            defaultParameters:  []
        #object_hydrators:
            #offer: \Tms\OperationClientBundle\Entity\Offer
            #operation: \Tms\OperationClientBundle\Entity\Operation

à discuter :
* hydrator dans config api
* type dans metadata (namespace VS nom entity)
* single / item / element
* methode patch avec hypermediaManager (cf en bas)

notes : 
* hypermediacollection implémente iterator
* lastPage / firstPage / goToPage vont être ajoutés
* AUTO_SWITCH_PAGE_OFF sur une collection pour automatiser le passage à la page suivante

#####################
## CRAWLER METHODS ##
#####################

---------------------------
## HYPERMEDIA COLLECTION ##
---------------------------

# FIND ALL
$hypermediaCollection = $this
    ->get('tms_rest_client.operation.offers')
    ->findAll($query, HypermediaCollection::AUTO_SWITCH_PAGE_OFF / ON)
;

# METADATA
$hypermediaCollection->getMetaData($metadataName = null);

# DATA
$hypermediaCollection->getData();

# NEXT
$hypermediaCollection
    ->nextPage()
    ->nextPage()
    ->nextPage()
;

# PREVIOUS
$hypermediaCollection
    ->previousPage()
    ->previousPage()
    ->previousPage()
;

# LAST
$hypermediaCollection->lastPage();

# FIRST
$hypermediaCollection->firstPage();

# GOTO
$hypermediaCollection->goToPage($page);

# LOOP
while($hypermediaCollection->next() != null) {
    $element = $hypermediaCollection->current();
}

foreach ($hypermediaCollection as $hypermediaSingle) {
    $single = $hypermediaSingle->getData();
}

----------------------------------------
## HYPERMEDIA SINGLE / ITEM / ELEMENT ##
----------------------------------------

# FIND ONE
$hypermediaSingle = $this
    ->get('tms_rest_client.operation.offers')
    ->findOneBy(array('id' => 1))
;

# EMBEDDED
$hypermediaCollection = $hypermediaSingle->getEmbedded($embeddedName = null);

# METADATA
$hypermediaSingle->getMetaData($metadataName = null);

# DATA
$hypermediaSingle->getData(HyperMediaSingle::HYDRATOR_MODE_ASSOC/HYDRATOR_MODE_OBJECT);

------------------------------------------

# DELETE
$this->get('tms_rest_client.operation.offers')->delete($hypermediaSingle);

# UPDATE
$this->get('tms_rest_client.operation.offers')->update($hypermediaSingle);

# FIELD PATCH
$this
    ->get('tms_rest_client.operation.offers')
    ->patchField($hypermediaSingle, array($key => $value))
;

###############
## HYDRATORS ##
###############

HydratorInterface implémentée par
* ArrayHydrator
* ObjectHydrator

----------------------------
OOP
$product->sell($user);
$user->buy($product);
$user->buy($product, $store);

SOA
$saleHandler = new SaleHandler();
$saleHandler->sell($product, $user, $store);

