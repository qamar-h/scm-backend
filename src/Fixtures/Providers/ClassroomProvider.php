<?php

namespace App\Fixtures\Providers;


use Faker\Provider\Base as BaseProvider;

final class ClassroomProvider extends BaseProvider
{
   /**
    * @var array List of job titles.
    */
   const NAME_PROVIDER = [
       'name' => [
           'Mathématiques',
           'Français',
           'Arabe',
           'Breton',
           'Anglais',
           'SVT',
           'Physique',
           'Histoire',
           'Dessin',
           'Musique'
       ]
   ];

   /**
    * @return string Random job title.
    */
   public function classroomName():string
   {
       $names = [
           sprintf(
               '%s',
               self::randomElement(self::NAME_PROVIDER['name']),

           ),
        
       ];

       return self::randomElement($names);
   }
}