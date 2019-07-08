<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use Teknoo\Sellsy\Transport\Guzzle;
use Teknoo\Sellsy\Sellsy;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\StreamedResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$stat = 1;
		$criteria = array();
		$user = $this->getUser(); 
		if($user->getSincemonth() != '')
		{
			$criteria['mois'] = $user->getSincemonth();
		}
		if(count($user->getOwners()) > 0)
		{
			foreach( $user->getOwners() as $owners)
			{
				$criteria['owners'][] = $owners->getId();
			}
		}
		if($user->getShowdelete() == 1)
		{
			$criteria['showdelete'] = 1;
		}
		if($request->query->get('mois') != ''){
			$criteria['mois'] = $request->query->get('mois');
		}
		$client = $em->getRepository( 'AppBundle:Client')->notContacted($stat,1,10,$criteria);
		$sans_contact = $this->mapped($client);
		$total_sans_contact = $client['total'];
		
		$client = $em->getRepository( 'AppBundle:Client')->withRdv($stat,1,10,$criteria);
		$with_rdv = $this->mapped($client);
		$total_with_rdv = $client['total'];
		
		$client = $em->getRepository( 'AppBundle:Client')->withoutRdv($stat,1,10,$criteria);
		$without_rdv = $this->mapped($client);
		$total_without_rdv = $client['total'];
		
		$client = $em->getRepository( 'AppBundle:Client')->suivi($stat,1,10,$criteria);
		$suivi = $this->mapped($client);
		$total_suivi = $client['total'];
		
		$client = $em->getRepository( 'AppBundle:Client')->relance($stat,1,10,$criteria);
		$relance = $this->mapped($client);
		$total_relance = $client['total'];
		
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'sans_contact' => $sans_contact,
			'total_sans_contact' => $total_sans_contact,
			'with_rdv' => $with_rdv,
			'total_with_rdv' => $total_with_rdv,
			'without_rdv' => $without_rdv,
			'total_without_rdv' => $total_without_rdv,
			'suivi' => $suivi,
			'total_suivi' => $total_suivi,
			'relance' => $relance,
			'total_relance' => $total_relance,
			'default_month' => isset($criteria['mois']) ? $criteria['mois'] : 1
        ]);
    }
	
	public function mapped($client)
	{
		$owner = array();
		foreach($client['result'] as $key => $value){
			if(!in_array($value['ownerforename'].' '.$value['ownername'],$owner)){
				$owner[] = $value['ownerforename'].' '.$value['ownername'];
			}
								
		}
		$final = array();
		foreach($owner as $o){
			foreach($client['result'] as $key => $value){
				if($o == $value['ownerforename'].' '.$value['ownername']){
					$final[$value['ownerforename'].' '.$value['ownername']][] = $value['ownerid'];
				}
									
			}
		}
		
		arsort($final);
		$owners = array();
		foreach($final as $k => $v){
			$owners[]=array('name' => $k,'value'=> count($v) ,'color' => $this->stringToColorCode($k) ,'id' => $v[0]);
		}
		
		return $owners;
	}
	
	public function stringToColorCode($str) {
		$code = dechex(crc32($str));
		$code = substr($code, 0, 6);
		return $code;
	}
	
	
	
	/**
     * @Route("/admin/sans-contact", name="sans-contact")
     */
    public function sansContactAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$owners = $em->getRepository( 'AppBundle:Owner')->notDeleted();
		$selected_mois = '';
		if($request->query->get('mois') != ''){
			$selected_mois = $request->query->get('mois');
		}
		$selected_owner = '';
		if($request->query->get('owner') != ''){
			$selected_owner = $request->query->get('owner');
		}
        return $this->render('default/sans-contact.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'owners' => $owners,
			'selected_mois' => $selected_mois,
			'selected_owner' => $selected_owner,
        ]);
    }
	
	/**
     * @Route("/admin/avec-rdvs", name="avec-rdvs")
     */
    public function avecRdvsAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$owners = $em->getRepository( 'AppBundle:Owner')->notDeleted();
		$selected_mois = '';
		if($request->query->get('mois') != ''){
			$selected_mois = $request->query->get('mois');
		}
		$selected_owner = '';
		if($request->query->get('owner') != ''){
			$selected_owner = $request->query->get('owner');
		}
        return $this->render('default/avec-rdvs.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'owners' => $owners,
			'selected_mois' => $selected_mois,
			'selected_owner' => $selected_owner,
        ]);
    }
	
	
	/**
     * @Route("/admin/sans-rdvs", name="sans-rdvs")
     */
    public function sansRdvsAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$owners = $em->getRepository( 'AppBundle:Owner')->notDeleted();
		$selected_mois = '';
		if($request->query->get('mois') != ''){
			$selected_mois = $request->query->get('mois');
		}
		$selected_owner = '';
		if($request->query->get('owner') != ''){
			$selected_owner = $request->query->get('owner');
		}
        return $this->render('default/sans-rdvs.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'owners' => $owners,
			'selected_mois' => $selected_mois,
			'selected_owner' => $selected_owner,
        ]);
    }
	
	/**
     * @Route("/admin/suivie-clts", name="suivie-clts")
     */
    public function suivieAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$owners = $em->getRepository( 'AppBundle:Owner')->notDeleted();
		$selected_mois = '';
		if($request->query->get('mois') != ''){
			$selected_mois = $request->query->get('mois');
		}
		$selected_owner = '';
		if($request->query->get('owner') != ''){
			$selected_owner = $request->query->get('owner');
		}
        return $this->render('default/suivie-clts.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'owners' => $owners,
			'selected_mois' => $selected_mois,
			'selected_owner' => $selected_owner,
        ]);
    }
	
	/**
     * @Route("/admin/relance", name="relance")
     */
    public function relanceAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$owners = $em->getRepository( 'AppBundle:Owner')->notDeleted();
		$selected_mois = '';
		if($request->query->get('mois') != ''){
			$selected_mois = $request->query->get('mois');
		}
		$selected_owner = '';
		if($request->query->get('owner') != ''){
			$selected_owner = $request->query->get('owner');
		}
        return $this->render('default/relance.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'owners' => $owners,
			'selected_mois' => $selected_mois,
			'selected_owner' => $selected_owner,
        ]);
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
			  $this->getParameter('user_token'),//User Token
			  $this->getParameter('user_secret'),//User Secret
			  $this->getParameter('consumer_token'),//Consumer Token
			  $this->getParameter('consumer_secret')//Consumer Secret
		  );

		  $sellsy->setTransport($transportBridge);
