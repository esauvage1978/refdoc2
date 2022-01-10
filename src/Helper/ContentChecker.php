<?php


namespace App\Helper;


class ContentChecker
{



    public static function run(?string $content): ?string
    {

        $polluant=[
            "MsoIntenseEmphasis",
            "MsoNormal",
            "Mso-bidi-font-weight:bold",
            "Mso-bidi-font-style:italic"
        ];
        
        $content= str_replace($polluant," ", $content);

        return preg_replace('#\/\/<xml>[\s\S]+\/\/</xml>#', '', $content);

    }



}
