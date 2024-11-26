<?php

return array(

    'does_not_exist' => 'Lokacija ne postoji.',
<<<<<<< HEAD
    'assoc_users'    => 'This location is not currently deletable because it is the location of record for at least one asset or user, has assets assigned to it, or is the parent location of another location. Please update your models to no longer reference this company and try again. ',
=======
    'assoc_users'    => 'Lokacija trenutno nije obrisiva zato što je to lokacija zapisa barem jedne imovine ili korisnika, ima imovinu zaduženju njoj, ili je nadlokacija druge lokacije. Molim vas izmenite vaše podatke tako da više nemaju vezu ka ovoj lokaciji i pokušajte ponovo. ',
>>>>>>> origin/upstream
    'assoc_assets'	 => 'Ta je lokacija trenutno povezana s barem jednim resursom i ne može se izbrisati. Ažurirajte resurs da se više ne referencira na tu lokaciju i pokušajte ponovno. ',
    'assoc_child_loc'	 => 'Ta je lokacija trenutno roditelj najmanje jednoj podredjenoj lokaciji i ne može se izbrisati. Ažurirajte svoje lokacije da se više ne referenciraju na ovu lokaciju i pokušajte ponovo. ',
    'assigned_assets' => 'Dodeljena imovina',
    'current_location' => 'Trenutna lokacija',
    'open_map' => 'Otvori u :map_provider_icon mapama',


    'create' => array(
        'error'   => 'Lokacija nije kreirana, pokušajte ponovo.',
        'success' => 'Lokacija je uspešno kreirana.'
    ),

    'update' => array(
        'error'   => 'Lokacija nije ažurirana, pokušajte ponovo',
        'success' => 'Lokacija je uspešno ažurirana.'
    ),

    'restore' => array(
        'error'   => 'Lokacija nije povraćena, molim vas pokušajte ponovo',
        'success' => 'Lokacija je uspešno povraćena.'
    ),

    'delete' => array(
        'confirm'   	=> 'Jeste li sigurni da želite izbrisati tu lokaciju?',
        'error'   => 'Došlo je do problema s brisanjem lokacije. Molim pokušajte ponovo.',
        'success' => 'Lokacija je uspešno obrisana.'
    )

);
