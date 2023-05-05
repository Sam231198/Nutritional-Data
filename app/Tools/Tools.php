<?php

namespace App\Tools;

class Tools
{
    private static array $itens = [
        "imported_t",
        "url",
        "creator",
        "created_t",
        "last_modified_t",
        "product_name",
        "quantity",
        "brands",
        "categories",
        "labels",
        "cities",
        "purchase_places",
        "stores",
        "ingredients_text",
        "traces",
        "serving_size",
        "serving_quantity",
        "nutriscore_score",
        "nutriscore_grade",
        "main_category",
        "image_url"
    ];

    public static function selecionarParametros(array $value): array
    {
        $dados = [];
        foreach ($value as $key => $value) {
            if (in_array($key, self::$itens)) $dados[$key] = $value;
        }
        return $dados;
    }


    public static function selecionarQuery(string|null $params = ''): array
    {
        self::$itens[] = 'status';

        if (!$params) return [];

        $params = explode(";", $params);
        $dados = self::explodeParamsOption($params);

        return $dados;
    }

    private static function explodeParamsOption(string|array|null $params = '')
    {
        if (is_string($params)) $params[] = $params;

        $dados = [];

        foreach ($params as $value) {
            $params = explode(":", $value);

            if (in_array($params[0], self::$itens)) {
                $dados[$params[0]] = $params[1];
            }
        }

        return $dados;
    }
}
