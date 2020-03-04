<?php

namespace erede\model;

/**
* Class ThreeDSecureOnFailure
*
* ThreeDSecureOnFailure indicates behaviour for 3DS transaction.
*/
abstract class ThreeDSecureOnFailure
{
    const CONTINUE_PROCESSING 	= 'continue';
    const DECLINE 				= 'decline';
}