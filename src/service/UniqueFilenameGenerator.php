<?php

namespace App\service;

class UniqueFilenameGenerator
{
    public function generateUniqueFilename($imageName, $imageExtension)
    {
        $currentTimestamp = time();
        $nameHashed = hash('sha256', $imageName);

        $imageNewName = uniqid() . '-' . $nameHashed . '-' . $currentTimestamp . '.' . $imageExtension;
        return $imageNewName;
    }
}

//Un test unitaire teste de manière automatique une fonctionnalité
//(une classe ou plusieurs classes travaillant ensemble).

//Un test fonctionnel (e2e) teste de manière automatique une fonctionnalité :
//en imitant l'utilisateur, donc quand je clique sur le bouton de suppression
//que l'élément est bien supprimé en BDD
