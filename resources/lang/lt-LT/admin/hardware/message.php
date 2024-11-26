<?php

return [

<<<<<<< HEAD
    'undeployable' 		=> '<strong>Įspėjimas:</strong> Ši įranga pažymėta kaip negalima išduoti. Jei ši būsena pasikeitė, atnaujinkite ją.',
    'does_not_exist' 	=> 'Tokios įrangos nėra.',
    'does_not_exist_var'=> 'Įranga su numeriu :asset_tag nerasta.',
    'no_tag' 	        => 'Nenurodytas inventorinis numeris.',
    'does_not_exist_or_not_requestable' => 'Tokios įrangos nėra arba jos negalima užsakyti.',
    'assoc_users'	 	=> 'Ši įranga šiuo metu yra išduota naudotojui ir negali būti panaikinta. Pirmiausia paimkite įrangą ir tuomet vėl bandykite panaikinti. ',
    'warning_audit_date_mismatch' 	=> 'Šios įrangos kito audito data (:next_audit_date) yra ankstesnė už paskutinio audito datą (:last_audit_date). Atnaujinkite kito audito datą.',

    'create' => [
        'error'   		=> 'Įrangos sukurti nepavyko, bandykite dar kartą.',
        'success' 		=> 'Įranga sukurta sėkmingai.',
        'success_linked' => 'Įranga su žyma :tag sukurta sėkmingai. <strong><a href=":link" style="color: white;">Spustelėkite čia, kad peržiūrėtumėte</a></strong>.',
    ],

    'update' => [
        'error'   			=> 'Įrangos atnaujinti nepavyko, bandykite dar kartą',
        'success' 			=> 'Įranga atnaujinta sėkmingai.',
        'encrypted_warning' => 'Įranga buvo atnaujinta sėkmingai, tačiau dėl trūkstamų teisių, užšifruoti pasirinktiniai laukai nebuvo atnaujinti',
        'nothing_updated'	=>  'Nebuvo pasirinktas nei vienas laukas, todėl niekas nebuvo atnaujinta.',
        'no_assets_selected'  =>  'Nebuvo pasirinkta jokia įranga, todėl nieko nebuvo atnaujinta.',
        'assets_do_not_exist_or_are_invalid' => 'Pasirinkta įranga negali būti atnaujinta.',
    ],

    'restore' => [
        'error'   		=> 'Įrangos atkurti nepavyko, bandykite dar kartą',
        'success' 		=> 'Įranga atkurta sėkmingai.',
        'bulk_success' 		=> 'Įranga atkurta sėkmingai.',
        'nothing_updated'   => 'Nebuvo pasirinkta jokia įranga, todėl nieko nebuvo atkurta.', 
    ],

    'audit' => [
        'error'   		=> 'Įrangos auditas nesėkmingas: :error ',
=======
    'undeployable' 		 => '<strong>Įspėjimas:</strong> Šis turtas pažymėtas kaip negalimas išduoti. Jei šio turto būsena pasikeitė, atnaujinkite būsenos žymą.',
    'does_not_exist' 	 => 'Tokio turto nėra.',
    'does_not_exist_var' => 'Turtas su numeriu :asset_tag nerastas.',
    'no_tag' 	         => 'Nenurodytas inventorinis numeris.',
    'does_not_exist_or_not_requestable' => 'Tokio turto nėra arba jo negalima užsakyti.',
    'assoc_users'	 	 => 'Šis turtas šiuo metu yra išduotas naudotojui ir negali būti panaikintas. Pirmiausia paimkite turtą ir tuomet vėl bandykite jį panaikinti. ',
    'warning_audit_date_mismatch' 	=> 'Šio turto kito audito data (:next_audit_date) yra ankstesnė už paskutinio audito datą (:last_audit_date). Atnaujinkite kito audito datą.',
    'labels_generated'   => 'Labels were successfully generated.',
    'error_generating_labels' => 'Error while generating labels.',
    'no_assets_selected' => 'No assets selected.',

    'create' => [
        'error'   		=> 'Turto sukurti nepavyko, bandykite dar kartą.',
        'success' 		=> 'Turtas sukurtas sėkmingai.',
        'success_linked' => 'Turtas su žyma :tag sukurtas sėkmingai. <strong><a href=":link" style="color: white;">Spustelėkite čia, kad peržiūrėtumėte</a></strong>.',
        'multi_success_linked' => 'Asset with tag :links was created successfully.|:count assets were created succesfully. :links.',
        'partial_failure' => 'An asset was unable to be created. Reason: :failures|:count assets were unable to be created. Reasons: :failures',
    ],

    'update' => [
        'error'   			=> 'Turto atnaujinti nepavyko, bandykite dar kartą',
        'success' 			=> 'Turtas atnaujintas sėkmingai.',
        'encrypted_warning' => 'Turtas buvo atnaujintas sėkmingai, tačiau dėl nepakankamų teisių, užšifruoti pasirinktiniai laukai nebuvo atnaujinti',
        'nothing_updated'	=>  'Nebuvo pasirinktas nei vienas laukas, todėl niekas nebuvo atnaujinta.',
        'no_assets_selected'  =>  'Nebuvo pasirinkta jokio turto, todėl nieko nebuvo atnaujinta.',
        'assets_do_not_exist_or_are_invalid' => 'Pasirinktas turtas negali būti atnaujintas.',
    ],

    'restore' => [
        'error'   		=> 'Turto atkurti nepavyko, bandykite dar kartą',
        'success' 		=> 'Turtas atkurtas sėkmingai.',
        'bulk_success' 		=> 'Turtas atkurtas sėkmingai.',
        'nothing_updated'   => 'Nebuvo pasirinkta jokio turto, todėl nieko nebuvo atkurta.', 
    ],

    'audit' => [
        'error'   		=> 'Turto auditas nesėkmingas: :error ',
>>>>>>> origin/upstream
        'success' 		=> 'Turto auditas sėkmingai užregistruotas.',
    ],


    'deletefile' => [
        'error'   => 'Failas neištrintas. Bandykite dar kartą.',
        'success' => 'Failas sėkmingai ištrintas.',
    ],

    'upload' => [
        'error'   => 'Failo (-ų) įkelti nepavyko. Bandykite dar kartą.',
        'success' => 'Failas (-ai) įkelti sėkmingai.',
        'nofiles' => 'Nepasirinkote jokio failo įkėlimui arba failas, kurį bandote įkelti, yra per didelis',
        'invalidfiles' => 'Vienas ar keli failai yra per dideli arba neleistinas šis failų formatas. Leidžiami failų tipai yra: png, gif, jpg, doc, docx, pdf ir txt.',
    ],

    'import' => [
<<<<<<< HEAD
=======
        'import_button'         => 'Vykdyti importavimą',
>>>>>>> origin/upstream
        'error'                 => 'Kai kurie elementai nebuvo tinkamai importuoti.',
        'errorDetail'           => 'Šie elementai nebuvo importuoti dėl klaidų.',
        'success'               => 'Jūsų failas buvo importuotas',
        'file_delete_success'   => 'Jūsų failas buvo sėkmingai ištrintas',
        'file_delete_error'      => 'Šio failo ištrinti nepavyko',
        'file_missing' => 'Pažymėtas failas nerastas',
<<<<<<< HEAD
=======
        'file_already_deleted' => 'Pasirinktas failas jau buvo panaikintas',
>>>>>>> origin/upstream
        'header_row_has_malformed_characters' => 'Vienas ar keli antraštinės eilutės atributai turi netinkamai suformuotų UTF-8 simbolių',
        'content_row_has_malformed_characters' => 'Vienas ar keli pirmosios eilutės atributai turi netinkamai suformuotų UTF-8 simbolių',
    ],


    'delete' => [
<<<<<<< HEAD
        'confirm'   	=> 'Ar tikrai norite panaikinti šią įrangą?',
        'error'   		=> 'Bandant panaikinti įrangą įvyko klaida. Bandykite dar kartą.',
        'nothing_updated'   => 'Nebuvo pasirinkta jokia įranga, todėl nieko nebuvo panaikinta.',
        'success' 		=> 'Įranga sėkmingai panaikinta.',
    ],

    'checkout' => [
        'error'   		=> 'Įranga nebuvo išduota, bandykite dar kartą',
        'success' 		=> 'Įranga išduota sėkmingai.',
        'user_does_not_exist' => 'Neteisingas naudotojas. Bandykite dar kartą.',
        'not_available' => 'Ši įranga negali būti išduodama!',
        'no_assets_selected' => 'Turite pasirinkti bent vieną įrangą iš sąrašo',
    ],

    'checkin' => [
        'error'   		=> 'Įranga nebuvo paimta, bandykite dar kartą',
        'success' 		=> 'Įranga paimta sėkmingai.',
        'user_does_not_exist' => 'Neteisingas naudotojas. Bandykite dar kartą.',
        'already_checked_in'  => 'Ši įranga jau yra paimta.',
=======
        'confirm'   	=> 'Ar tikrai norite panaikinti šį turtą?',
        'error'   		=> 'Bandant panaikinti turtą įvyko klaida. Bandykite dar kartą.',
        'nothing_updated'   => 'Nebuvo pasirinkta jokio turto, todėl nieko nebuvo panaikinta.',
        'success' 		=> 'Turtas sėkmingai panaikintas.',
    ],

    'checkout' => [
        'error'   		=> 'Turtas nebuvo išduotas, bandykite dar kartą',
        'success' 		=> 'Turtas išduotas sėkmingai.',
        'user_does_not_exist' => 'Neteisingas naudotojas. Bandykite dar kartą.',
        'not_available' => 'Šis turtas negali būti išduodamas!',
        'no_assets_selected' => 'Turite pasirinkti bent vieną turto vienetą iš sąrašo',
    ],

    'multi-checkout' => [
        'error'   => 'Turtas nebuvo išduotas, bandykite dar kartą|Turtas nebuvo išduotas, bandykite dar kartą',
        'success' => 'Turtas išduotas sėkmingai.|Turtas išduotas sėkmingai.',
    ],

    'checkin' => [
        'error'   		=> 'Turtas nebuvo paimtas, bandykite dar kartą',
        'success' 		=> 'Turtas paimtas sėkmingai.',
        'user_does_not_exist' => 'Neteisingas naudotojas. Bandykite dar kartą.',
        'already_checked_in'  => 'Šis turtas jau yra paimtas.',
>>>>>>> origin/upstream

    ],

    'requests' => [
<<<<<<< HEAD
        'error'   		=> 'Įranga nebuvo užsakyta, bandykite dar kartą',
        'success' 		=> 'Įranga užsakyta sėkmingai.',
=======
        'error'   		=> 'Turtas nebuvo užsakytas, bandykite dar kartą',
        'success' 		=> 'Turtas užsakytas sėkmingai.',
>>>>>>> origin/upstream
        'canceled'      => 'Išdavimo prašymas sėkmingai atšauktas',
    ],

];
