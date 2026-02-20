<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileManager{

    //pour utiliser slugger il faut faire un constructeur
    public function __construct(private SluggerInterface $slugger){}

    public function upload(UploadedFile $file, string $dir, string $basicName)
    {
        $newName = sprintf('%s-%s.%s', $this->slugger->slug($basicName), uniqid(), $file->guessExtension());
        //on indique où est envoyé le fichier uploadé
        $file->move($dir, $newName);

        return $newName;
    }
}

