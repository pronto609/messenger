<?php

namespace App\Messenger;

use App\Message\Command\LogEmoji;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ExternalJsonMessengerSerializer implements SerializerInterface
{

    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];

        $data = json_decode($body, true);

        if (null === $data) {
            throw new MessageDecodingFailedException('Invalid JSON');
        }

        if (!isset($data['emoji'])) {
            throw new MessageDecodingFailedException('Missing the emoji key!');
        }

        if (!isset($headers['type'])) {
            throw new MessageDecodingFailedException('Missing "type" header');
        }

        switch ($headers['type']) {
            case 'emoji':
                return $this->createLogEmojiEnvelop($data['emoji']);
            case 'delete_photo':
                break;
        }

        throw new MessageDecodingFailedException(
            sprintf('Invalid type "%s"', $headers['type'])
        );
    }

    public function encode(Envelope $envelope): array
    {
        throw [
            'body' => '',
            'headers' => ''
        ];
    }

    /**
     * @param $emoji
     * @return Envelope
     */
    public function createLogEmojiEnvelop($emoji): Envelope
    {
        $message = new LogEmoji($emoji);

        $envelope = new Envelope($message);

        $stamps = [];

        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }
        $envelope->with(... $stamps);

        //needed only if you need this to be sent through  the non-default bus
        $envelope->with(new BusNameStamp('command.bus'));
        return $envelope;
    }
}