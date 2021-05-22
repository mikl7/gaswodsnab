<?php


class Twig_Extension_LoadAsset extends Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('load_asset',  'load_asset'),
        );
    }

    public function getName()
    {
        return 'load_asset';
    }
}

function load_asset($path, $themePath = 'catalog/view/theme/gaswodsnab') {
    if (file_exists($file = $themePath . '/mix-manifest.json')) {
        $content = json_decode(file_get_contents($file), true);

        return $themePath . $content[$path];
    }

    throw new Exception('mix-manifest.json not found');
}