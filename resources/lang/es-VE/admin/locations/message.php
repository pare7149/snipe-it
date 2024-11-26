<?php

return array(

<<<<<<< HEAD
    'does_not_exist' => 'La ubicación no existe.',
    'assoc_users'    => 'This location is not currently deletable because it is the location of record for at least one asset or user, has assets assigned to it, or is the parent location of another location. Please update your models to no longer reference this company and try again. ',
    'assoc_assets'	 => 'Esta ubicación está actualmente asociada con al menos un activo y no puede ser borrada. Por favor actualiza tus activos para no referenciar más esta ubicación e inténtalo de nuevo. ',
    'assoc_child_loc'	 => 'Esta ubicación es actualmente padre al menos una ubicación hija y no puede ser borrada. Por favor actualiza tus ubicaciones para no referenciar más esta ubicación e inténtalo de nuevo. ',
    'assigned_assets' => 'Recursos asignados',
=======
    'does_not_exist' => 'La localización no existe.',
    'assoc_users'    => 'Esta ubicación no se puede eliminar actualmente porque es la ubicación de al menos un activo o usuario, tiene activos asignados o es la ubicación padre de otra ubicación. Por favor, actualice sus registros para que ya no hagan referencia a esta ubicación e inténtalo de nuevo. ',
    'assoc_assets'	 => 'Esta ubicación está actualmente asociada con al menos un activo y no puede ser eliminada. Por favor actualice sus activos para que ya no hagan referencia a esta ubicación e inténtelo de nuevo. ',
    'assoc_child_loc'	 => 'Esta ubicación es actualmente el padre de al menos una ubicación hija y no puede ser eliminada.   Por favor actualice sus ubicaciones para que ya no hagan referencia a esta ubicación e inténtelo de nuevo. ',
    'assigned_assets' => 'Activos asignados',
>>>>>>> origin/upstream
    'current_location' => 'Ubicación actual',
    'open_map' => 'Abrir en mapas :map_provider_icon',


    'create' => array(
        'error'   => 'La ubicación no pudo ser creada, por favor, inténtelo de nuevo.',
        'success' => 'La ubicación fue creada exitosamente.'
    ),

    'update' => array(
        'error'   => 'La ubicación no pudo ser actualizada, por favor inténtelo de nuevo',
        'success' => 'La ubicación fue actualizada exitosamente.'
    ),

    'restore' => array(
        'error'   => 'No se ha restaurado la ubicación, inténtelo de nuevo',
        'success' => 'La ubicación fue restaurada exitosamente.'
    ),

    'delete' => array(
        'confirm'   	=> '¿Está seguro de que desea eliminar esta ubicación?',
        'error'   => 'Hubo un problema borrando la ubicación. Por favor, Inténtelo de nuevo.',
        'success' => 'La ubicación fue eliminada exitosamente.'
    )

);
