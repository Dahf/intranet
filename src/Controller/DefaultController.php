<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Traits\HasChatRepository;
use App\Traits\HasProjectRepository;
use App\Traits\HasUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;

class DefaultController extends AbstractController
{
    use HasChatRepository;
    use HasUserRepository;
    use HasProjectRepository;

    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        $user  = $this->getUser();
        $chats = $this->chatRepository->findAll();
        $projects = $this->projectRepository->findAll();
        $count = $user->getProjects();
 
        return $this->render('default/index.html.twig', [   
            'user' => $user,
            'users' => $users, 
            'chats' => $chats,    
            'projects' => $projects,
            'count' => $count,
        ]);
    }

    public function chat(int $id): Response
    {
        $chat = $this->chatRepository->find($id);
        
        if (!$chat instanceof Chat) {
            return $this->createNotFoundException();
        }

        $user  = $this->getUser();
        $chatusers = $chat->getUsers();
        $chatToUser = null;
        foreach($chatusers as $usersInChat){
            if($usersInChat != $user){
                $chatToUser = $usersInChat;
            }
        }
        
        if($chatToUser === null){
            return new Response('', Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!in_array($user, $chatusers->toArray())) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }
        $chats = $this->chatRepository->findAll();
        return $this->render('chat/detail.html.twig', [  
            'user' => $user,    
            'chat' => $chat, 
            'chats' => $chats,    
            'chatusers' => $chatusers,
            'chatToUser' => $chatToUser,
        ]); 
    }
}
