<?php
namespace erede\model;

/**
 * Type of urls
 *
 */
abstract class UrlKind
{
    const THREE_D_SECURE_SUCCESS 	= 'ThreeDSecureSuccess';
    const THREE_D_SECURE_FAILURE 	= 'ThreeDSecureFailure';
    const CALLBACK              	= 'Callback';
}