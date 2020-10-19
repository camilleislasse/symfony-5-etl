<?php

namespace App\Etl\User;


use App\Entity\User;
use App\Serializer\Normalizer\UserNormalizer;
use Doctrine\Persistence\ObjectManager;
use Kiboko\Component\ETL\Flow\Extractor\SplCSVExtractor;
use Kiboko\Component\ETL\Flow\Transformer\DenormalizationTransformer;
use Kiboko\Component\ETL\Pipeline;
use Kiboko\Component\ETL\Pipeline\PipelineRunner;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserImportPipeline implements
    Pipeline\Feature\TransformingInterface,
    Pipeline\Feature\LoadingInterface,
    LoggerAwareInterface
{
    use PipelineDecoratorTrait;
    use LoggerAwareTrait;

    /** @var Pipeline\PipelineInterface */
    private $decorated;

    public function __construct(
        ObjectManager $om,
        int $batchSize,
        UserNormalizer $normalizer
    ) {
        $this->decorated = (new Pipeline\Pipeline(new PipelineRunner()))
            ->extract(new SplCSVExtractor(new \SplFileObject(__DIR__ . '/users.csv', 'r')))
            ->transform(new DenormalizationTransformer($normalizer, User::class))
            ->load(new UserLoader($batchSize, $om));
    }
}
