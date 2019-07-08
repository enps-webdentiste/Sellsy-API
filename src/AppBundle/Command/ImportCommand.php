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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

/**
 * Import des données via Sellsy
 *
 * @author Jacky
 */
class ImportCommand extends ContainerAwareCommand {
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('webdentiste:import:data')
            ->setDescription('Import des données via Sellsy');
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
		$agendaLables = $this->importAgendaLabel($output);
		$textOutput =  $agendaLables." label agenda(s) importé(s)";
		$phones = $this->importPhone($output);
		$textOutput =  $phones." phone(s) importé(s)";
		$callTypes = $this->importCalltype($output);
		$textOutput =  $callTypes." calltype(s) importé(s)";
		
        
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
				$Tc = new Tclient();
				$Tc->setThirdid((int)$cl['thirdid']);
				$Tc->setThirdurl("/?_f=third&thirdid=".$cl['thirdid']);
				$Tc->setIdent($cl['ident']);
				$Tc->setOwnerid((int)$cl['ownerid']);
				$Tc->setType($cl['type']);
				$Tc->setMaincontactid((int)$cl['maincontactid']);
				$Tc->setRelationType($cl['relationType']);
				$Tc->setName($cl['name']);
				$Tc->setTel($cl['tel']);
				$Tc->setEmail($cl['email']);
				$Tc->setSiret($cl['siret']);
				if(isset($cl['mainAddress'])) $Tc->setMainAddress($cl['mainAddress']);
				$Tc->setOwner($cl['owner']);
				$em->persist($Tc);
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
		$agenda = $sellsy->Agenda()->getList()->getResponse();
		$nbtotal = 0;
		foreach($agenda['result'] as $result)
		{
			$nbtotal += count($result['events']);
			foreach($result['events'] as $ag)
			{
				$Ag = new Tagenda();
				$Ag->setIdType($result['id']);
				$Ag->setRelatedtype($ag['relatedtype']);
				$Ag->setRelatedid((int)$ag['relatedid']);
				$Ag->setOwnerid((int)$ag['ownerid']);
				$Ag->setStart(new \DateTime($ag['start']));
				$Ag->setEnd(new \DateTime($ag['end']));
				$Ag->setTimestampStart($ag['timestampStart']);
				$Ag->setTimestampEnd($ag['timestampEnd']);
				$Ag->setLabelid((int)$ag['labelid']);
				$Ag->setLabelname(isset($ag['labelname'])?$ag['labelname']:'');
				$Ag->setOwnerfullname($ag['ownerfullname']);
				$Ag->setState($ag['state']);
				$em->persist($Ag);
				$em->flush();
			}
			$output->writeln(count($result['events'])." etape ".$result['id']);
		}
		
		return $nbtotal;
	}
	
	public function importAgendaLabel(OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$sellsy = $this->initSellsy();	
		$agendaLabel = $sellsy->Agenda()->getAvailableLabels()->getResponse();
		$nbtotal = count($agendaLabel);
		foreach($agendaLabel as $result)
		{
			$Al = new TagendaLabel();
			$Al->setLabelid((int)$result['id']);
			$Al->setValue($result['value']);
			$em->persist($Al);
			$em->flush();
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
			$phone = $sellsy->Phone()->getList(['pagination' => ['pagenum' => $i,'nbperpage' => 1000]])->getResponse();
			foreach($phone['result'] as $p){
				$Ph = new Tphone();
				$Ph->setThirdid((int)$p['thirdid']);
				$Ph->setPeopleid((int)$p['peopleid']);
				$Ph->setStart($p['start']);
				$datetimeFormat = 'Y-m-d H:i:s';

				$date = new \DateTime();
				// If you must have use time zones
				// $date = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
				$date->setTimestamp($p['start']);
				$Ph->setStartDate($date);
				$Ph->setSource($p['source']);
				$Ph->setThirdFullName(isset($p['thirdFullName'])?$p['thirdFullName']:'');
				$Ph->setThirdUrl(isset($p['thirdUrl'])?$p['thirdUrl']:'');
				$Ph->setStaffFullName(isset($p['staffFullName'])?$p['staffFullName']:'');
				$Ph->setStaffEmail(isset($p['staffEmail'])?$p['staffEmail']:'');
				$em->persist($Ph);
				$em->flush();
			}
			$output->writeln(count($phone['result'])." etape ".$i);
			
		}
		return $nbtotal;
	}
	
	public function importCalltype(OutputInterface $output)
	{
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$sellsy = $this->initSellsy();	
		$callTypes = $sellsy->Phone()->getCallTypes()->getResponse();
		$nbtotal = count($callTypes['list']);
		
		foreach($callTypes['list'] as $result)
		{
			$Cal = new Tcalltype();
			$Cal->setCalltypeid((int)$result['id']);
			$Cal->setValue($result['value']);
			$em->persist($Cal);
			$em->flush();
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
