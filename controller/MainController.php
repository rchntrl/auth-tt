<?php

class MainController extends Controller
{

    public function indexAction()
    {
        return array(
            'title' => 'Main page',
        );
    }

    public function changeLocaleAction()
    {
        $lang = $this->request->getRouteValue('lang');
        setcookie('lang', $lang, null, '/');
        $this->redirectUrl($this->request->getBackUrl());
    }

    public function registerAction()
    {
        $user = new User();
        $form = new UserForm($user);
        $form->setFieldsMap(array(
            'PlainPassword' => array(
                new Limit(null, 255),
                new NotBlank(),
                new Password(),
            )
        ));
        if ($this->request->isPostMethod()) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $plainPassword = $user->getPlainPassword();
                DB::create($user, $errors);
                if ($this->registry->auth->login($user->Email, $plainPassword)) {
                    FormMessage::sendMessage(FormMessage::SUCCESS, 'Your account is successfully registered.');
                    $this->redirectUrl(BASE_URL . '/profile');
                }
            }
        }
        return array(
            'title' => 'Create Account',
            'form' => $form,
        );
    }

    public function loginAction()
    {
        if ($this->request->isPostMethod()) {
            $data = $this->request->getRequestData();
            if ($this->registry->auth->login($data['Email'], $data['Password'])) {
                $this->redirectUrl(BASE_URL . '/profile');
            }
        }
        return array(
            'title' => 'Sign in',
        );
    }

    public function logoutAction()
    {
        $this->registry->auth->logout();
    }
}
