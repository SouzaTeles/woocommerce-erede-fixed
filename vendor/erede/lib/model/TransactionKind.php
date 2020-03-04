<?php

namespace erede\model;

/**
 * Type of transaction
 */
abstract class TransactionKind
{
    const CREDIT        = 'credit';
    const DEBIT         = 'debit';
}