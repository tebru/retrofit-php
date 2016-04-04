<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

/**
 * Class BasicController
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Route("/api/basic")
 */
class BasicController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/{entity}/{id}", name="basic_auth", defaults={"id" = 1})
     */
    public function indexAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse([
            'method' => $request->getRealMethod(),
            'path' => $request->getPathInfo(),
            'query_params' => $request->query->all(),
            'headers' => $request->headers->all(),
            'content' => $this->getContent($request),
            'files' => $_FILES,
            'user' => [
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
            ],
        ]);
    }

    /**
     * Get content from request
     *
     * @param Request $request
     * @return array|mixed|null
     */
    private function getContent(Request $request)
    {
        $return = null;
        switch ($request->headers->get('content-type')) {
            case 'application/json':
                $return = json_decode($request->getContent(), true);
                break;
            default:
                $return = $request->request->all();
                break;
        }

        return $return;
    }
}
