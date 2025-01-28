#!/usr/bin/env bash

# Run Sail commands
function sail() {
    (
        ./vendor/bin/sail artisan "$@"
    )
}

# run function
sail "$@"









