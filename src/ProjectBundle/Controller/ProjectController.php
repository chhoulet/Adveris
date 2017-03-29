<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ProjectBundle\Entity\Project;
use ProjectBundle\Form\ProjectType;

class ProjectController extends Controller
{
	//Homepage du site, avec présentation de la liste des projets et contrôle du role de l'user pour avoir accès 
	// à la fonction de création d'un projet
    public function homeAction()
    {
    	$em=$this->getDoctrine()->getManager();
    	$listProjects=$em->getRepository('ProjectBundle:Project')->findAll();
    	$role=null;
    	
    	//Contrôle des roles de l'user au sein des équipes dont il fait partie
    	$user=$this->getUser();
    	if($user)
    	{
	    	$teams=$user->getTeamMembers();
	    	foreach($teams as $team)
	    	{
	    		if($team && $team->getRole()==3)
	    		{
	    			$role=3;
	    		}
	    	}    		
    	}

        return $this->render('ProjectBundle:Home:home.html.twig', 
        	array('listProjects'=>$listProjects,
        		  'user'        =>$user,
        		  'role'        =>$role));
    }

    //Création d'un projet, instanciation de la variable affich qui déterminera le titre dans le template
    //Celui-ci sera réutilisé pour l'édition
    public function createAction(Request $request)
    {
    	$em=$this->getDoctrine()->getManager();
    	$session=$request->getSession();
    	$affich=1;
    	$project=new Project();
    	$form=$this->createForm(new ProjectType(), $project);

    	$form->handleRequest($request);

    	if($form->isValid())
    	{
    		$em->persist($project);
    		$em->flush();

    		$session->getFlashBag()->add('create', $project->getName().' a bien été créé !');
    		return $this->redirect($this->generateUrl('project_homepage'));
    	}

    	return $this->render('ProjectBundle:Project:create.html.twig',
    		array('form'=>$form->createView(),
    			  'affich'=>$affich));
    }

    public function updateAction(Request $request, $idProject)
    {
    	$em=$this->getDoctrine()->getManager();
    	$session=$request->getSession();
    	$affich=2;

    	//Controle de l'existence de l'idProject et de sa correspondance avec un projet réel:
    	if($idProject)
    	{
    		$listID=[];
    		$listProjects=$em->getRepository('ProjectBundle:Project')->findAll();
    		foreach($listProjects as $project)
    		{
    			$id=$project->getId();
    			$listID[]=$id;
    		}

    		if(in_array($idProject, $listID))
    		{
    			$project=$em->getRepository('ProjectBundle:Project')->find($idProject);
		    	$form=$this->createForm(new ProjectType(), $project);

		    	$form->handleRequest($request);

		    	if($form->isValid())
		    	{
		    		$em->persist($project);
		    		$em->flush();

		    		$session->getFlashBag()->add('edit', $project->getName().' a bien été mis à jour !');
		    		return $this->redirect($this->generateUrl('project_homepage'));
		    	}
    		}
    	}
    	else 
    	{
    		throw new NotFoundHttpException("Projet inexistant !");    		
    	}   	

    	return $this->render('ProjectBundle:Project:create.html.twig',
    		array('form'=>$form->createView(),
    			  'affich'=>$affich));
    }


    // Récupération du projet et vérification des droits de l'user connecté sur celui-ci
    public function editAction($idProject)
    {
        $em=$this->getDoctrine()->getManager();
        $role=null;
        $team_members=null; 

        //Récupération de l'user
        $user=$this->getUser();
        {
            if($user)
            {
                $id=$user->getId();             
            }
        }

        //Contrôle de l'existence de l'idProject et de sa correspondance avec un projet réel:
        if($idProject)
        {
            $listID=[];
            $listProjects=$em->getRepository('ProjectBundle:Project')->findAll();
            foreach($listProjects as $project)
            {
                $id=$project->getId();
                $listID[]=$id;
            }

            if(in_array($idProject, $listID))
            {
                $project=$em->getRepository('ProjectBundle:Project')->find($idProject);             
                //Récupération des teams du projet, et des membres pour chaque team
                $team_members=$project->getTeamMembers();
                if($id) //Vérif que l'user est authentifié
                {
                    if($team_members)
                    {           
                        $listIdUsers=[];        
                        foreach($team_members as $team)
                        {
                            $users=$team->getUsers();
                            if($users)
                            {
                                foreach($users as $user)
                                {
                                    $idUser=$user->getId();
                                    $listIdUsers[]=$idUser;                             
                                }
                                //Vérif si l'user est bien membre de l'équipe
                                if(in_array($id, $listIdUsers))
                                {
                                    $idTeam=$team->getId();
                                    $role=$team->getRole();
                                }
                            }
                        }                                   
                    }
                }
            }

            return $this->render('ProjectBundle:Project:edit.html.twig', 
                array('project'     =>$project,
                      'user'        =>$user,
                      'role'        =>$role,
                      'team_members'=>$team_members));
        }
        else
        {
            throw new NotFoundHttpException("Projet inexistant !");
        }
    }

    public function removeAction(Request $request, $idProject)
    {
        $em=$this->getDoctrine()->getManager();
        $session=$request->getSession();
        $affich=2;

        //Controle de l'existence de l'idProject et de sa correspondance avec un projet réel:
        if($idProject)
        {
            $listID=[];
            $listProjects=$em->getRepository('ProjectBundle:Project')->findAll();
            foreach($listProjects as $project)
            {
                $id=$project->getId();
                $listID[]=$id;
            }

            if(in_array($idProject, $listID))
            {
                $project=$em->getRepository('ProjectBundle:Project')->find($idProject);
                $em->remove($project);
                /*$em->flush();*/

                $session->getFlashBag()->add('remove', 'Ce projet est définitivement supprimé');
                return $this->redirect($this->generateUrl('project_homepage'));
            }
            else
            {
                throw new NotFoundHttpException("Projet inexistant !");
            }
        }
    }

    //Sélection des projets de l'user
    public function listByUserAction(Request $request, $userId)
    {
        $em=$this->getDoctrine()->getManager();
        $session=$request->getSession();
        $userConnected=$this->getUser();
        $idUserConnected=$userConnected->getId();

        //Contrôle de la validité du paramètre:
        $users=$em->getRepository('UserBundle:User')->findAll();
        $listIdUsers=[];
        foreach($users as $user)
        {
            $id=$user->getId();
            $listIdUsers[]=$id;
        }

        if(in_array($userId, $listIdUsers))
        {
            //Contrôle des droits de l'user connecté
            if($userId == $idUserConnected)
            {                                
                $teamMembers=$idUserConnected->getTeamMembers();
                if($teamMembers != null)
                {
                    $projects=[];
                    foreach($teamMembers as $team)
                    {
                        $role=$team->getRole();
                        $idProject=$team->getProject();
                        $project=$em->getRepository('ProjectBundle:Project')->find($idProject);
                        $projects[]=[$role, $project, $team];
                    }
    
                    return $this->render('ProjectBundle:Project:listByUser.html.twig', 
                        array('projects'=>$projects,
                              'user'    =>$user,
                              'userConnected'=>$userConnected));                    
                }
                else
                {
                    $session->getFlashBag()->add('echec', 'Vous n\'avez aucun projet enregistré !');
                    return $this->redirect($this->generateUrl('project_homepage'));
                }
            }
            else
            {
                $session->getFlashBag()->add('echecRights', 'Vous n\'avez pas les droits pour accéder à ce projet !');
                return $this->redirect($this->generateUrl('project_homepage'));
            }            
        }
        else
        {
            throw new NotFoundHttpException("Projet inexistant !");
        }
    }
}
