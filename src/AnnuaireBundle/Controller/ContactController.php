<?php

namespace AnnuaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AnnuaireBundle\Entity\Contact;
use AnnuaireBundle\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactController extends Controller
{
    public function indexAction()
    {
        return $this->render('AnnuaireBundle::index.html.twig');
    }
    /**
     * Create action.
     */
    public function listeAction()
    {
		$contacts = $this->findContacts();
        //$mode = false;
		
        return $this->render('AnnuaireBundle::liste.html.twig', [
		    'contacts' => $contacts, 
            //'form' => $form->createView(),
			//'mode'=> $mode,
        ]);
    }
    
	/**
     * Add action.
     */
    public function addAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm('AnnuaireBundle\Form\ContactType', $contact)
                 ->add ('save', new SubmitType(),[
                     'attr'=>[
                         'class'=>"btn btn-sm btn-success",
                     ]
                 ]);                           

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact); 
            $em->flush($contact);
        
            return $this->redirect($this->generateUrl('annuaire_liste'));    
        }

		//$contacts = $this->findContacts();
		//$mode = false;
		
        return $this->render('AnnuaireBundle::ajout.html.twig', [
		   // 'contacts' => $contacts, 
            'form' => $form->createView(),
			//'mode'=> $mode,
        ]);
    }
	
	/**
     * Edit action.
     */
    public function editAction(Request $request, $id)
    {
        $contact = $this
		    ->getDoctrine()
            ->getRepository('AnnuaireBundle:Contact')
            ->findOneBy(['id'=>$id]);
			
        $editForm = $this
                ->createForm(new ContactType(), $contact);
				
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annuaire_liste');
        }
         
		$contacts = $this->findContacts();
		//$mode = false; 
		 
        return $this->render('AnnuaireBundle::ajout.html.twig', array(
            'contacts' => $contacts,
            'form' => $editForm->createView(),
			//'mode'=> $mode,
        ));
    }
	
	/**
     * Delete action.
     */
    public function deleteAction($id)
    {
        $contact = $this->findById($id);
			
		$contact->setTrashed(true);
		
		$em = $this->getDoctrine()->getManager();
        $em->persist($contact); 
        $em->flush($contact);

        return $this->redirectToRoute('annuaire_liste');
    }
	
    public function findContacts()
	{
	    return $this
		    ->getDoctrine()
            ->getRepository('AnnuaireBundle:Contact')
            ->findAll(); 
	}
	public function findById($id)
	{
	    return $this
		    ->getDoctrine()
            ->getRepository('AnnuaireBundle:Contact')
            ->findOneBy(['id'=>$id]); 
	}
}
