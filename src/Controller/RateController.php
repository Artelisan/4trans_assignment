<?php

namespace App\Controller;

use App\Form\Rate\RateFormType;
use App\Service\RateService;
use DateTime;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RateController extends AbstractController
{
    #[Route(path: '/', name: 'rate_index_action')]
    #[Template('rate/index.html.twig')]
    public function indexAction(Request $request, RateService $rateService): array
    {
        $form = $this->createForm(RateFormType::class)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->getData()['date'] > new DateTime()) {
                $error = new FormError('Dátum nesmie byť neskôr než dnes.');
                $form->get('date')->addError($error);
                $form->addError($error);
            }

            $rate = $rateService->getRate($form->getData()['currency'], $form->getData()['date']);

            if (!$rate) {
                $date = date_format($form->getData()['date'], 'd F Y');
                $error = new FormError("Kurz pre menu {$form->getData()['currency']} z dňa $date nebol nájdený.");
                $form->get('currency')->addError($error);
                $form->addError($error);
            }

            if ($form->isValid()) {
                return [
                    'form' => $form->createView(),
                    'rate' => $rate,
                ];
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
