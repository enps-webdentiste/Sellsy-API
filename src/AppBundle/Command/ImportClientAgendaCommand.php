<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Tclient;
use AppBundle\Entity\Tagenda;
use AppBundle\Entity\TagendaLabel;
use AppBundle\Entity\Tphone;
use AppBundle\Entity\Tcalltype;
use AppBundle\Entity\Phonetype;
use AppBundle\Entity\Agendatype;
use AppBundle\Entity\Owner;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Phone;
use AppBundle\Entity\Agenda;
use AppBundle\Entity\Client as Cclient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

/**
 * Import des données via Sellsy
 *
 * @author Jacky
 */
class ImportClientAgendaCommand extends ContainerAwareCommand {
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('import:cliagenda:data')
            ->setDescription('Import et Update des données via Sellsy');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
        
		$clients = $this->importClient($output);
		$textOutput =  $clients." client(s) importé(s)";
		
		
		$agendas = $this->importAgenda($output);
		$textOutput =  $agendas." agenda(s) importé(s)";
        
        $output->writeln($textOutput);
    }
	
	
	public function importClient(OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$sellsy = $this->initSellsy();	
		$client = $sellsy->Client()->getList()->getResponse();
		$nbtotal = (int) $client['infos']['nbtotal'];
		$nbpage = ceil($nbtotal / 1000);
		
		for ($i = 1; $i <= $nbpage; $i++) 
		{
			$client = $sellsy->Client()->getList(['pagination' => ['pagenum' => $i,'nbperpage' => 1000]])->getResponse();
			foreach($client['result'] as $cl){
				$existClient = $em->getRepository('AppBundle:Client')->findOneBy(array('thirdid' => (int)$cl['thirdid']));
				if(count($existClient) == 0){
					$existClient = new Cclient();
				}
				$existClient->setThirdid((int)$cl['thirdid']);
				$existClient->setClienturl("/?_f=third&thirdid=".$cl['thirdid']);
				$existClient->setIdent($cl['ident']);
				$owner = $em->getRepository('AppBundle:Owner')->findOneBy(array( 'ownerid' =>(int)$cl['ownerid']));
				
				$existClient->setOwner($owner);
				$existClient->setType($cl['type']);
				$contact = $em->getRepository('AppBundle:Contact')->findOneBy(array( 'contactid' =>(int)$cl['maincontactid']));
				if( $contact !== null){
					$existClient->setContact($contact);
				}
				$existClient->setRelationType($cl['relationType']);
				$existClient->setName($cl['name']);
				$existClient->setTel($cl['tel']);
				$existClient->setMobile($cl['mobile']);
				$existClient->setEmail($cl['email']);
				$existClient->setSiret($cl['siret']);
				$existClient->setActif($cl['actif']);
				if(isset($cl['mainAddress'])) $existClient->setMainAddress($cl['mainAddress']);
				$em->persist($existClient);
				$em->flush();
				
			}
			$output->writeln(count($client['result'])." etape ".$i);
			
		}
		return $nbtotal;
	}
	
	
	public function importAgenda(OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$sellsy = $this->initSellsy();	
		$nbtotal = 0;
		//pour les 'task'
		
		$agenda = $sellsy->Agenda()->getList(['search' => ['type'=>'task','status' => array('ok','complete')]])->getResponse();
		
		foreach($agenda['result'] as $result)
		{
			$nbtotal += count($result['events']);
			foreach($result['events'] as $ag)
			{
				if(isset($ag['id'])){
					$existAgenda = $em->getRepository('AppBundle:Agenda')->findOneBy(array('agendaid' => (int)$ag['id']));
					if(count($existAgenda) == 0){
						$existAgenda = new Agenda();
					}
					$existAgenda->setAgendaid((int)$ag['id']);
					$client = $em->getRepository('AppBundle:Client')->findOneBy(array('thirdid' => (int)$ag['relatedid']));
					$existAgenda->setClient($client);
					$agendatype = $em->getRepository('AppBundle:Agendatype')->findOneBy(array('labelid' => (int)$ag['labelid']));
					$existAgenda->setAgendatype($agendatype);
					$existAgenda->setStart(new \DateTime($ag['start']));
					$existAgenda->setEnd(new \DateTime($ag['end']));
					$existAgenda->setTimestampStart($ag['timestampStart']);
					$existAgenda->setTimestampEnd($ag['timestampEnd']);
					$existAgenda->setState($ag['state']);
					$existAgenda->setType($ag['type']);
					$existAgenda->setActif("Y");
					$em->persist($existAgenda);
					$em->flush();
				}
			}
			$output->writeln('Task '.count($result['events'])." etape ".$result['id']);
		}
		
		//pour les 'event'
		$agenda = $sellsy->Agenda()->getList(['search' => ['type'=>'event','status' => array('ok','complete')]])->getResponse();
		
		foreach($agenda['result'] as $key => $result)
		{
			$nbtotal += count($result['events']);
			foreach($result['events'] as $ag)
			{
				if(isset($ag['id'])){
					$existAgenda = $em->getRepository('AppBundle:Agenda')->findOneBy(array('agendaid' => (int)$ag['id']));
					if(count($existAgenda) == 0){
						$existAgenda = new Agenda();
					}
					$existAgenda->setAgendaid((int)$ag['id']);
					$client = $em->getRepository('AppBundle:Client')->findOneBy(array('thirdid' => (int)$ag['relatedid']));
					$existAgenda->setClient($client);
					$agendatype = $em->getRepository('AppBundle:Agendatype')->findOneBy(array('labelid' => (int)$ag['labelid']));
					$existAgenda->setAgendatype($agendatype);
					$existAgenda->setStart(new \DateTime($ag['start']));
					$existAgenda->setEnd(new \DateTime($ag['end']));
					$existAgenda->setTimestampStart($ag['timestampStart']);
					$existAgenda->setTimestampEnd($ag['timestampEnd']);
					$existAgenda->setState($ag['state']);
					$existAgenda->setType($ag['type']);
					$existAgenda->setActif("Y");
					$em->persist($existAgenda);
					$em->flush();
				}
			}
			$output->writeln('Event '.count($result['events'])." etape ".$result['id']);
		}
		
		return $nbtotal;
	}
	
	public function initSellsy()
	{
		//Create the HTTP client
		  $guzzleClient = new Client();

		  //Create the transport bridge
		  $transportBridge = new Guzzle($guzzleClient);

		  //Create the front object
		  $sellsy = new Sellsy(
			  'https://apifeed.sellsy.com/0/',
			  $this->getContainer()->getParameter('user_token'),//User Token
			  $this->getContainer()->getParameter('user_secret'),//User Secret
			  $this->getContainer()->getParameter('consumer_token'),//Consumer Token
			  $this->getContainer()->getParameter('consumer_secret')//Consumer Secret
		  );

		  $sellsy->setTransport($transportBridge);
	
		return $sellsy;
	}
    
}
