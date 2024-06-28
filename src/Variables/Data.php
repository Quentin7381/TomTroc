<?php

namespace Variables;

/**
 * Class Data
 * @package Variables
 *
 * Data is a class that holds the data for the Variables class.
 * It is a hierarchical data structure, where each level is a new instance of Data.
 * The final value is the target of most requests to Data. Any request that want to get the data object instead will need to catch the exception.
 */
class Data extends Structure
{


    public function get(string $key): mixed
    {
        try {
            return parent::get($key);
        } catch (Exception $e) {
            if ($e->getCode() == Exception::STRUCTURE_PATH_NOT_FOUND) {
                throw new Exception(Exception::DATA_NOT_FOUND, ['key' => $key], $e);
            }
            
            throw $e;
        }
    }
}

/*
    Data stocke une structure de données hiérarchique.
    Chaque niveau de l'arborescence est une nouvelle instance de Data, jusqu'à arriver à une valeur finale.
    La valeur finale (end point) est la cible de la plupart des requêtes vers Data.
    Si la valeur demandée n'existe pas, une exception est levée, avec le chemin complet de la requête.
    Si la valeur demandée n'est pas un end point, une exception est levée. Elle contiens la donnée et le chemin complet de la requête.
*/
