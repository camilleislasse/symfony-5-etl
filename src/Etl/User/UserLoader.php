<?php

namespace App\Etl\User;

use Doctrine\Persistence\ObjectManager;
use Kiboko\Component\ETL\Flow\FlushableInterface;
use Kiboko\Component\ETL\Flow\Loader\LoaderInterface;
use Kiboko\Component\ETL\Pipeline\Bucket\AcceptanceResultBucket;
use Kiboko\Component\ETL\Pipeline\Bucket\EmptyResultBucket;
use Kiboko\Component\ETL\Pipeline\Bucket\ResultBucketInterface;
use Kiboko\Component\ETL\Pipeline\EmptyBucket;
use Kiboko\Component\ETL\Pipeline\GenericBucket;

class UserLoader implements LoaderInterface, FlushableInterface
{
    private int $batchSize;
    
    private ObjectManager $om;
    
    private array $detachable;
    
    public function __construct(
        int $batchSize,
        ObjectManager $om
    ) {
        $this->batchSize = $batchSize;
        $this->om = $om;
        $this->detachable = [];
    }
    
    public function load(): \Generator
    {
        $itemCount = 0;
        while ($object = yield) {
            $this->persist($object);

            if ($this->batchSize <= ++$itemCount) {
                $this->flushBatch();
                $itemCount = 0;
            }

            yield new GenericBucket($object);
        }

        $this->flushBatch();
    }

    private function flushBatch()
    {
        try {
            $this->om->flush();
            $this->om->clear();
        } catch (\Exception $exception) {
            throw new \RuntimeException('', 0, $exception);
        }
    }

    private function persist(object $entity)
    {
        $this->om->persist($entity);
    }

    public function flush(): ResultBucketInterface
    {
        $this->flushBatch();
        return new EmptyBucket();
    }
}
