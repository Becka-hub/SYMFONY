<?php
namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Exception;

class Service
{

    private ManagerRegistry $managerRegistry;

    public function __construct( ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function json_decode()
    {
        try {
            return file_get_contents('php://input') ?
                json_decode(file_get_contents('php://input')) : [];
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function em()
    {
        return $this->managerRegistry->getManager();
    }

    public function fichier64($type,$brochure,$dataBase64)
    {
        $imageName = time().$type;
        $fileName = $brochure.$imageName;
        $file = fopen($fileName, 'wb');
        $data = explode(',',$dataBase64);
        fwrite($file, base64_decode(count($data) === 2 ? $data[1] : $data[0]));
        fclose($file);
        return $imageName;

    }
}