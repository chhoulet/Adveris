<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Team_member;
use ProjectBundle\Form\Team_memberType;
use ProjectBundle\Form\RemoveMemberType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamMemberController extends Controller
{
	//Ce controller va servir à la fois pour la création d'équipe projet et la mise à jour d'users sur un projet.
	//Dans le 1er cas, l'action va avoir pour origine un button en homepage, avec instanciation d'un objet TeamMember
	//Dans le 2eme, l'ajout ou le retrait d'un user sur un projet correspond à une mise à jour, d'où parametre idTeamMember optionnel
	public function addTeamMemberAction(Request $request, $origin, $idTeamMember=null)
	{
		$em=$this->getDoctrine()->getManager();
		$session=$request->getSession();
		
		//Instantiation d'une Team_member
		if($origin == 1)
		{
			$teamMember=new Team_member();			
		}
		else
		{
			//Contrôle de l'existence et de la validité de TeamMember
			if($idTeamMember != 0)
			{
				$listTeamMembers=$em->getRepository('ProjectBundle:Team_member')->findAll();
				$listIdTeams=[];
				foreach($listTeamMembers as $team)
				{
					$idTeam=$team->getId();
					$listIdTeams[]=$idTeam;
				}
				if(in_array($idTeamMember, $listIdTeams))
				{
					$teamMember=$em->getRepository('ProjectBundle:Team_member')->find($idTeamMember);
					//Récupération des id des users existants pour controle de duplication de clés primaires:
					$usersBeforeForm=$teamMember->getUsers();
					$listIdUserBeforeForm=[];
					foreach($usersBeforeForm as $userBeforeForm)
					{
						$idUser=$userBeforeForm->getId();
						$listIdUserBeforeForm[]=$idUser;
					}
				}
			}
			else
			{
				throw new NotFoundHttpException("Cette équipe n'existe pas !");
			}
		}

		// Création du formulaire avec en parametre notre objet $teamMember
		$form=$this->createForm(new Team_memberType(), $teamMember);

		$form->handleRequest($request);

		if($form->isValid())
		{	
			// En fonction de l'origine (int), 3 actions différentes pour les données users:	
			// Création, mise à jour et suppression d'users d'une team-member
			$users=$teamMember->getUsers();			

			if($origin == 1)
			{				
				if($users)
				{			
					foreach($users as $user)		
					{
						$user->addTeamMember($teamMember);													
					}
					
					$em->persist($teamMember);
					$session->getFlashBag()->add('addTeam','Cette équipe est créée et est affectée à ' . ' '. $teamMember->getProject()->getName());
				}
			}

			// Mise à jour d'une team-member
			elseif($origin == 2)
			{				
				if($users)
				{
					//Controle de non-duplication
					foreach($users as $user)
					{
						$id=$user->getId();
						if(!in_array($id, $listIdUserBeforeForm))
						{
							$user->addTeamMember($teamMember);							
							$session->getFlashBag()->add('addMember','Ce changement d\'utilisateur(s) est enregistré et appliqué à ce projet : ' . ' ' . $teamMember->getProject()->getName());
						}
						else
						{
							$user=$em->getRepository('UserBundle:User')->find($id);
							$session->getFlashBag()->add('duplication', $user->getFullName() . ' figure déjà dans cette équipe !');
							return $this->redirect($this->generateUrl('project_homepage'));							 
						}
					}
				}
			}

			//Suppression d'user de la team-member
			elseif($origin == 3)
			{
				if($users)
				{
					foreach($users as $user)
					{
						$id=$user->getId();
						if(in_array($id, $listIdUserBeforeForm))
						{
							$user->removeTeamMember($teamMember);														
						}						
						else
						{
							$user=$em->getRepository('UserBundle:User')->find($id);
							$session->getFlashBag()->add('duplication', $user->getFullName() . ' ne figure pas dans dans cette équipe !');
							return $this->redirect($this->generateUrl('project_homepage'));						 
						}
					}

					$nbrObjects=count($users);
					if($nbrObjects > 1)
					{
						$session->getFlashBag()->add('removeMembers','Ces users sont supprimés de ce projet : ' . ' ' . $teamMember->getProject()->getName());
					}
					else
					{
						$session->getFlashBag()->add('removeMember', $user->getId() . ' est supprimé de ce projet : ' . ' ' . $teamMember->getProject()->getName());
					}
				}
			}

			$em->flush();

			return $this->redirect($this->generateUrl('project_homepage'));
		}

		return $this->render('ProjectBundle:TeamMember:addTeamMember.html.twig',
			array('form'   =>$form->createView(),
				  'origin' =>$origin));
	}

	
}