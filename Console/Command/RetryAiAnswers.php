<?php
namespace Vendor\ProductQnA\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vendor\ProductQnA\Model\ResourceModel\Question\CollectionFactory;
use Vendor\ProductQnA\Service\AiAnswerService;

class RetryAiAnswers extends Command
{
    private $questionCollectionFactory;
    private $aiAnswerService;

    public function __construct(
        CollectionFactory $questionCollectionFactory,
        AiAnswerService $aiAnswerService
    ) {
        parent::__construct();
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->aiAnswerService = $aiAnswerService;
    }

    protected function configure()
    {
        $this->setName('productqna:retry-ai-answers')
             ->setDescription('Retry AI answer generation for unanswered questions with AI preference');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Retrying AI answer generation for failed questions...</info>');

        // Get all questions with AI preference that don't have answers
        $collection = $this->questionCollectionFactory->create();
        $collection->addFieldToFilter('answer_preference', 'ai')
                   ->addFieldToFilter('status', ['neq' => 3]); // Not answered

        $count = 0;
        foreach ($collection as $question) {
            $output->writeln(sprintf(
                'Processing question #%d: %s',
                $question->getQuestionId(),
                substr($question->getQuestionText(), 0, 50) . '...'
            ));

            $success = $this->aiAnswerService->processQuestion($question->getQuestionId());
            
            if ($success) {
                $output->writeln('<info>✓ Answer generated successfully</info>');
                $count++;
            } else {
                $output->writeln('<error>✗ Failed to generate answer</error>');
            }
        }

        $output->writeln(sprintf('<info>Done! Generated %d answers.</info>', $count));
        
        return Command::SUCCESS;
    }
}
