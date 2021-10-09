<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Traits\HasEntityManager;
use App\Traits\HasProjectRepository;
use App\Traits\HasUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;

class ProjectController extends AbstractController
{
    
    use HasEntityManager;
    use HasUserRepository;
    use HasProjectRepository;

    public function create(Request $request): Response
    {
        $user  = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createNotFoundException();
        }  

        $projectName = (string) $request->request->get('projectName');
        $projectStatus = (string) $request->request->get('projectStatus');
        $projectDate = (string) $request->request->get('projectTimeline');
        $projectUserArray = (array) $request->request->get('projectUser');

        $projectProgress = (float) 0;

        $project = new Project();
        $project
            ->setProjectName($projectName)
            ->setProjectProgress($projectProgress)
            ->setProjectStatus($projectStatus);
        $newformat = Carbon::parse($projectDate, 'Europe/Berlin');
        $project->setProjectTimeline($newformat);
        $project->addProjectMember($user);
        foreach($projectUserArray as $userToAdd){
            $project->addProjectMember($this->userRepository->find($userToAdd));
        }

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        $user  = $this->getUser();
        $projects = $this->projectRepository->findAll();
        $count = $user->getProjects();
        return $this->render('project/index.html.twig', [
            'user' => $user,   
            'users' => $users,
            'projects' => $projects,
            'count' => $count,
        ]);
    }
}
