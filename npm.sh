#!/bin/bash

command="docker run -it --rm --volume $(pwd):/app -w=/app node:9.0 npm $@"

eval $command

