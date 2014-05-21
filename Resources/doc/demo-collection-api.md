{
    "metadata": {
        "type": "Tms\\Bundle\\OperationBundle\\Entity\\Offer",
        "page": 1,
        "pageCount": 2,
        "totalCount": 2,
        "limit": 20,
        "offset": 0
    },
    "data": [
        {
            "metadata": {
                "type": "Tms\\Bundle\\OperationBundle\\Entity\\Offer"
            },
            "data": {
                "id": 61,
                "reference": "test",
                "name": "teste",
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
                        "images": [],
                        "createdAt": "2014-03-11T15:12:50+0100",
                        "updatedAt": "2014-03-11T15:12:50+0100"
                    }
                ],
                "updatedAt": "2014-03-12T17:38:47+0100"
            },
            "links": {
                "self": {
                    "href": "http://operation-manager.local/app_dev.php/api/rest/offers/61.json"
                }
            }
        },
        {
            "metadata": {
                "type": "Tms\\Bundle\\OperationBundle\\Entity\\Offer"
            },
            "data": {
                "id": 62,
                "reference": "",
                "name": "toto",
                "startsAt": "-0001-11-30T00:00:00+0100",
                "endsAt": "-0001-11-30T00:00:00+0100",
                "status": "",
                "operations": []
            },
            "links": {
                "self": {
                    "href": "http://operation-manager.local/app_dev.php/api/rest/offers/62.json"
                }
            }
        }
    ],
    "links": {
        "self": {
            "href": "http://operation-manager.local/app_dev.php/api/rest/offers?page=1&limit=20&offset=0"
        },
        "next": "",
        "previous": ""
    }
}
