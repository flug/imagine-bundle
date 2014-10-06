<?php

namespace Clooder\ImagineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCacheClooderImagineCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('clooder:imagine:clear:cache')
            ->setDescription('Clear cache of images');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $fs                          = $this->getContainer()->get('filesystem');
        $clooderImagineConfiguration = $this->getContainer()->get('clooder.imagine.configuration');
        $kernelRootDir               = $this->getContainer()->getParameter('kernel.root_dir');

        $removePath = $kernelRootDir . "/../web" . $clooderImagineConfiguration->getCacheDirectory();
        if ($fs->exists($removePath)) {
            $fs->remove($removePath);
        }


    }
}


