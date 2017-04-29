<?php

/**
 * Shortcut for Debug::dump() method
 */
function dump(...$args)
{
    return Debug::dump(...$args);
}

/**
 * Shortcut for Debug::error() method
 */
function error(...$args)
{
    return Debug::error(...$args);
}

/**
 * Shortcut for Debug::ajaxError() method
 */
function ajax_error(...$args)
{
    return Debug::ajaxError(...$args);
}
