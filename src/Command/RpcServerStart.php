<?php
/**
 * @author: Mari <m934222258@gmail.com>
 * @since : 28.09.2020
 */

namespace GepurIt\RpcApiBundle\Command;

use AMQPEnvelope;
use AMQPQueue;
use AMQPChannelException;
use AMQPConnectionException;
use AMQPExchangeException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GepurIt\RpcApiBundle\Actions\ActionFactory;
use GepurIt\RpcApiBundle\Exception\RpcException;
use GepurIt\RpcApiBundle\Rabbit\ConsumerExchangeProviderInterface;
use GepurIt\RpcApiBundle\Request\IncomingRequest;
use GepurIt\RpcApiBundle\Request\RequestDataResolver;
use GepurIt\RpcApiBundle\Response\ErrorResponse;
use GepurIt\RpcApiBundle\Response\ExceptionResponse;
use GepurIt\RpcApiBundle\Response\RpcResponse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RpcServerStart
 * @package GepurIt\RpcApiBundle\Command
 */
class RpcServerStart extends Command
{
    private InputInterface $input;
    private OutputInterface $output;
    private ConsumerExchangeProviderInterface $exchangeProvider;
    private ActionFactory $actionFactory;
    private RequestDataResolver $requestDataResolver;
    private EntityManagerInterface $entityManager;

    /**
     * RpcServerStart constructor.
     *
     * @param ConsumerExchangeProviderInterface $exchangeProvider
     * @param ActionFactory                     $actionFactory
     * @param RequestDataResolver               $requestDataResolver
     * @param EntityManagerInterface            $entityManager
     */
    public function __construct(
        ConsumerExchangeProviderInterface $exchangeProvider,
        ActionFactory $actionFactory,
        RequestDataResolver $requestDataResolver,
        EntityManagerInterface $entityManager
    ) {
        $this->exchangeProvider = $exchangeProvider;
        $this->actionFactory = $actionFactory;
        $this->requestDataResolver = $requestDataResolver;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('rpc:client_api:consume')
            ->setDescription('Listen client_api_rpc queue from ERP and response predictDiscount or another action')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->output->writeln((new DateTime("now"))->format("Y-m-d H:i:s")." RPC server started");

        $this->exchangeProvider->consume([$this, 'processEnvelope']);
        // ToDo resolve this
//        while (count($this->channel->callbacks)) {
//            $this->channel->wait();
//        }
    }

    /**
     * @param AMQPEnvelope $envelope
     * @param AMQPQueue    $queue
     *
     * @return void
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     */
    public function processEnvelope(AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $request = IncomingRequest::fromAMQPEnvelope($envelope);
        $this->output->writeln(
            (new DateTime("now"))->format("Y-m-d H:i:s")." Got message {$request->correlationId}, action {$request->actionName}"
        );
        $this->goodMorning();
        try {
            $response = $this->dispatchAction($request);
        } catch (RpcException $exception) {
            $response = new ErrorResponse($exception->getMessage());
        } catch (Exception $exception) {
            $response = new ExceptionResponse($exception);
        }
        $this->sendResponse($response, $request);
        $this->goodNight();

        $queue->ack($envelope->getDeliveryTag());
    }

    /**
     * @param RpcResponse     $response
     * @param IncomingRequest $incomingRequest
     *
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     */
    public function sendResponse(RpcResponse $response, IncomingRequest $incomingRequest)
    {
        $this->exchangeProvider->getReplyToExchange($incomingRequest->replyTo)->publish(
            json_encode($response),
            $incomingRequest->replyTo,
            AMQP_NOPARAM,
            [
                'content_type'  => 'application/json',
                'delivery_mode' => 2,
                'message_id'    => $incomingRequest->correlationId,
                'correlation_id' => $incomingRequest->correlationId
            ]
        );
    }

    /**
     * @param IncomingRequest $request
     *
     * @return RpcResponse
     */
    private function dispatchAction(IncomingRequest $request)
    {
        $action = $this->actionFactory->getAction($request->actionName);
        $data   = $this->requestDataResolver->resolve($request);
        return $action->dispatch($data);
    }

    /**
     * open connections
     */
    private function goodMorning()
    {
        if ($this->entityManager->getConnection()->ping() === false) {
            $this->entityManager->getConnection()->close();
            $this->entityManager->getConnection()->connect();
        }
    }

    /**
     * close connections
     */
    private function goodNight()
    {
        $this->entityManager->getConnection()->close();
    }
}
