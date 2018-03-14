<?php

/*
 * Example of uploading file
 */

function uploadProductImages($id) {
    $file = new File('foo', base_dir() . 'public/uploads');

    $params = [
            [
            'type' => 'mime',
            'values' => ['image/jpg', 'image/gif']
        ],
            [
            'type' => 'size',
            'values' => '1K'
        ],
            [
            'type' => 'dimensions',
            'values' => ['width' => 300, 'height' => 200]
        ],
    ];

    $file->addValidations($params);
    $file->setName('smbo');
    $file->save();

    echo $file->getNameWithExtension();
}
