<?php

return array(

    'does_not_exist' => 'Lokacija ne obstaja.',
<<<<<<< HEAD
    'assoc_users'    => 'This location is not currently deletable because it is the location of record for at least one asset or user, has assets assigned to it, or is the parent location of another location. Please update your models to no longer reference this company and try again. ',
=======
    'assoc_users'    => 'Te lokacije trenutno ni mogoče izbrisati, ker je lokacija zapisa za vsaj eno sredstvo ali uporabnika, ker so ji dodeljena sredstva ali ker je starševska lokacija druge lokacije. Posodobite svoje zapise tako, da se na to lokacijo ne boste več sklicevali, in poskusite znova. ',
>>>>>>> origin/upstream
    'assoc_assets'	 => 'Ta lokacija je trenutno povezana z vsaj enim sredstvom in je ni mogoče izbrisati. Prosimo, posodobite svoja sredstva, da ne bodo več vsebovali te lokacije in poskusite znova. ',
    'assoc_child_loc'	 => 'Ta lokacija je trenutno starš vsaj ene lokacije otroka in je ni mogoče izbrisati. Posodobite svoje lokacije, da ne bodo več vsebovale te lokacije in poskusite znova. ',
    'assigned_assets' => 'Dodeljena sredstva',
    'current_location' => 'Trenutna lokacija',
    'open_map' => 'Odpri v :map_provider_icon Zemljevidih',


    'create' => array(
        'error'   => 'Lokacija ni bila ustvarjena, poskusite znova.',
        'success' => 'Lokacija je bila uspešno ustvarjena.'
    ),

    'update' => array(
        'error'   => 'Lokacija ni posodobljena, poskusite znova',
        'success' => 'Lokacija je bila posodobljena.'
    ),

    'restore' => array(
        'error'   => 'Lokacija ni bila obnovljena, poskusite znova',
        'success' => 'Lokacija je bila uspešno obnovljena.'
    ),

    'delete' => array(
        'confirm'   	=> 'Ali ste prepričani, da želite izbrisati to lokacijo?',
        'error'   => 'Prišlo je do težave z brisanjem lokacije. Prosim poskusite ponovno.',
        'success' => 'Lokacija je bila uspešno izbrisana.'
    )

);
