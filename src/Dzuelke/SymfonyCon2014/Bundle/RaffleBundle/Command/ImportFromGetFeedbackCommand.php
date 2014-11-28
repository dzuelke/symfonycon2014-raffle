<?php
    
// src/Acme/DemoBundle/Command/GreetCommand.php
namespace Dzuelke\SymfonyCon2014\Bundle\RaffleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportFromGetFeedbackCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('raffle:import')
            ->setDescription('Import responses from GetFeedback')
            ->addArgument('survey_id', InputArgument::REQUIRED, 'GetFeedback Survey ID')
            ->addArgument('repository_name', InputArgument::REQUIRED, 'Repository to use for mapping (Bundle:Docname)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $survey_id = $input->getArgument('survey_id');
        $repository_name = $input->getArgument('repository_name');
        
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $repository = $dm->getRepository($repository_name);
        $document_class = $repository->getClassName();
        
        // clear first
        foreach($repository->findAll() as $doc) {
            $dm->remove($doc);
        }
        
        $ctx = stream_context_create(array('http' => array('header' => array('Accept: application/json', 'Authorization: Bearer ' . $this->getContainer()->getParameter('env.getfeedback_api_token')))));
        
        $page = 0;
        while(true) {
            $page++;
            $data = file_get_contents("https://api.getfeedback.com/surveys/$survey_id/responses?per_page=100&page=$page", false, $ctx);
        
            if(!$data) {
                throw new Exception('failed to fetch from API');
            }
        
            $data = json_decode($data, true);
            if(!$data) {
                throw new Exception('failed to decode JSON');
            }
            
            if(!count($data['active_models'])) {
                $output->writeln("finished");
                break;
            }
            
            foreach($data['active_models'] as $response) {
                if($response['status'] != 'completed') continue;
            
                $entrant = new $document_class();
                try {
                    $entrant->hydrate($response);
                } catch(\Exception $e) {
                    continue;
                }
            
                $output->write(".");
            
                $dm->persist($entrant);
            }
            $output->writeln("done with part $page");
        }
        
        $dm->flush();
    }
}