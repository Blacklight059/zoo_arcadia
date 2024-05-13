<?php

namespace App\ServiceImages;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService 
{
    private $params;
    
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(
        UploadedFile $picture, 
        ?string $folder = '', 
        ) 
    {
        $fichier = md5(uniqid(rand(), true)) . '.jpeg';

        $picture_infos = getImagesize($picture);

        if($picture_infos === false) {
            throw new Exception("Format d\'image incorrect");
        }

        switch($picture_infos['mime']) {
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_source = imageCreateFromJpeg($picture);
                break;
            case 'image/jpg':
                $picture_source = imagecreatefromjpeg($picture);
                break;                
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            default:
                throw new Exception("Format d\'image incorrect");
        }

      
        $path = $this->params->get('images_directory') . $folder;

        $picture->move($path . '/', $fichier);

        return $fichier;
    }

    public function delete(string $fichier, 
    ?string $folder = '', 
    ?int $width = 250, 
    ?int $height = 250)
    {
        if($fichier !== 'default.jpeg'){
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . 'mini/' . $width . 'x' . $height . '-' . $fichier ;

            if(file_exists($mini)){
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;

            if(file_exists($$original)){
                unlink($mini);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}
