<?php

namespace App\Doctrine\DBAL\Type;

/**
 * Description of TypeContractEnumType
 *
 * @author Lamine Mansouri <mansourilamine19@gmail.com>
 */
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Enum\TypeContractEnum;

class TypeContractEnumType extends Type {

    protected function getEnum(): string {
        return TypeContractEnum::class;
    }

    public function getName() {
        return 'type_contract_enum';
    }

    private string $class;

    public static function addEnumType(string $class): void {
        self::addType($class, self::class);
        self::getType($class)->class = $class; // <------------------------------------------ here
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) {
        $enum = $this->getEnum();
        $cases = array_map(
                fn($enumItem) => "'{$enumItem->name}'",
                $enum::cases(),
        );

        return sprintf('ENUM(%s)', implode(', ', $cases));
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) {
        return true;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) {
//        $enumClass = $this->getEnum();
//        return $enumClass::from($value);
        return $value;
    }

    public function convertToDatabaseValue($enum, AbstractPlatform $platform) {
        //return $enum->value;
        return $enum;
    }
}
