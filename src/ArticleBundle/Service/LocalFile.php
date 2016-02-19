<?php
/**
 * Created by PhpStorm.
 * User: imie
 * Date: 2/19/16
 * Time: 9:59 AM
 */

namespace ArticleBundle\Service;


use Symfony\Component\HttpKernel\KernelInterface;

class LocalFile{

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string
     */
    private $store_folder;


    /**
     * LocalFile constructor.
     * @param KernelInterface $kernel
     */
    //injection de dépendance ici car on passe le kernel ds constructeur
    public function __construct(KernelInterface $kernel, $store_folder){ //on passe l'interface Kernel plutot que la classe kernel

        $this->kernel = $kernel;
        $this->store_folder = $store_folder;
    }

    public function store($data){

        $filename = $this->kernel->getRootDir()."/Data/file1.dat";
        $dataEncoded = json_encode($data);
        file_put_contents($filename, $dataEncoded);

    }


}