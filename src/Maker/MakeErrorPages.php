<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\MakerBundle\Maker;

use Doctrine\Common\Annotations\Annotation;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Leclere François <fra.leclere@gmail.com>
 */
final class MakeErrorPages extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:error-pages';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates error pages templates in the right directory')
            //->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeController.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $templateName404 = ('bundles/TwigBundle/Exception/error404.html.twig');
        $templateName403 = ('bundles/TwigBundle/Exception/error403.html.twig');
        $templateName = ('bundles/TwigBundle/Exception/error.html.twig');

        if ($this->isTwigInstalled()) {
            $generator->generateTemplate(
                $templateName404,
                'controller/twig_template.tpl.php',
                [
                    'controller_path' => 'No controller path',
                    'root_directory' => $generator->getRootDirectory(),
                    'class_name' => 'Error 404 page',
                ]
            );
            $generator->generateTemplate(
                $templateName403,
                'controller/twig_template.tpl.php',
                [
                    'controller_path' => 'No controller path',
                    'root_directory' => $generator->getRootDirectory(),
                    'class_name' => 'Error 403 page',
                ]
            );
            $generator->generateTemplate(
                $templateName,
                'controller/twig_template.tpl.php',
                [
                    'controller_path' => 'No controller path',
                    'root_directory' => $generator->getRootDirectory(),
                    'class_name' => 'Default error page',
                ]
            );
        }

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text('Next: Open your new controller class and add some pages!');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            Annotation::class,
            'doctrine/annotations'
        );
    }

    private function isTwigInstalled()
    {
        return class_exists(TwigBundle::class);
    }
}
