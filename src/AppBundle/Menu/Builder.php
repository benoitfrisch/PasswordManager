<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->addChild('menu.home', ['route' => 'home']);
        $menu->addChild('menu.create', ['route' => 'createFolder']);
        $menu->addChild('menu.hidden', ['route' => 'hiddenFolders']);
        return $menu;
    }
}