<?php

class ProfileController extends Controller
{
    public function profileAction()
    {
        return array(
            'title' => 'My profile',
            'user' => $this->getUser(),
        );
    }

    public function editProfileAction()
    {
        if (!$user = $this->getUser()) {
            exit();
        }
        $form = new UserForm($user);
        if ($this->request->isPostMethod()) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                // update record
                DB::update($user);
                FormMessage::sendMessage(FormMessage::SUCCESS, 'Your profile is successfully updated.');
                if ($this->request->getValue('SaveAndExit')) {
                    $this->redirectUrl(BASE_URL . '/profile');
                }
            } else {
                FormMessage::sendMessage(FormMessage::ERROR, 'Sorry, saving went wrong... Try again.');
            }
        }
        return array(
            'title' => 'Edit profile',
            'form' => $form,
        );
    }

    public function editLocationAction()
    {
        if (!$user = $this->getUser()) {
            exit();
        }
        if ($user->Location) {
            $address = $user->Location;
        } else {
            $address = new Address();
            $address->User = $user;
        }
        $form = $this->getLocationForm($address);
        if ($this->request->isPostMethod()) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                if (!$address->getId()) {
                    DB::create($address);
                } else {
                    DB::update($address);
                }
                FormMessage::sendMessage(FormMessage::SUCCESS, 'Your location is successfully saved.');
                if ($this->request->getValue('SaveAndExit')) {
                    $this->redirectUrl(BASE_URL . '/profile');
                }
            } else {
                FormMessage::sendMessage(FormMessage::ERROR, 'Sorry, saving went wrong... Try again.');
            }
        }
        return array(
            'title' => 'Edit location',
            'form' => $form,
        );
    }

    public function addWorkAction()
    {
        $record = new WorkExperience();
        $record->User = $this->getUser();
        $form = new WorkExperienceForm($record);
        if ($this->request->isPostMethod()) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                // add record
                if (!DB::create($record, $errors)) {
                    throw new Exception($errors['message'][2], (int)$errors['code']);
                }
                FormMessage::sendMessage(FormMessage::SUCCESS, 'Your work position is successfully added.');
                if ($this->request->getValue('SaveAndExit')) {
                    $this->redirectUrl(BASE_URL . '/profile');
                }
                $this->redirectUrl(BASE_URL . '/profile/edit-work/' . $record->getId());
            } else {
                FormMessage::sendMessage(FormMessage::ERROR, 'Sorry, saving went wrong... Try again.');
            }
        }
        return array(
            'title' => 'Add Position',
            'form' => $form,
        );
    }

    public function removeWorkAction()
    {
        $id = $this->request->getRouteValue('id');
        if ($record = DB::getObjectByID('WorkExperience', $id)) {
            DB::remove($record);
            $this->redirectUrl($this->request->getBackUrl());
        } else {
            $this->handleNotFound('This record was not found');
        }
    }

    public function editWorkAction()
    {
        $id = $this->request->getRouteValue('id');
        /** @var WorkExperience $record */
        $record = DB::getObjectByID('WorkExperience', $id);
        $form = new WorkExperienceForm($record);
        if ($this->request->isPostMethod()) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                // update record
                if (!DB::update($record, $errors)) {
                    throw new Exception($errors['message'][2], (int)$errors['code']);
                }
                FormMessage::sendMessage(FormMessage::SUCCESS, 'Your work position is successfully saved.');
                if ($this->request->getValue('SaveAndExit')) {
                    $this->redirectUrl(BASE_URL . '/profile');
                }
            } else {
                FormMessage::sendMessage(FormMessage::ERROR, 'Sorry, saving went wrong... Try again.');
            }
        }
        return array(
            'title' => 'Edit Position',
            'form' => $form,
        );
    }

    public function addEduAction()
    {
        $record = new Education();
        $record->User = $this->getUser();
        $form = $this->getEducationForm($record);
        if ($this->request->isPostMethod()) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                // add record
                if (!DB::create($record, $errors)) {
                    throw new Exception($errors['message'][2], (int)$errors['code']);
                }
                FormMessage::sendMessage(FormMessage::SUCCESS, 'Your institution is successfully added.');
                if ($this->request->getValue('SaveAndExit')) {
                    $this->redirectUrl(BASE_URL . '/profile');
                }
                $this->redirectUrl(BASE_URL . '/profile/edit-edu/' . $record->getId());
            } else {
                FormMessage::sendMessage(FormMessage::ERROR, 'Sorry, saving went wrong... Try again.');
            }
        }
        return array(
            'title' => 'Add Institution',
            'form' => $form,
        );
    }

    public function editEduAction()
    {
        $id = $this->request->getRouteValue('id');
        /** @var Education $record */
        $record = DB::getObjectByID('Education', $id);
        $form = $this->getEducationForm($record);
        if ($this->request->isPostMethod()) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                // update record
                if (!DB::update($record, $errors)) {
                    throw new Exception($errors['message'][2], (int)$errors['code']);
                }
                FormMessage::sendMessage(FormMessage::SUCCESS, 'Your institution is successfully saved.');
                if ($this->request->getValue('SaveAndExit')) {
                    $this->redirectUrl(BASE_URL . '/profile');
                }
            } else {
                FormMessage::sendMessage(FormMessage::ERROR, 'Sorry, saving went wrong... Try again.');
            }
        }

        return array(
            'title' => 'Edit Institution',
            'form' => $form,
        );
    }

    public function removeEduAction()
    {
        $id = $this->request->getRouteValue('id');
        if ($record = DB::getObjectByID('Education', $id)) {
            DB::remove($record);
            $this->redirectUrl($this->request->getBackUrl());
        } else {
            $this->handleNotFound('This record was not found');
        }
    }

    /**
     * @param Education $record
     * @return Form
     */
    public function getEducationForm(Education $record)
    {
        $form = new Form($record);
        $form->setFieldsMap(array(
            'Status' => array(
                new NotBlank(),
                new Limit(null, 225)
            ),
            'Institution' => array(
                new NotBlank(),
                new Limit(null, 225)
            ),
        ));

        return $form;
    }

    /**
     * @param Address $record
     * @return Form
     */
    public function getLocationForm(Address $record)
    {
        $form = new Form($record);
        $form->setFieldsMap(array(
            'Country' => array(
                new NotBlank(),
                new Limit(null, 225)
            ),
            'Region' => array(
                new NotBlank(),
                new Limit(null, 225)
            ),
            'City' => array(
                new NotBlank(),
                new Limit(null, 225)
            ),
        ));

        return $form;
    }
}
