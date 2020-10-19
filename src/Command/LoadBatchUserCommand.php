<?php

namespace App\Command;

use App\Etl\User\UserImportPipeline;
use App\Serializer\Normalizer\UserNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class LoadBatchUserCommand extends Command
{
  
    private EntityManagerInterface $entityManager;
    private ObjectNormalizer $normalizer;
    
    protected static $defaultName = 'load-batch-user';
    
    public function __construct(EntityManagerInterface $entityManager, ObjectNormalizer $normalizer)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->normalizer = $normalizer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Load batch user')
            ->addArgument('batchSize', InputArgument::REQUIRED, 'Number of user you want load')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $batchSize = $input->getArgument('batchSize');

        if ($batchSize) {
            $io->note(sprintf('You passed an argument: %s', $batchSize));
        }
        
        $io->success('Load');
    
        $pipeline = new UserImportPipeline(
            $this->entityManager,
            (int)$batchSize,
            new UserNormalizer($this->normalizer)
        );
        $pipeline->run();
        
        return Command::SUCCESS;
    }
}
