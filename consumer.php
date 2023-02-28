    <?php
    public static function sendNotificationToMessageQueue($notification)
    {
 
        $url_str = "amqps://student:XYR4yqc.cxh4zug6vje@rabbitmq-exam.rmq3.cloudamqp.com/mxifnklj"
        or exit("CLOUDAMQP_URL not set");
        $url = parse_url($url_str);
        $vhost = substr($url['path'], 1);

        $connection = new AMQPStreamConnection($url['host'], 5672, $url['user'], $url['pass'], $vhost);

        $channel = $connection->channel();

        $channel->exchange_declare('exchange.f37e7201-a0cc-4444-84c0-2a7cf1245070', 'direct', false, false, false);
        $channel->queue_declare('exam', false, true, false, false);
        $channel->queue_bind('exam', 'exchange.f37e7201-a0cc-4444-84c0-2a7cf1245070', 'f37e7201-a0cc-4444-84c0-2a7cf1245070');

        $msg = new AMQPMessage('Hi CloudAMQP, this was fun!', array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ));

        $channel->basic_publish($msg, 'exchange.f37e7201-a0cc-4444-84c0-2a7cf1245070', 'f37e7201-a0cc-4444-84c0-2a7cf1245070');

        echo " [x] Sent 'Hi CloudAMQP, this was fun!' to exchange.f37e7201-a0cc-4444-84c0-2a7cf1245070 / exam.\n";

        $channel->exchange_delete('exchange.f37e7201-a0cc-4444-84c0-2a7cf1245070');
        $channel->close();
        $connection->close();
    }
    ?>
