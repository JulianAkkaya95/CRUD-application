<?php

namespace App\Controller;

use App\Entity\Artikel;
use App\Form\Type\ArtikelType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArtikelController extends AbstractController
{

    /**
     * Holt alle Artikel aus der Datenbank.
     * @return Response
     * @Route("/artikel", name="getAllArtikel", methods={"GET","HEAD"})
     * @Route("/")
     */
    public function getAllArtikel(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $artikel = $entityManager->getRepository(Artikel::class)->findAll();

        return $this->render('artikelOverview.html.twig', ["artikel" => $artikel]);
    }

    /**
     * Holt genau einen Artikel aus der Datenbank.
     * @Route("/artikel/{id}", methods={"GET","HEAD"})
     * @param int $id z.B. 1 oder 16
     * @return Response
     */
    public function getArtikel(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $artikel = $entityManager->getRepository(Artikel::class)->find($id);

        return $this->render('artikelDetail.html.twig', ['artikel' => $artikel]);
    }

    /**
     * Ermöglicht das bearbeiten und hinzufügen von Artikeln.
     * Holt einen Artikel und gibt ein Formular zurück, mit dem dieser bearbeitet werden kann,
     * Wenn kein Formular übergeben wurde, wird ein neuer Artikel erstellt, der über das Fomular gespeichert werden kann.
     * @Route("/edit/{id}", name="editMask")
     * @param Request $request
     * @param int $id z.B. 1 oder 16
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, int $id = NULL): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (isset($id)) {
            $artikel = $entityManager->getRepository(Artikel::class)->find($id);
        }
        //Auch für im Fall das eine ungültige ID übergeben wurde, soll ein neuer Artikel erstellt werden.
        if (!isset($artikel)) {
            $artikel = new Artikel();
        }

        $form = $this->createForm(ArtikelType::class, $artikel);
        $form->handleRequest($request);
        $this->saveEditArtikel($form, $artikel, $entityManager);

        return $this->render('editMask.html.twig', [
            "form" => $form->createView(),
            "artikel" => $artikel
        ]);
    }

    /**
     * Speichert Artikel die durch das Formular verändert oder hinzugefügt worden sind.
     * @param FormInterface $form
     * @param Artikel $artikel
     * @param ObjectManager $entityManager
     * @throws \Exception
     */
    function saveEditArtikel(FormInterface $form, Artikel &$artikel, ObjectManager $entityManager)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                //Für jedes Bild muss ein eindeutiger Name erstellt werden.
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
                $artikel->setImage($newFilename);
            }
            $artikel = $form->getData();
            $artikel->setLastModified(new DateTime());
            $entityManager->persist($artikel);
            $entityManager->flush();
        }
    }

    /**
     * Löscht einzelne Artikel
     * @Route("delete/artikel/{id}")
     * @param int $id z.B. 1 oder 16
     * @return Response
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $artikel = $entityManager->getRepository(Artikel::class)->find($id);
        $entityManager->remove($artikel);
        $entityManager->flush();
        return $this->redirectToRoute("getAllArtikel", [], 302);
    }
}