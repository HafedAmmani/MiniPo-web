<?php


namespace minipoBundle\Redirection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AfterLoginRedirection implements AuthenticationSuccessHandlerInterface
{
    private $router;

    /**
     * AfterLoginRedirection constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request        $request
     *
     * @param TokenInterface $token
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $roles = $token->getRoles();

        $rolesTab = array_map(function ($role) {
            return $role->getRole();
        }, $roles);

        if (in_array('ROLE_ADMIN', $rolesTab, true)) {

            $redirection = new RedirectResponse($this->router->generate('minipo_AcceuilAdmin'));
        }
        if (in_array('ROLE_EMPLOYE', $rolesTab, true)) {

            $redirection = new RedirectResponse($this->router->generate('minipo_employe'));
        }
        if (in_array('ROLE_CLIENT', $rolesTab, true)) {

            $redirection = new RedirectResponse($this->router->generate('minipo_client'));
        }
        if (in_array('ROLE_RH', $rolesTab, true)) {

            $redirection = new RedirectResponse($this->router->generate('minipo_Afficher'));
        }
        if (in_array('ROLE_BLOG', $rolesTab, true)) {

            $redirection = new RedirectResponse($this->router->generate('minipo_listblog'));
        }
        if (in_array('ROLE_LIVREUR', $rolesTab, true)) {

            $redirection = new RedirectResponse($this->router->generate('minipo_afficheLivreur'));
        }
        return $redirection;

    }

}