<?php
namespace App\EventListener;
/**
 * Description of AccessDeniedListener
 *
 * @author Lamine Mansouri <mansourilamine19@gmail.com>
 */

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AccessDeniedListener
{
    public function __construct(private Environment $twig) {}

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedException) {
            $html = $this->twig->render('security/access_denied.html.twig');
            $response = new Response($html, 403);
            $event->setResponse($response);
        }
    }
}
