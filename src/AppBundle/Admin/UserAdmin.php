<?php

namespace AppBundle\Admin;

use FOS\UserBundle\Model\UserManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{

    /**
     * @var UserManager
     */
    protected $userManager;

    public function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('firstname');
        $formMapper->add('lastname');
        $formMapper->add('username');
        $formMapper->add('plainPassword', TextType::class, ['required' => false]);
        $formMapper->add('email', 'email');
        $formMapper->add('enabled');
        $formMapper->add('groups', null, ['expanded' => true]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('firstname');
        $datagridMapper->add('lastname');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('firstname');
        $listMapper->addIdentifier('lastname');
        $listMapper->add('_action', 'actions', [
            'actions' => [
                'edit' => [],
            ]
        ]);
    }

    public function prePersist($object)
    {
        $this->userManager->updatePassword($object);
        $this->userManager->updateCanonicalFields($object);
    }

    public function preUpdate($object)
    {
        $this->userManager->updatePassword($object);
        $this->userManager->updateCanonicalFields($object);
    }


}