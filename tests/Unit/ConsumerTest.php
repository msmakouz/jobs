<?php

declare(strict_types=1);

namespace Spiral\RoadRunner\Jobs\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use Spiral\RoadRunner\Jobs\Consumer;
use Spiral\RoadRunner\Jobs\Exception\ReceivedTaskException;
use Spiral\RoadRunner\Jobs\Serializer\JsonSerializer;
use Spiral\RoadRunner\Jobs\Serializer\SerializerInterface;
use Spiral\RoadRunner\Payload;
use Spiral\RoadRunner\WorkerInterface;

final class ConsumerTest extends TestCase
{
    private Consumer $consumer;
    /** @var WorkerInterface|MockObject|WorkerInterface&MockObject */
    private WorkerInterface $woker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->consumer = new Consumer(
            $this->woker = $this->createMock(WorkerInterface::class),
            new JsonSerializer()
        );
    }

    public function testReceivedTask(): void
    {
        $this->woker->method('waitPayload')->willReturn(
            new Payload(
                \json_encode($payload = ['baz' => 'bar']),
                \json_encode([
                    'id'       => 'job-id',
                    'pipeline' => 'job-pipeline',
                    'job'      => 'job-name',
                    'headers'  => ['foo' => 'bar'],
                ])
            )
        );
        $task = $this->consumer->waitTask();

        $this->assertSame($payload, $task->getPayload());
        $this->assertSame('job-id', $task->getId());
        $this->assertSame('job-pipeline', $task->getQueue());
        $this->assertSame('job-name', $task->getName());
        $this->assertSame(['foo' => 'bar'], $task->getHeaders());
    }

    public function testEmptyBody(): void
    {
        $this->woker->method('waitPayload')->willReturn(
            new Payload(
                null,
                \json_encode([
                    'id'       => 'job-id',
                    'pipeline' => 'job-pipeline',
                    'job'      => 'job-name',
                    'headers'  => ['foo' => 'bar'],
                ])
            )
        );
        $task = $this->consumer->waitTask();

        $this->assertSame([], $task->getPayload());
    }

    public function testEmptyHeader(): void
    {
        $this->expectException(ReceivedTaskException::class);
        $this->expectErrorMessage('Task payload does not have a valid header.');

        $this->woker->expects($this->once())->method('waitPayload')->willReturn(
            new Payload(null)
        );
        $this->consumer->waitTask();
    }

    public function testSerializer()
    {
        $serializer = $this->createMock(SerializerInterface::class);

        $this->woker->method('waitPayload')->willReturn(
            new Payload(
                'foo=bar',
                \json_encode([
                    'id'       => 'job-id',
                    'pipeline' => 'job-pipeline',
                    'job'      => 'job-name',
                    'headers'  => ['foo' => 'bar'],
                ])
            )
        );

        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo('foo=bar'))
            ->willReturn(['foo' => 'bar']);

        $task = $this->consumer->withSerializer($serializer)->waitTask();

        $this->assertSame(['foo' => 'bar'], $task->getPayload());
    }
}