/*
		  //Example of request, follow the API documentation of Sellsy API.
		  print $sellsy->Infos()->getInfos()->getResponse()['consumerdatas']['id'].PHP_EOL;
		  print $sellsy->AccountPrefs()->getCorpInfos()->getResponse()['email'].PHP_EOL;
		  
*/	
		return $sellsy;
	}
	
	
	/**
     * @Route("/json-ind1", name="ind1")
     * @param  Request $request
     * @return Response
     */
    public function oneAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		$pagenum = 1;
		$nbperpage = 1000;
		$criteria = array();

		if($request->query->get('start') != ''){
			$pagenum = $request->query->get('start');
		}
		if($request->query->get('length') != ''){
			$nbperpage = $request->query->get('length');
		}
		
		if($request->query->get('mois') != ''){
			$criteria['mois'] = $request->query->get('mois');
		}
		
		if($request->query->get('owner') != ''){
			$criteria['owner'] = $request->query->get('owner');
		}
		
		if($request->query->get('search') != ''){
			$criteria['search'] = $request->query->get('search');
		}
		
		if($request->query->get('order') != ''){
			$criteria['order'] = $request->query->get('order');
		}
		
		$client = $em->getRepository( 'AppBundle:Client')->notContacted(0,$pagenum,$nbperpage,$criteria);
		
		$result['draw'] = $request->query->get('draw');
		$result['recordsTotal'] = $client['total'];
		$result['recordsFiltered'] = $client['total'];
		$result['data'] = array();
		foreach($client['result'] as $key => $value){
			$result['data'][] = array(	'<a href="https://www.sellsy.fr'.$value['clienturl'].'" target="_blank">'.$value['thirdid'].'</a>',
										$value['clientname'],
										'<a href="#" onclick="loadDynamicContentModal(\''.$value['id'].'\')">'.$value['ident'].'</a>',
										$value['mainAddress'],
										$value['email'],
										$value['tel'],
										$value['mobile'],
										$value['ownerforename'].' '.$value['ownername']
										
								);
		}
		
		
        return new JsonResponse($result);
    }
	
	/**
     * @Route("/json-ind21", name="ind21")
     * @param  Request $request
     * @return Response
     */
    public function two1Action(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		$pagenum = 1;
		$nbperpage = 1000;
		$criteria = array();

		if($request->query->get('start') != ''){
			$pagenum = $request->query->get('start');
		}
		if($request->query->get('length') != ''){
			$nbperpage = $request->query->get('length');
		}
		
		if($request->query->get('mois') != ''){
			$criteria['mois'] = $request->query->get('mois');
		}
		
		if($request->query->get('owner') != ''){
			$criteria['owner'] = $request->query->get('owner');
		}
		
		if($request->query->get('search') != ''){
			$criteria['search'] = $request->query->get('search');
		}
		
		if($request->query->get('order') != ''){
			$criteria['order'] = $request->query->get('order');
		}
		
		$client = $em->getRepository( 'AppBundle:Client')->withRdv(0,$pagenum,$nbperpage,$criteria);
		
		
		
        $result['draw'] = $request->query->get('draw');
		$result['recordsTotal'] = $client['total'];
		$result['recordsFiltered'] = $client['total'];
		$result['data'] = array();
		foreach($client['result'] as $key => $value){
			$result['data'][] = array(	'<a href="https://www.sellsy.fr'.$value['clienturl'].'" target="_blank">'.$value['thirdid'].'</a>',
										$value['clientname'],
										'<a href="#" onclick="loadDynamicContentModal(\''.$value['id'].'\')">'.$value['ident'].'</a>',
										$value['mainAddress'],
										$value['email'],
										$value['tel'],
										$value['mobile'],
										$value['ownerforename'].' '.$value['ownername']
										
								);	
		}
		
		
        return new JsonResponse($result);
    }
	
	/**
     * @Route("/json-ind22", name="ind22")
     * @param  Request $request
     * @return Response
     */
    public function two2Action(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		$pagenum = 1;
		$nbperpage = 1000;
		$criteria = array();

		if($request->query->get('start') != ''){
			$pagenum = $request->query->get('start');
		}
		if($request->query->get('length') != ''){
			$nbperpage = $request->query->get('length');
		}
		
		if($request->query->get('mois') != ''){
			$criteria['mois'] = $request->query->get('mois');
		}
		
		if($request->query->get('owner') != ''){
			$criteria['owner'] = $request->query->get('owner');
		}
		
		if($request->query->get('search') != ''){
			$criteria['search'] = $request->query->get('search');
		}
		
		if($request->query->get('order') != ''){
			$criteria['order'] = $request->query->get('order');
		}
		
		$client = $em->getRepository( 'AppBundle:Client')->withoutRdv(0,$pagenum,$nbperpage,$criteria);
		
		
		
        $result['draw'] = $request->query->get('draw');
		$result['recordsTotal'] = $client['total'];
		$result['recordsFiltered'] = $client['total'];
		$result['data'] = array();
		foreach($client['result'] as $key => $value){
			$result['data'][] = array(	'<a href="https://www.sellsy.fr'.$value['clienturl'].'" target="_blank">'.$value['thirdid'].'</a>',
										$value['clientname'],
										'<a href="#" onclick="loadDynamicContentModal(\''.$value['id'].'\')">'.$value['ident'].'</a>',
										$value['mainAddress'],
										$value['email'],
										$value['tel'],
										$value['mobile'],
										$value['ownerforename'].' '.$value['ownername']
										
								);
		}
        return new JsonResponse($result);
    }
	
	/**
     * @Route("/json-ind3", name="ind3")
     * @param  Request $request
     * @return Response
     */
    public function threeAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		$pagenum = 1;
		$nbperpage = 1000;
		$criteria = array();

		if($request->query->get('start') != ''){
			$pagenum = $request->query->get('start');
		}
		if($request->query->get('length') != ''){
			$nbperpage = $request->query->get('length');
		}
		
		if($request->query->get('mois') != ''){
			$criteria['mois'] = $request->query->get('mois');
		}
		
		if($request->query->get('owner') != ''){
			$criteria['owner'] = $request->query->get('owner');
		}
		
		if($request->query->get('search') != ''){
			$criteria['search'] = $request->query->get('search');
		}
		
		if($request->query->get('order') != ''){
			$criteria['order'] = $request->query->get('order');
		}
		
		$client = $em->getRepository( 'AppBundle:Client')->suivi(0,$pagenum,$nbperpage,$criteria);
		
		
		
        $result['draw'] = $request->query->get('draw');
		$result['recordsTotal'] = $client['total'];
		$result['recordsFiltered'] = $client['total'];
		$result['data'] = array();
		foreach($client['result'] as $key => $value){
			$result['data'][] = array(	'<a href="https://www.sellsy.fr'.$value['clienturl'].'" target="_blank">'.$value['thirdid'].'</a>',
										$value['clientname'],
										'<a href="#" onclick="loadDynamicContentModal(\''.$value['id'].'\')">'.$value['ident'].'</a>',
										$value['mainAddress'],
										$value['email'],
										$value['tel'],
										$value['mobile'],
										$value['ownerforename'].' '.$value['ownername']
										
								);
		}
        return new JsonResponse($result);
    }
	
	/**
     * @Route("/json-ind4", name="ind4")
     * @param  Request $request
     * @return Response
     */
    public function fourAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		$pagenum = 1;
		$nbperpage = 1000;
		$criteria = array();

		if($request->query->get('start') != ''){
			$pagenum = $request->query->get('start');
		}
		if($request->query->get('length') != ''){
			$nbperpage = $request->query->get('length');
		}
		
		if($request->query->get('mois') != ''){
			$criteria['mois'] = $request->query->get('mois');
		}
		
		if($request->query->get('owner') != ''){
			$criteria['owner'] = $request->query->get('owner');
		}
		
		if($request->query->get('search') != ''){
			$criteria['search'] = $request->query->get('search');
		}
		
		if($request->query->get('order') != ''){
			$criteria['order'] = $request->query->get('order');
		}
		
		$client = $em->getRepository( 'AppBundle:Client')->relance(0,$pagenum,$nbperpage,$criteria);
		
        $result['draw'] = $request->query->get('draw');
		$result['recordsTotal'] = $client['total'];
		$result['recordsFiltered'] = $client['total'];
		$result['data'] = array();
		foreach($client['result'] as $key => $value){
			$result['data'][] = array(	'<a href="https://www.sellsy.fr'.$value['clienturl'].'" target="_blank">'.$value['thirdid'].'</a>',
										$value['clientname'],
										'<a href="#" onclick="loadDynamicContentModal(\''.$value['id'].'\')">'.$value['ident'].'</a>',
										$value['mainAddress'],
										$value['email'],
										$value['tel'],
										$value['mobile'],
										$value['ownerforename'].' '.$value['ownername']
										
								);
		}
        return new JsonResponse($result);
    }
	
	/**
     * @Route("/export/{name}", name="export")
     * @param  Request $request
     * @return Response
     */
    public function exportAction(Request $request,$name)
    {
		$em = $this->getDoctrine()->getManager();
		
		$pagenum = 1;
		$nbperpage = 1000;
		$criteria = array();

		if($request->query->get('start') != ''){
			$pagenum = $request->query->get('start');
		}
		if($request->query->get('length') != ''){
			$nbperpage = $request->query->get('length');
		}
		
		if($request->query->get('mois') != ''){
			$criteria['mois'] = $request->query->get('mois');
		}
		
		if($request->query->get('owner') != ''){
			$criteria['owner'] = $request->query->get('owner');
		}
		
		if($request->query->get('search') != ''){
			$criteria['search'] = $request->query->get('search');
		}
		
		if($request->query->get('order') != ''){
			$criteria['order'] = $request->query->get('order');
		}
		
		switch($name)
		{
			case 'sans-contact':
				$client = $em->getRepository( 'AppBundle:Client')->notContacted(1,$pagenum,$nbperpage,$criteria);
			break;
			case 'avec-rdvs':
				$client = $em->getRepository( 'AppBundle:Client')->withRdv(1,$pagenum,$nbperpage,$criteria);
			break;
			case 'sans-rdvs':
				$client = $em->getRepository( 'AppBundle:Client')->withoutRdv(1,$pagenum,$nbperpage,$criteria);
			break;
			case 'suivie-clts':
				$client = $em->getRepository( 'AppBundle:Client')->suivi(1,$pagenum,$nbperpage,$criteria);
			break;
			case 'relance':
				$client = $em->getRepository( 'AppBundle:Client')->relance(1,$pagenum,$nbperpage,$criteria);
			break;
		}
		
        $response = new StreamedResponse();
		$response->setCallback(function() use ($client) {
			$handle = fopen('php://output', 'w+');
			$column = [];
			$column[] = 'ThirdId';
			$column[] = utf8_decode('Nom de la société');
			$column[] = 'Nom contact';
			$column[] = utf8_decode('Prénom contact');
			$column[] = 'Code client';
			$column[] = 'Adresse postale';
			$column[] = 'Adresse email';
			$column[] = utf8_decode('Télephone fixe');
			$column[] = utf8_decode('Télephone portable');
			$column[] = 'Conseiller';
			fputcsv($handle, $column, ';');
			
			foreach ($client['result'] as $key => $value) {
				$line = [];
				$line[] = $value['thirdid'];
				$line[] = utf8_decode($value['clientname']);
				$line[] = utf8_decode($value['lastname']);
				$line[] = utf8_decode($value['firstname']);
				$line[] = utf8_decode($value['ident']!=''?$value['ident']:$value['clientname']);
				$line[] = utf8_decode($value['mainAddress']);
				$line[] = $value['email'];
				$line[] = utf8_decode($value['tel']);
				$line[] = utf8_decode($value['mobile']);
				$line[] = utf8_decode($value['ownerforename'].' '.$value['ownername']);
				fputcsv( $handle,$line,';');
			}
			fclose($handle);
		});
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', 'text/csv; charset=utf-8');
		$response->headers->set('Content-Disposition','attachment; filename="export-'.$name.date("Y-m-d-H-i-s").'.csv"');
		return $response;
    }
	
	/**
     * @Route("/admin/client/{id}", name="view-client")
     */
    public function viewClientAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$client = $em->getRepository( 'AppBundle:Client')->find($id);
		return $this->render('default/view-client.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'client' => $client
        ]);
	}
	
	
		
	/**
     * @Route("/admin/phone", name="phone")
     */
    public function phoneAction(Request $request)
    {
		$sellsy = $this->initSellsy();	
		$prospect = $sellsy->Prospects()->getList(['pagination' => ['pagenum' => 1,'nbperpage' => 10]])->getResponse();
		$client = $sellsy->Client()->getList(['pagination' => ['pagenum' => 1,'nbperpage' => 10],
												'search' => ['email' => "dr.amiel@dentistementon"]])->getResponse();
		$contact = $sellsy->Client()->getContact(['clientid' => 13663953 , 'contactid' => 11917351])->getResponse();
		/*$contact = $sellsy->Peoples()->getList(['pagination' => ['pagenum' => 1,'nbperpage' => 10]])->getResponse();*/
		
		$staff = $sellsy->Staffs()->getList(['pagination' => ['pagenum' => 1,'nbperpage' => 1000]])->getResponse();
		
		$phone = $sellsy->Phone()->getList(['pagination' => ['pagenum' => 1,'nbperpage' => 100]])->getResponse();
		
		 $callTypes = $sellsy->Phone()->getCallTypes()->getResponse();
		
		$record = $sellsy->Phone()->recordAfterCall(['callid' => 144015 ])->getResponse();
		
       
        return $this->render('default/phone.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'prospect' => $prospect,
			'client' => $client,
			'contact' => $contact,
			'staff' => $staff,
			'phone' => $phone,
			'callTypes' =>  $callTypes,
			'record' =>  $record
        ]);
    }
	
	/**
     * @Route("/admin/agenda", name="agenda")
     */
    public function agendaAction(Request $request)
    {
		$sellsy = $this->initSellsy();	
		$agenda = array();
		$agenda = $sellsy->Agenda()->getList(['search' => ['type'=>'event','relatedId' => 13664952,'status' => array('ok','complete'),'includeRecurring' => 'Y']])->getResponse();
		
		echo '<pre>';
		print_r($agenda);
		echo '</pre>';
		
		die('kira');
		
		$availableLabel = array();
		//$availableLabel = $sellsy->Agenda()->getAvailableLabels()->getResponse();
		$client = array();
		$client = $sellsy->Client()->getOne(['clientid' => 13663986])->getResponse();
		$contact = array();
		//$contact = $sellsy->Client()->getContact(['clientid' => 13664094 , 'contactid' => 11917516])->getResponse();
		$staff = array();
		//$staff = $sellsy->Staffs()->getList()->getResponse();
		$event = array();
		$event = $sellsy->Event()->getList(['pagination' => ['pagenum' => 1,'nbperpage' => 1000],'search' => ['relatedid' => 13663986 ,'relatedtype' => 'client']])->getResponse();
		
        return $this->render('default/agenda.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'agenda' => $agenda,
			'availableLabel' => $availableLabel,
			'client' => $client,
			'contact' => $contact,
			'staff' => $staff,
			'event' => $event
        ]);
    }
	
	/**
     * @Route("/sellsy", name="webhook-sellsy")
     */
    public function webhookAction(Request $request,\Swift_Mailer $mailer)
    {
		$em = $this->getDoctrine()->getManager();
		$content =  json_decode($request->request->get('notif'));
		
		switch($content->event){
			case 'create':
				
			break;
			case 'deleted':
				switch($content->relatedtype){
					case 'third':
						if($content->thirdtype == 'client'){
							$client = $em->getRepository('AppBundle:Client')->findOneBy(array('thirdid' => (int)$content->relatedid));
							if($client !== null){
								$client->setActif("N");
								$em->persist($client);
								$em->flush();
							}
						}	
					break;
					case 'event':
						$agenda = $em->getRepository('AppBundle:Agenda')->findOneBy(array('agendaid' => (int)$content->relatedid));
						if($agenda !== null){
							$agenda->setActif("N");
							$em->persist($agenda);
							$em->flush();
						}
					break;
					case 'task':
						$agenda = $em->getRepository('AppBundle:Agenda')->findOneBy(array('agendaid' => (int)$content->relatedid));
						if($agenda !== null){
							$agenda->setActif("N");
							$em->persist($agenda);
							$em->flush();
						}
					break;
				}
				$mail = (new \Swift_Message('test'))
					->setFrom('jhtolotra@wylog.com')
					->setTo('jhtolotra@wylog.com')
					->setSubject('Sellsy Webhook Test')
					->setBody('delete'.print_r($content,true)
					,
					'text/html')
					->setContentType('text/html');
					$this->get('mailer')->send($mail);
			break;
			case 'delete':
				switch($content->relatedtype){
					case 'phonecall':
						$phone = $em->getRepository('AppBundle:Phone')->findOneBy(array('phoneid' => (int)$content->relatedid));
						if($phone !== null){
							$phone->setActif("N");
							$em->persist($phone);
							$em->flush();
						}
					break;
					
				}
				$mail = (new \Swift_Message('test'))
					->setFrom('jhtolotra@wylog.com')
					->setTo('jhtolotra@wylog.com')
					->setSubject('Sellsy Webhook Test')
					->setBody('delete'.print_r($content,true)
					,
					'text/html')
					->setContentType('text/html');
					$this->get('mailer')->send($mail);
			break;
			case 'update':
				switch($content->relatedtype){
					
				}
			break;
		}
		$result = array();
		return new JsonResponse($result);
    }
}
