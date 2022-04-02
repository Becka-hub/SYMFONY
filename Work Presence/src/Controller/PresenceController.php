<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Presence;
use App\Repository\EmployeRepository;
use App\Repository\PresenceRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PresenceController extends AbstractController
{
    #[Route('/presence', name: 'presence')]
    public function index(EmployeRepository $employe, PresenceRepository $presence): Response
    {
        $employess = $employe->findAll();
        $mois = $presence->findByMois();
        $date = $presence->findByDate();
        return $this->render('presence/index.html.twig', [
            'controller_name' => 'Presence',
            'employe' => array_map(function (Employe $emp) {
                return $emp->tojson();
            }, $employess),
            'mois' => array_map(function (Presence $presMois) {
                return $presMois->tojson();
            }, $mois),
            'date' => array_map(function (Presence $presDate) {
                return $presDate->tojson();
            }, $date),
        ]);
    }


    #[Route('/ajoutePresence', name: 'ajoutePresence', methods: 'POST')]
    public function ajoutePresence(Request $request, EmployeRepository $employe,PresenceRepository $presenceRepos): Response
    {
        $datetime = new DateTime();
        $mois = $datetime->format('M');
        $date = $datetime->format('d');
        $idEmploye = $request->request->get('nomEmploye');
        $presenceUser=$presenceRepos->findOneBy(['employe'=>$idEmploye,'mois'=>$mois,'date'=>$date]);
        
        if($presenceUser && $presenceUser !== ""){
            return $this->json(['success' => 'User already presence!'], 200);
        }

        $employess = $employe->find($idEmploye);
        $user = $this->getUser();
        $presence = new Presence();
        $presence->setEmploye($employess);
        $presence->setUser($user);
        $presence->setMois($mois);
        $presence->setDate($date);
        $presence->setStatus("Présent");
        $em = $this->getDoctrine()->getManager();
        $em->persist($presence);
        $em->flush();
        return $this->json(['success' => 'Add presence success!'], 200);
    }

    #[Route('/affichePresence', name: 'affichePresence', methods: 'POST')]
    public function affichePresence(Request $request, PresenceRepository $presence): Response
    {
        $mois = $request->request->get('mois');
        $date = $request->request->get('date');
        $presences = $presence->findBy(['mois' => $mois, 'date' => $date]);
        return $this->json(array_map(function (Presence $pres) {
            return $pres->tojson();
        }, $presences), 200);
    }

    #[Route('/afficheAbsent', name: 'afficheAbsent', methods: 'POST')]
    public function afficheAbsent(Request $request, EmployeRepository $employe, PresenceRepository $presence)
    {
        $mois = $request->request->get('mois');
        $date = $request->request->get('date');
        $employes = $employe->findAll();
        $table = '<table class="table table-border-danger table-striped">
       <tr>
       <th>Name</th>
       <th>Last Name</th>
       <th>Email</th>
       <th>Phone Number</th>
       </tr> 
    ';
        $count=0;
        foreach ($employes as $resultat) {
            $presencess = $presence->findOneBy(['employe' => $resultat->getId(),'mois'=>$mois,'date'=>$date]);
            if ($presencess == "") {
                $table .= '
                        <tr class="">
                       <td>' . $resultat->getName() . '</td>
                       <td>' . $resultat->getLastName() . '</td>
                       <td>' . $resultat->getEmail() . '</td>
                       <td>' . $resultat->getPhone() . '</td>
                       </tr>
                       ';
                $count++;
            }
        }
        $table .= '</table>';

        return $this->json(['table' => $table,'count'=>$count], 200);
    }
}
