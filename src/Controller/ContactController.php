<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Message;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/contact", name="contact_")
 */
class ContactController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        if($request->isMethod('POST')) {
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {

                $contact_exist = $this->em->getRepository(Contact::class)->findOneBy(['email' => $request->request->get('contact')['email'] ]);
                if($contact_exist)
                    $contact = $contact_exist;

                $message = new Message();
                $message->setSubject($request->request->get('contact')['subject']);
                $message->setContent($request->request->get('contact')['message']);
                $this->em->persist($message);

                $contact->addMessage($message);
                $this->em->persist($contact);
                $this->em->flush();

                $filename = str_replace(' ', '_', $contact->getName()) . time() . '.json';
                $data = [
                    'name'    => $contact->getName(),
                    'email'   => $contact->getEmail(),
                    'date'    => $message->getDate(),
                    'subject' => $message->getSubject(),
                    'message' => $message->getContent()
                ];
                file_put_contents('messages_json/'.$filename, json_encode($data));

                $this->addFlash('success', 'Your message has been send successfully !');
            }
        }

        return $this->render('contact/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{contact}/show", name="show")
     * @param Contact $contact
     * @return Response
     */
    public function show(Contact $contact): Response
    {

        return $this->render('contact/show.html.twig', [
            'contact' => $contact
        ]);
    }

    /**
     * @Route("/{message}/status", name="status")
     * @param Message $message
     * @return Response
     */
    public function status(Message $message): Response
    {

        if($message->getDone() === false) {
            $message->setDone(true);
        }
        else {
            $message->setDone(false);
        }

        $this->em->persist($message);
        $this->em->flush();

        $contact = $message->getContact();

        return $this->render('contact/show.html.twig', [
            'contact' => $contact
        ]);
    }
}
