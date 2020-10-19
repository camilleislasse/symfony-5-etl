<?php

namespace App\Etl\User;

use App\Entity\User;
use Kiboko\Component\ETL\Flow\Extractor\ExtractorInterface;
use Kiboko\Component\ETL\Flow\Transformer\TransformerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserTransformer implements TransformerInterface
{
    public function transform(): \Generator
    {
    
        while ($object = yield) {
            var_dump($object);die;
        }
    }
    
}
