<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use App\Traits\HasChatRepository;
use App\Traits\HasEntityManager;
use App\Traits\HasUserRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends AbstractController
{
    use HasEntityManager;
    use HasChatRepository;
    use HasUserRepository;

    public function create(Request $request): Response
    {
        $userToId = (int) $request->request->get('id');
        $user  = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createNotFoundException();
        }

        if ($user->getId() === $userToId) {
            throw new Exception('no chat with yourself');
        }
        
        $userTo = $this->userRepository->find($userToId);
        
        if (!$userTo instanceof User) {
            throw $this->createNotFoundException();
        }

        $chatsTo = $this->chatRepository->find($userToId);
        $chatsFrom = $this->chatRepository->find($user->getId());

        foreach($chatsTo as $chats){
            foreach($chatsFrom as $chats2){
                if($chats === $chats2){
                    return new Response('already chat with that person', Response::HTTP_NOT_ACCEPTABLE);
                }
            }
        }



        $chat = new Chat();

        $chat
            ->addUser($user)
            ->addUser($userTo);

        $this->entityManager->persist($chat);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    public function addMessage(Request $request): Response
    {
        $message = new Message();
        $user  = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createNotFoundException();
        }

        $chatId = (int) $request->request->get('chatid');
        $chat = $this->chatRepository->find($chatId);

        if (!$chat instanceof Chat) {
            throw $this->createNotFoundException();
        }

        $userToId = (int) $request->request->get('userid');
        $userTo = $this->userRepository->find($userToId);

        if (!$userTo instanceof User) {
            throw $this->createNotFoundException();
        }
        $content = (string) $request->request->get('content');

        $now = new DateTimeImmutable('now');

        $message
                ->setUserFrom($user)
                ->setUserTo($userTo)
                ->setChat($chat)
                ->setCreatedAt($now)
                ->setContent($content);
        
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_CREATED);
    }

}