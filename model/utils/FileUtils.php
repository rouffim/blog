<?php


namespace model\utils;


use Exception;
use model\AbstractModel;

class FileUtils {

    /**
     * @param string $file
     * @return string
     */
    static function getFileExtension(string $file): string {
        return strtolower(pathinfo($file,PATHINFO_EXTENSION));
    }

    /**
     * @param string $ch
     * @return string
     */
    static function getResourcesPath(string $ch = ''): string {
        return "resources/$ch";
    }

    /**
     * @param string $ch
     * @return string
     */
    static function getImagesPath(string $ch = ''): string {
        return FileUtils::getResourcesPath("images/$ch");
    }

    /**
     * @param AbstractModel $model
     * @param string $extension
     * @return string
     * @throws Exception
     */
    static function getModelImagePath(AbstractModel $model, string $extension): string {
        if(is_null($model)) {
            throw new Exception('Given model is null.');
        }
        if(is_null($model->MODEL_IMAGE_PATH)) {
            throw new Exception('Given model has not image path.');
        }
        return FileUtils::getImagesPath($model->MODEL_IMAGE_PATH . $model->getUuid() . ".$extension");
    }

}
