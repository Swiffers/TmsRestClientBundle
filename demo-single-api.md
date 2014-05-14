{
    "metadata": {
        "type": "Tms\\Bundle\\OperationBundle\\Entity\\Offer"
    },
    "data": {
        "id": 61,
        "reference": "test",
        "name": "teste",
        "shortDescription": "test",
        "longDescription": "<br>",
        "benefitDescription": "<br>",
        "startsAt": "2013-11-01T16:00:00+0100",
        "endsAt": "2014-03-27T16:00:00+0100",
        "status": "NEW",
        "operations": [
            {
                "id": 1,
                "reference": "sdfgsdgd",
                "customerReference": "15465#5646#45646",
                "name": "sdfgsdg",
                "startsAt": "2014-03-04T00:00:00+0100",
                "endsAt": "2014-03-28T00:00:00+0100",
                "campaign": {
                    "id": 1,
                    "name": "fqsdfqsd",
                    "starts_at": "2014-03-11T00:00:00+0100",
                    "ends_at": "2014-03-14T00:00:00+0100",
                    "createdAt": "2014-03-11T15:10:15+0100",
                    "updatedAt": "2014-03-11T15:10:15+0100"
                },
                "modality": {
                    "id": 1,
                    "description": "desc mod ope<br>",
                    "information": "info mod ope",
                    "terms": "cond mod ope",
                    "createdAt": "2014-03-11T15:17:15+0100",
                    "updatedAt": "2014-03-11T15:17:15+0100"
                },
                "images": [],
                "offers": [],
                "nonComplianceCodes": [],
                "operationCustomerParticipationStates": [],
                "operationInsurances": [],
                "offerModalities": [
                    {
                        "id": 1,
                        "description": "desc mod offre",
                        "terms": "desc mod offre",
                        "createdAt": "2014-03-11T15:19:39+0100",
                        "updatedAt": "2014-03-11T15:19:39+0100"
                    }
                ],
                "createdAt": "2014-03-11T15:12:50+0100",
                "updatedAt": "2014-03-11T15:12:50+0100"
            }
        ],
        "modalities": [
            {
                "id": 1,
                "description": "desc mod offre",
                "terms": "desc mod offre",
                "createdAt": "2014-03-11T15:19:39+0100",
                "updatedAt": "2014-03-11T15:19:39+0100"
            }
        ],
        "participation": {
            "id": 12,
            "onlineEnabled": true,
            "offlineEnabled": false,
            "benefits": [
                {
                    "id": 1,
                    "handlerServiceId": "virement",
                    "priority": 1,
                    "options": "{'toto':'titi'}",
                    "updatedAt": "2014-03-11T15:22:28+0100"
                }
            ],
            "eligibilities": [
                {
                    "id": 1,
                    "handlerServiceId": "sdfgsdf",
                    "priority": 1,
                    "createdAt": "2014-03-11T15:21:45+0100",
                    "updatedAt": "2014-03-11T15:21:45+0100"
                }
            ],
            "updatedAt": "2014-03-11T15:23:08+0100"
        },
        "updatedAt": "2014-03-12T17:38:47+0100"
    },
    "links": {
        "self": "http://operation-manager.local/app_dev.php/api/rest/offers/61"
    },
    "embedded": {
        "products": {
            "metadata": {
                "type": "Tms\\Bundle\\OperationBundle\\Entity\\Product"
            },
            "data": [
                {
                    "data": {
                        "id": 1,
                        "name": "produit1",
                        "ean13": 324645,
                        "shortDescription": "descsxs",
                        "longDescription": "<br>",
                        "updatedAt": "2014-03-11T15:16:04+0100"
                    },
                    "links": {
                        "self": {
                            "href": "http://operation-manager.local/app_dev.php/api/rest/products/1"
                        }
                    },
                    "metadata": {
                        "type": "Tms\\Bundle\\OperationBundle\\Entity\\Product"
                    }
                }
            ],
            "links": {
                "href": "http://operation-manager.local/app_dev.php/api/rest/offers/61/products"
            }
        }
    }
}
