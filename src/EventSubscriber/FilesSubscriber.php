<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\FileToDeleteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;

class FilesSubscriber implements EventSubscriberInterface
{
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FileToDeleteEvent::NAME => ['delete']
        ];
    }

    public function delete(FileToDeleteEvent $event)
    {
        if ($this->filesystem->exists($event->getFilePath()))
        {
            $this->filesystem->remove($event->getFilePath());
        }
    }
}
