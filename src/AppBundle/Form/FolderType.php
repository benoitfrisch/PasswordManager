<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (count($options['parent']) > 0) {
            $groups = array_intersect($options['user'], $options['parent']);
        } else {
            $groups = $options['user'];
        }
        $builder
            ->add('name', null, ['label' => 'name'])
            ->add('hidden', null, ['required' => true, 'label' => 'hidden'])
            ->add('groups', null, ['choices' => $groups, 'expanded' => true, 'required' => true, 'label' => 'groups'])
            ->add('save', SubmitType::class, ['label' => 'create_folder']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null,
            'parent' => null,
        ]);
    }
}