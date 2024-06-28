#!/bin/bash
echo "$(source "/usr/local/bin/core-install.sh")"
echo "$(wp faker core content --pages=5 || true)"
echo "$(source "/usr/local/bin/zip-plugin.sh")"