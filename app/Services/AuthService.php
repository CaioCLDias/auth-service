<?php

namespace App\Services;


use App\Interfaces\UserRepositoryInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AuthService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function login($request)
    {
        $user = $this->userRepository->authenticate($request);
        $this->publishLoginEvent($user);

        return $user;
    }

    /**
     * @param $user
     * @return void
     * @throws \Exception
     */
    private function publishLoginEvent($user): void
    {
        $host = getenv('RABBITMQ_HOST') ?: 'localhost';
        $user_mq = getenv('RABBITMQ_USER') ?: 'guest';
        $pass_mq = getenv('RABBITMQ_PASS') ?: 'guest';
        $port = getenv('RABBITMQ_PORT') ?: 5672;
        $connection = new AMQPStreamConnection($host, $port, $user_mq, $pass_mq);
        $channel = $connection->channel();

        $channel->queue_declare('login_events', false, false, false, false);

        $msg = new AMQPMessage(json_encode($user));
        $channel->basic_publish($msg, '', 'login_events');

        $channel->close();
        $connection->close();
    }
}
