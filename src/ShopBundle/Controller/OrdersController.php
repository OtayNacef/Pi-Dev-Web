<?php

namespace ShopBundle\Controller;

use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;
use JMS\Payment\CoreBundle\PluginController\PluginController;
use JMS\Payment\CoreBundle\PluginController\Result;
use ShopBundle\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrdersController extends Controller
{
    public function newAction($amount)
    {
        $em = $this->getDoctrine()->getManager();

        $order = new Order($amount);
        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('app_orders_show', [
                'orderId' => $order->getId(),
            ]
        );
    }

    public function showAction($orderId, Request $request)
    {
        $order = $this->getDoctrine()->getManager()->getRepository(Order::class)->find($orderId);

        $form = $this->createForm(ChoosePaymentMethodType::class, null, [
            'amount' => $order->getAmount(),
            'currency' => 'EUR',
        ]);
        $ppc = $this->container->get('payment.plugin_controller');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ppc->createPaymentInstruction($instruction = $form->getData());

            $order->setPaymentInstruction($instruction);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush($order);

            return $this->redirectToRoute('app_orders_paymentcreate', [
                'orderId' => $order->getId(),
            ]);
        }

        return $this->render('@Shop/Order/show.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    private function createPayment(Order $order)
    {
        $ppc = $this->container->get('payment.plugin_controller');
        $instruction = $order->getPaymentInstruction();
        $pendingTransaction = $instruction->getPendingTransaction();

        if ($pendingTransaction !== null) {
            return $pendingTransaction->getPayment();
        }

        $amount = $instruction->getAmount() - $instruction->getDepositedAmount();

        return $ppc->createPayment($instruction->getId(), $amount);
    }


    public function paymentCreateAction($orderId)
    {
        $ppc = $this->container->get('payment.plugin_controller');
        $order = $this->getDoctrine()->getManager()->getRepository(Order::class)->find($orderId);

        $payment = $this->createPayment($order, $ppc);

        $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());

        if ($result->getStatus() === Result::STATUS_PENDING) {
            $ex = $result->getPluginException();

            if ($ex instanceof ActionRequiredException) {
                $action = $ex->getAction();

                if ($action instanceof VisitUrl) {
                    return $this->redirect($action->getUrl());
                }
            }
        }

        throw $result->getPluginException();

        // In a real-world application you wouldn't throw the exception. You would,
        // for example, redirect to the showAction with a flash message informing
        // the user that the payment was not successful.
    }

}
