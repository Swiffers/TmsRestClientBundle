TmsRestClientBundle Configuration Reference
=====================================

REST client configuration
-------------------------

#### Configuration
You need to configure an agnostic crawler in `app/config/config.yml` file :

da_api_client:
    api:
        hypermedia.crawler:
            endpoint_root:  http://local.df_operation/app_dev.php/api/rest/

Then you need to define your crawler service in `MyBundle/Resources/config/services.yml` file :

parameters:
    tms_rest_client.crawler.class: Tms\Bundle\RestClientBundle\Crawler\HypermediaCrawler

services:
    tms_rest_client.crawler:
        class: %tms_rest_client.crawler.class%
        arguments: [@da_api_client.api.hypermedia.crawler] # the name of your previous crawler define in you config file