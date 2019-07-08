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
class ImportUpdateCommand extends ContainerAwareCommand {
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('webdentiste:update:data')
            ->setDescription('Import et Update des données via Sellsy');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
        $callTypes = $this->importCalltype($output);
		$textOutput =  $callTypes." calltype(s) importé(s)";
		$output->writeln($textOutput);
		
		$agendaTypes = $this->importAgendatype($output);
		$textOutput =  $agendaTypes." agendatype(s) importé(s)";
		$output->writeln($textOutput);
		
		$owners = $this->importOwner($output);
		$textOutput =  $owners." owner(s) importé(s)";
		
		
		$contacts = $this->importContact($output);
		$textOutput =  $contacts." contact(s) importé(s)";
		
		
		$clients = $this->importClient($output);
		$textOutput =  $clients." client(s) importé(s)";
		
		
		$phones = $this->importPhone($output);
		$textOutput =  $phones." phone(s) importé(s)";
		
		
		$agendas = $this->importAgenda($output);
		$textOutput =  $agendas." agenda(s) importé(s)";
        
        $output->writeln($textOutput);
    }
	
	
	
	public function importCalltype(OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$sellsy = $this->initSellsy();	
		$callTypes = $sellsy->Phone()->getCallTypes()->getResponse();
		$nbtotal = count($callTypes['list']);
		
		foreach($callTypes['list'] as $result)
		{
			$phonetype = $em->getRepository('AppBundle:Phonetype')->findOneBy(array('calltypeid' => (int)$result['id']));
			if(count($phonetype) == 0){
				$phonetype = new Phonetype();
			}
			$phonetype->setCalltypeid((int)$result['id']);
			$phonetype->setValue($result['value']);
			$em->persist($phonetype);
			$em->flush();
		}
		return $nbtotal;
	}
	
	public function importAgendatype(OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$sellsy = $this->initSellsy();	
		$agendaTypes = $sellsy->Agenda()->getAvailableLabels()->getResponse();
		$nbtotal = count($agendaTypes);
		
		foreach($agendaTypes as $result)
		{
			$agendatypes = $em->getRepository('AppBundle:Agendatype')->findOneBy(array('labelid' => (int)$result['id']));
			if(count($agendatypes) == 0){
				$agendatypes = new Agendatype();
			}
			$agendatypes->setLabelid((int)$result['id']);
			$agendatypes->setValue($result['value']);
			$em->persist($agendatypes);
			$em->flush();
		}
		return $nbtotal;
	}
	
	public function importOwner(OutputInterface $output)
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
				$owner = $em->getRepository('AppBundle:Owner')->findOneBy(array('ownerid' => (int)$cl['ownerid']));
				if(count($owner) == 0){
					$owner = new Owner();
				}
				$owner->setOwnerid((int)$cl['ownerid']);
				$owner->setName($cl['owner']);
				$em->persist($owner);
				$em->flush();
			}
			$output->writeln(count($client['result'])." etape ".$i);
			
		}
		return $nbtotal;
	}
	
	public function importContact(OutputInterface $output)
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
				$contact = $em->getRepository('AppBundle:Contact')->findOneBy(array('contactid' => (int)$cl['maincontactid']));
				if(count($contact) == 0){
					$contact = new Contact();
				}
				$contact->setContactid((int)$cl['maincontactid']);
				$contact->setFirstname(isset($cl['contacts'][(int)$cl['maincontactid']]['forename'])?$cl['contacts'][(int)$cl['maincontactid']]['forename']:"");
				$contact->setLastname(isset($cl['contacts'][(int)$cl['maincontactid']]['name'])?$cl['contacts'][(int)$cl['maincontactid']]['name']:"");
				$em->persist($contact);
				$em->flush();
			}
			$output->writeln(count($client['result'])." etape ".$i);
			
		}
		return $nbtotal;
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
				$existClient->setContact($contact);
				$existClient->setRelationType($cl['relationType']);
				$existClient->setName($cl['name']);
				$existClient->setTel($cl['tel']);
				$existClient->setEmail($cl['email']);
				$existClient->setSiret($cl['siret']);
				if(isset($cl['mainAddress'])) $existClient->setMainAddress($cl['mainAddress']);
				$em->persist($existClient);
				$em->flush();
				
			}
			$output->writeln(count($client['result'])." etape ".$i);
			
		}
		return $nbtotal;
	}
	
	public function importPhone(OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$sellsy = $this->initSellsy();	
		$phone = $sellsy->Phone()->getList()->getResponse();
		$nbtotal = (int) $phone['infos']['nbtotal'];
		$nbpage = ceil($nbtotal / 1000);
		
		for ($i = 1; $i <= $nbpage; $i++) 
		{
			$phones = $sellsy->Phone()->getList(['pagination' => ['pagenum' => $i,'nbperpage' => 1000]])->getResponse();
			foreach($phones['result'] as $p){
				$existPhone = $em->getRepository('AppBundle:Phone')->findOneBy(array('phoneid' => (int)$p['id']));
				if(count($existPhone) == 0){
					$existPhone = new Phone();
				}
				$existPhone->setThirdid((int)$p['thirdid']);
				$existPhone->setPhoneid((int)$p['id']);
				$client = $em->getRepository('AppBundle:Client')->findOneBy(array('thirdid' => (int)$p['thirdid']));
				$existPhone->setClient($client);
				if(isset($p['activities'])){
					$phonetype = $em->getRepository('AppBundle:Phonetype')->findOneBy(array('calltypeid' => (int)$p['activities'][0]['calltypeid']));
					if($phonetype !== null){
						$existPhone->setPhonetype($phonetype);
					}
				}
				
				$existPhone->setStart($p['start']);
				$datetimeFormat = 'Y-m-d H:i:s';

				$date = new \DateTime();
				// If you must have use time zones
				// $date = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
				$date->setTimestamp($p['start']);
				$existPhone->setStartDate($date);
				$existPhone->setSource($p['source']);
				$existPhone->setThirdFullName(isset($p['thirdFullName'])?$p['thirdFullName']:'');
				$existPhone->setThirdUrl(isset($p['thirdUrl'])?$p['thirdUrl']:'');
				$existPhone->setStaffFullName(isset($p['staffFullName'])?$p['staffFullName']:'');
				$existPhone->setStaffEmail(isset($p['staffEmail'])?$p['staffEmail']:'');
				$em->persist($existPhone);
				$em->flush();
				
				
			}
			$output->writeln(count($phones['result'])." etape ".$i);
			
		}
		return $nbtotal;
	}
	
	public function importAgenda(OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$sellsy = $this->initSellsy();	
		$agenda = $sellsy->Agenda()->getList()->getResponse();
		$nbtotal = 0;
		foreach($agenda['result'] as $result)
		{
			$nbtotal += count($result['events']);
			foreach($result['events'] as $ag)
			{
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
				$em->persist($existAgenda);
				$em->flush();
			}
			$output->writeln(count($result['events'])." etape ".$result['id']);
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
