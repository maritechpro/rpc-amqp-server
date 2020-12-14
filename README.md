# rpc-api-bundle
You should only create Requests and Actions 

example of /config/packages/rpc_api.yaml

```$yaml
rpc_api:

services:
  GepurIt\RpcApiBundle\Rabbit\ConsumerExchangeProviderInterface:
    alias: 'rpc.provider.client_api'

  rpc.provider.client_api:
    class: GepurIt\RpcApiBundle\Rabbit\ConsumerExchangeProvider
    arguments: ['@rabbit_mq', 'client_api_rpc']
    public: true

  GepurIt\RpcApiBundle\Request\RequestDataResolver:
    autowire: true
    calls:
      - {method: registerRequest, arguments: ['predictDiscount','App\RpcApi\Request\PredictDiscountRequestData']}
      - {method: registerRequest, arguments: ['updateLoyalty','App\RpcApi\Request\UpdateLoyaltyRequestData']}

  GepurIt\RpcApiBundle\Actions\ActionFactory:
    autowire: true
    calls:
      - {method: registerAction, arguments: ['predictDiscount','@App\RpcApi\Action\PredictDiscountAction']}
      - {method: registerAction, arguments: ['updateLoyalty','@App\RpcApi\Action\UpdateLoyaltyAction']}

  App\RpcApi\Action\:
    resource: '../../src/RpcApi/Action'
    autowire: true

```