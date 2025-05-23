<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0fb98b59d37122f267e964db1f7466bb
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'DB' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'DBTransaction' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroDB' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroDBException' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroDBParsedQuery' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroDBWalk' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
        'MeekroORM' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'MeekroORMColumn' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'MeekroORMException' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'MeekroORMScope' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'MeekroORMTable' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/orm.class.php',
        'WhereClause' => __DIR__ . '/..' . '/sergeytsalkov/meekrodb/db.class.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit0fb98b59d37122f267e964db1f7466bb::$classMap;

        }, null, ClassLoader::class);
    }
}
