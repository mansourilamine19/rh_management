<?php

namespace App\Enum;

/**
 * Description of RoleUserEnum
 *
 * @author Lamine Mansouri <mansourilamine19@gmail.com>
 */
enum RoleUserEnum: string {

    case ROLE_SUPER_ADMIN = "Super administrateur";
    case ROLE_ADMIN = "Administrateur";
    case ROLE_RH = "Resource humaine";
}
