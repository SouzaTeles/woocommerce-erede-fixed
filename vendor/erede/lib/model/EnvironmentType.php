<?php
namespace erede\model;

/**
* Class EnvironmentType
*
* EnvironmentType maps which environment will connect.
*/
abstract class EnvironmentType
{
    const HOMOLOG    = 0;
    const PRODUCTION = 1;
    const DEVELOP    = 2;
}
