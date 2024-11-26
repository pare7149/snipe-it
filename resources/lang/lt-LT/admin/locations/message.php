<?php

return array(

    'does_not_exist' => 'Tokios vietos nėra.',
<<<<<<< HEAD
    'assoc_users'    => 'This location is not currently deletable because it is the location of record for at least one asset or user, has assets assigned to it, or is the parent location of another location. Please update your models to no longer reference this company and try again. ',
    'assoc_assets'	 => 'Ši vieta šiuo metu yra susieta bent su viena įranga ir negali būti panaikinta. Atnaujinkite savo įrangą, kad nebebūtų sąsajos su šia vieta, ir bandykite dar kartą. ',
    'assoc_child_loc'	 => 'Ši vieta šiuo metu yra kaip pagrindinė bent vienai žemesnio lygio vietai ir negali būti panaikinta. Atnaujinkite savo žemesnio lygio vietas, kad nebebūtų sąsajos su šia vieta, ir bandykite dar kartą. ',
    'assigned_assets' => 'Priskirta įranga',
=======
    'assoc_users'    => 'Šios vietos negalima panaikinti, nes ji yra bent vieno turto vieneto ar naudotojo vieta, jai yra priskirtas turtas arba ji yra nurodyta kaip pagrindinė kitos vietos vieta. Atnaujinkite savo įrašus, kad jie nebeturėtų sąsajų su šia vieta ir bandykite dar kartą. ',
    'assoc_assets'	 => 'Ši vieta šiuo metu yra susieta bent su vienu turto vienetu ir negali būti panaikinta. Atnaujinkite savo turtą, kad nebebūtų sąsajos su šia vieta, ir bandykite dar kartą. ',
    'assoc_child_loc'	 => 'Ši vieta šiuo metu yra kaip pagrindinė bent vienai žemesnio lygio vietai ir negali būti panaikinta. Atnaujinkite savo žemesnio lygio vietas, kad nebebūtų sąsajos su šia vieta, ir bandykite dar kartą. ',
    'assigned_assets' => 'Priskirtas turtas',
>>>>>>> origin/upstream
    'current_location' => 'Dabartinė vieta',
    'open_map' => 'Atidaryti :map_provider_icon žemėlapiuose',


    'create' => array(
        'error'   => 'Vieta nebuvo sukurta. Bandykite dar kartą.',
        'success' => 'Vieta sukurta sėkmingai.'
    ),

    'update' => array(
        'error'   => 'Vieta nebuvo atnaujinta. Bandykite dar kartą',
        'success' => 'Vieta atnaujinta sėkmingai.'
<<<<<<< HEAD
=======
    ),

    'restore' => array(
        'error'   => 'Vieta nebuvo atkurta. Bandykite dar kartą',
        'success' => 'Vieta atkurta sėkmingai.'
>>>>>>> origin/upstream
    ),

    'delete' => array(
        'confirm'   	=> 'Ar tikrai norite panaikinti šią vietą?',
        'error'   => 'Bandant panaikinti vietą įvyko klaida. Bandykite dar kartą.',
        'success' => 'Vieta panaikinta sėkmingai.'
    )

);
