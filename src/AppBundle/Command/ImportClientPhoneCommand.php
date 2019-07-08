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
class ImportClientPhoneCommand extends ContainerAwareCommand {
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('import:cliphone:data')
            ->setDescription('Import et Update des données via Sellsy');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
        //$clients = $this->importClient($output);
		//$textOutput =  $clients." client(s) importé(s)";
		
		$phones = $this->importPhone($output);
		$textOutput =  $phones." phone(s) importé(s)";
		
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
				$existClient->setContact($contact);
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
				$existPhone->setActif("Y");
				$em->persist($existPhone);
				$em->flush();
				
				
			}
			$output->writeln(count($phones['result'])." etape ".$i);
			
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
